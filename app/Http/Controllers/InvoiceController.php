<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Controlador encargado de procesar las compras y gestionar las facturas (Invoices).
 */
class InvoiceController extends Controller
{
    /**
     * 1. PROCESAR LA COMPRA (Checkout)
     * Convierte los items del carrito en una factura real, resta el stock y vacía el carrito.
     */
    public function store()
    {
        // 1. Recuperamos el carrito de la sesión actual
        $cart = session()->get('cart');

        // 2. Seguridad: Si el carrito no existe o está vacío, redirigimos al home.
        // Usamos count() directamente porque la clase Cart implementa la interfaz Countable de PHP.
        if (!$cart || count($cart) === 0) {
            return redirect()->route('home')->with('error', 'El carrito está vacío.');
        }

        // Obtenemos el usuario autenticado que está haciendo la compra
        $user = Auth::user();

        try {
            // 3. TRANSACCIÓN DE BASE DE DATOS (DB::transaction)
            // Esto es VITAL en e-commerce. Ejecuta todas las consultas (crear factura, líneas, restar stock).
            // Si CUALQUIER paso falla a la mitad, Laravel hace un "Rollback" (deshace todo).
            // Así evitamos que se reste stock si la factura no se pudo guardar, o viceversa.
            $invoiceId = DB::transaction(function () use ($cart, $user) {

                // 4. AGRUPACIÓN DE LIBROS
                // El modelo Cart guarda las unidades sueltas: [LibroA, LibroA, LibroB].
                // Para una factura real, necesitamos líneas consolidadas: LibroA (Cantidad: 2), LibroB (Cantidad: 1).
                $booksGrouped = [];
                foreach ($cart->getBooks() as $book) {
                    if (!isset($booksGrouped[$book->id])) {
                        $booksGrouped[$book->id] = [
                            'book' => $book,
                            'qty' => $cart->countBook($book) // Cuenta cuántas copias hay en el array
                        ];
                    }
                }

                // 5. VARIABLES ACUMULADORAS PARA TOTALES
                $totalBase = 0;
                $totalTax = 0;
                $linesData = []; // Array temporal para guardar los datos de las líneas

                // 6. PROCESAR CADA LÍNEA Y RESTAR STOCK
                foreach ($booksGrouped as $item) {
                    $book = $item['book'];
                    $qty = $item['qty'];

                    // Asumimos un IVA fijo del 21% (0.21)
                    $price = $book->pvp;
                    $taxRate = 0.21;

                    // Cálculos matemáticos de la línea
                    $lineTotal = $price * $qty;
                    $lineTax = $lineTotal * $taxRate;

                    // Sumamos a los totales globales de la factura
                    $totalBase += $lineTotal;
                    $totalTax += $lineTax;

                    // RESTAR STOCK: Muy importante hacerlo dentro de la transacción
                    $book->decrement('stock', $qty);

                    // Guardamos los datos de la línea para insertarlos después de crear la cabecera
                    $linesData[] = [
                        'book_id' => $book->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'tax_rate' => $taxRate,
                    ];
                }

                // 7. CREAR LA CABECERA DE LA FACTURA (Invoice)
                $invoice = Invoice::create([
                    'user_id' => $user->id,
                    'invoice_number' => 'FAC-' . strtoupper(Str::random(10)), // Genera ej: FAC-A1B2C3D4E5

                    // "SNAPSHOT" (FOTO) DE LOS DATOS DEL CLIENTE:
                    // Guardamos el nombre y dirección que tiene el usuario AHORA MISMO.
                    // Si dentro de un año el usuario cambia de dirección de casa en su perfil,
                    // esta factura antigua NO debe cambiar (por motivos legales/contables).
                    'client_dni' => $user->dni ?? 'N/A',
                    'client_name' => $user->name,
                    'client_lastname' => $user->lastname,
                    'client_address' => $user->addrs,

                    'total_base' => $totalBase,
                    'total_tax' => $totalTax,
                    'total' => $totalBase + $totalTax,
                ]);

                // 8. GUARDAR LAS LÍNEAS (InvoiceLine) VINCULADAS A LA FACTURA
                foreach ($linesData as $line) {
                    $line['invoice_id'] = $invoice->id; // Asociamos la línea con el ID de la factura recién creada
                    InvoiceLine::create($line);
                }

                // Si todo sale bien, la transacción termina y devuelve el ID de la factura nueva
                return $invoice->id;
            });

            // 9. VACIAR EL CARRITO TRAS LA COMPRA EXITOSA
            $cart->clear();
            session()->put('cart', $cart); // Sobrescribimos la sesión con el carrito vacío

            // 10. REDIRIGIR A LA PANTALLA DE CARGA (Pasando el ID de la nueva factura)
            return redirect()->route('invoice.loader', ['id' => $invoiceId]);

        } catch (\Exception $e) {
            // Si algo falló (ej: base de datos caída), capturamos la excepción y mostramos un error amigable.
            return redirect()->route('cart.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * 2. PANTALLA DE CARGA (Loader)
     * Muestra un spinner durante unos segundos antes de mostrar la factura.
     */
    public function loader($id)
    {
        // Seguridad: where('user_id', Auth::id()) asegura que un usuario no pueda ver
        // la pantalla de carga de una factura que pertenece a otro usuario cambiando el ID en la URL.
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

        return view('invoices.loader', ['invoice' => $invoice]);
    }

    /**
     * 3. VER FACTURA (Invoice / Detalle)
     * Muestra la factura final completa con todas sus líneas.
     */
    public function show($id)
    {
        // Eager Loading (Carga ansiosa): usamos with('lines.book') para evitar el problema de N+1 consultas.
        // Esto trae la factura, todas sus líneas, y los libros asociados a esas líneas en solo 2 o 3 consultas SQL eficientes.
        $invoice = Invoice::with('lines.book')->where('user_id', Auth::id())->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * 4. MIS COMPRAS (Historial del Usuario)
     * Lista todas las facturas del usuario autenticado actual.
     */
    public function index()
    {
        // Ordenamos por fecha de creación descendente (las más nuevas primero).
        $invoices = Invoice::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('invoices.index', compact('invoices'));
    }
}
