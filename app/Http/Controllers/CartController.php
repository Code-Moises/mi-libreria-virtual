<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Cart;

class CartController extends Controller
{
    private function getCart(): Cart
    {
        if (!session()->has('cart')) {
            session(['cart' => new Cart()]);
        }
        return session('cart');
    }

    // 1. VER EL CARRITO
    public function index()
    {
        $cart = $this->getCart();

        $booksGrouped = [];
        foreach ($cart->getBooks() as $book) {
            if (!isset($booksGrouped[$book->id])) {
                $booksGrouped[$book->id] = [
                    'book' => $book,
                    'qty' => $cart->countBook($book)
                ];
            }
        }

        return view('cart', [
            'booksGrouped' => $booksGrouped,
            'total' => $cart->getTotal(),
            'cartCount' => count($cart)
        ]);
    }

    // 2. AÑADIR (Desde el Catálogo - Primera vez)
    public function add($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // VALIDACIÓN 1: Stock agotado (0 unidades en tienda)
        if ($book->stock <= 0) {
            return redirect()->back()->with('error', "❌ No hay stock disponible de: " . $book->title);
        }

        // VALIDACIÓN 2: ¿Ya está en el carrito? (Lo que pediste)
        if ($cart->has($book)) {
            return redirect()->back()->with('error', "⚠️ El producto ya está en el carrito.");
        }

        $cart->add($book);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Libro añadido al carrito.');
    }

    // 3. INCREMENTAR (Botón "+" del carrito)
    public function increment($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // VALIDACIÓN: No superar el stock máximo al sumar
        if ($cart->countBook($book) >= $book->stock) {
            return redirect()->back()->with('error', "❌ No puedes añadir más. Solo quedan {$book->stock} unidades.");
        }

        $cart->add($book); // Añadimos uno más
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cantidad aumentada.');
    }

    // 4. DISMINUIR (Botón "-")
    public function decrease($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        // Bloqueo: No bajar de 1
        if ($cart->countBook($book) <= 1) {
            return redirect()->back()->with('error', 'La cantidad mínima es 1.');
        }

        $cart->removeOne($book);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cantidad reducida.');
    }

    // 5. ELIMINAR (Papelera)
    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $cart = $this->getCart();

        $cart->delete($book);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Libro eliminado.');
    }

    // 6. VACIAR
    public function clear()
    {
        $cart = $this->getCart();
        $cart->clear();
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Carrito vaciado.');
    }
}
