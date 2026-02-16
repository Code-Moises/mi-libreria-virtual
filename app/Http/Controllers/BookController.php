<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Iniciamos la consulta base
        $query = Book::query();

        // Filtro 1. Si el usuario escribió algo en el buscador...
        if ($request->filled('search')) { // 'filled' comprueba que no esté vacío
            $searchTerm = $request->input('search');

            // Filtramos donde el título se parezca o el autor se parezca
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('author', 'LIKE', "%{$searchTerm}%");
            });
        }

        // filtro 2. Si el usuario seleccionó un autor
        if ($request->filled('author')) {
            $query->where('author', $request->input('author'));
        }

        // Obtener los libros paginados usando la variable $query
        $books = $query->paginate(10);

        // Esto hace que al cambiar de página (1, 2, 3) no se pierda la búsqueda
        $books->appends([
            'search' => $request->input('search'),
            'author' => $request->input('author')
        ]);

        // Obtener lista de autores únicos para el desplegable
        // "pluck" saca solo la columna 'author' y "sort" los ordena alfabéticamente
        $authors = Book::select('author')->distinct()->orderBy('author')->pluck('author');

        // Obtener los 5 libros más vendidos
        $topBooks = Book::orderBy('sales_count', 'desc')->take(5)->get();

        // enviamos ambas listas a la vista home
        return view('home', compact('books', 'topBooks', 'authors'));
    }

    public function show(Book $book)
    {
        return view('show', compact('book'));
    }
}
