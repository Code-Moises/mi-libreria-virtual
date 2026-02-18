<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\Cart;

/**
 * Controlador encargado de gestionar el Carrito de Compras.
 * Utiliza las sesiones de Laravel para mantener el estado del carrito entre distintas peticiones HTTP.
 */
class CartController extends Controller
{
    /**
     * Método auxiliar privado para obtener el carrito actual de la sesión.
     * Si el usuario acaba de entrar y no tiene carrito, le crea uno nuevo vacío.
     */
    private function getCart(): Cart
    {
        // session()->has() verifica si existe la clave 'cart' en la sesión actual
        if (!session()->has('cart')) {
            // Si no existe, guardamos una nueva instancia de nuestra clase Cart
            session(['cart' => new Cart()]);
        }

        // Retornamos el objeto Cart almacenado
        return session('cart');
    }

    /**
     * 1. VER EL CARRITO
     * Prepara los datos del carrito para mostrarlos en la vista principal del carrito.
     */
    public function index()
    {
        $cart = $this->getCart();

        // AGRUPACIÓN DE LIBROS:
        // El modelo Cart guarda cada libro de forma individual.
        // Si añades el mismo libro 3 veces, el array interno tendrá 3 objetos de ese libro.
        // Para la vista, es mejor agruparlos (Ej: "Harry Potter -> Cantidad: 3") en lugar de mostrar 3 filas.
        $booksGrouped = [];
        foreach ($cart->getBooks() as $book) {
            // Si el ID del libro aún no está en nuestro array agrupado, lo añadimos
            if (!isset($booksGrouped[$book->id])) {
                $booksGrouped[$book->id] = [
                    'book' => $book, // Guardamos el objeto entero para poder pintar su título, imagen, etc.
                    'qty' => $cart->countBook($book) // Calculamos cuántas veces aparece en el carrito
                ];
            }
        }

        // Enviamos los datos agrupados, el total calculado y el número total de items a la vista
        return view('cart', [
            'booksGrouped' => $booksGrouped,
            'total' => $cart->getTotal(),
            'cartCount' => count($cart)
        ]);
    }

    /**
     * 2. AÑADIR (Desde el Catálogo - Primera vez)
     * Añade un libro al carrito cuando el usuario hace clic desde la tienda.
     */
    public function add($id)
    {
        // findOrFail busca en la BD. Si el ID es inventado o borrado, lanza un error 404 automáticamente.
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // VALIDACIÓN DE NEGOCIO 1: Stock general agotado (0 unidades en la tienda real)
        if ($book->stock <= 0) {
            // Redirigimos de vuelta a la página anterior (back) con un mensaje flash de error
            return redirect()->back()->with('error', "No hay stock disponible de: " . $book->title);
        }

        // VALIDACIÓN DE NEGOCIO 2: ¿Ya está en el carrito?
        // En nuestro sistema, desde el catálogo solo dejamos añadirlo la primera vez.
        // Para sumar más unidades, el usuario debe ir a la vista del carrito y usar el botón "+".
        if ($cart->has($book)) {
            return redirect()->back()->with('error', "El producto ya está en el carrito.");
        }

        $cart->add($book);

        // Tras modificar el objeto $cart, debemos volver a guardarlo en la sesión
        // para que los cambios persistan en la siguiente recarga de página.
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Libro añadido al carrito.');
    }

    /**
     * 3. INCREMENTAR (Botón "+" dentro de la vista del carrito)
     * Suma una unidad más a un libro que ya tenemos en el carrito.
     */
    public function increment($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // VALIDACIÓN DE NEGOCIO: Límite de Stock
        // No podemos permitir que el usuario meta 5 libros en el carrito si en la BD solo quedan 3.
        if ($cart->countBook($book) >= $book->stock) {
            return redirect()->back()->with('error', "No puedes añadir más. Solo quedan {$book->stock} unidades.");
        }

        $cart->add($book); // Reutilizamos el método add() porque añade una instancia más al array
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cantidad aumentada.');
    }

    /**
     * 4. DISMINUIR (Botón "-" dentro de la vista del carrito)
     * Resta una unidad a un libro del carrito.
     */
    public function decrease($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // BLOQUEO INFERIOR: La cantidad mínima es 1.
        // Si el usuario quiere 0 unidades, debe usar el botón de eliminar (papelera), no el de restar.
        if ($cart->countBook($book) <= 1) {
            return redirect()->back()->with('error', 'La cantidad mínima es 1.');
        }

        // removeOne() borra solo una instancia de ese libro del array interno
        $cart->removeOne($book);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cantidad reducida.');
    }

    /**
     * 5. ELIMINAR (Botón de la Papelera)
     * Borra el libro completamente del carrito, sin importar la cantidad que tuviera.
     */
    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // El método delete() de nuestra clase Cart elimina TODAS las instancias que coincidan con ese libro
        $cart->delete($book);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Libro eliminado.');
    }

    /**
     * 6. VACIAR CARRITO
     * Elimina todos los elementos del carrito de golpe.
     */
    public function clear()
    {
        $cart = $this->getCart();

        // Llamamos al método interno clear() que vacía el array
        $cart->clear();
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Carrito vaciado.');
    }
}
