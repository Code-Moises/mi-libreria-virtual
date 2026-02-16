<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    // 1. PROCESAR LA COMPRA
    public function store()
    {
        // Recuperamos el carrito de la sesión
        $cart = session()->get('cart');

        // CORRECCIÓN: Usamos count() directamente porque tu clase implementa Countable
        if (!$cart || count($cart) === 0) {
            return redirect()->route('home')->with('error', 'El carrito está vacío.');
        }

        $user = Auth::user();

        try {
            $invoiceId = DB::transaction(function () use ($cart, $user) {

                // IMPORTANTE: Agrupar los libros.
                // Tu array es: [LibroA, LibroA, LibroB].
                // La factura necesita: LibroA (x2), LibroB (x1).
                // Si no hacemos esto, la factura tendría líneas repetidas.
                $booksGrouped = [];
                foreach ($cart->getBooks() as $book) {
                    if (!isset($booksGrouped[$book->id])) {
                        $booksGrouped[$book->id] = [
                            'book' => $book,
                            'qty' => $cart->countBook($book) // Usamos tu método propio
                        ];
                    }
                }

                // Calcular totales
                $totalBase = 0;
                $totalTax = 0;
                $linesData = [];

                foreach ($booksGrouped as $item) {
                    $book = $item['book'];
                    $qty = $item['qty'];

                    // Asumimos IVA 21%
                    $price = $book->pvp;
                    $taxRate = 0.21;

                    $lineTotal = $price * $qty;
                    $lineTax = $lineTotal * $taxRate;

                    $totalBase += $lineTotal;
                    $totalTax += $lineTax;

                    // Restar Stock
                    $book->decrement('stock', $qty);

                    // Preparamos la línea para guardar
                    $linesData[] = [
                        'book_id' => $book->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'tax_rate' => $taxRate,
                    ];
                }

                // A. Crear Cabecera Factura
                $invoice = Invoice::create([
                    'user_id' => $user->id,
                    'invoice_number' => 'FAC-' . strtoupper(Str::random(10)),
                    'client_dni' => $user->dni ?? 'N/A',
                    'client_name' => $user->name,
                    'client_lastname' => $user->lastname,
                    'client_address' => $user->addrs,
                    'total_base' => $totalBase,
                    'total_tax' => $totalTax,
                    'total' => $totalBase + $totalTax,
                ]);

                // B. Guardar Líneas
                foreach ($linesData as $line) {
                    $line['invoice_id'] = $invoice->id;
                    InvoiceLine::create($line);
                }

                return $invoice->id;
            });

            // Vaciar carrito
            $cart->clear();
            session()->put('cart', $cart);

            // Redirigir al loader
            return redirect()->route('invoice.loader', ['id' => $invoiceId]);

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // 2. LOADER
    public function loader($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
        return view('invoices.loader', ['invoice' => $invoice]);
    }

    // 3. VER FACTURA
    public function show($id)
    {
        $invoice = Invoice::with('lines.book')->where('user_id', Auth::id())->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    // 4. MIS COMPRAS
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }
}
