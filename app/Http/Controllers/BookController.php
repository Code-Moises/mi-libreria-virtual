<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Book;

/**
 * Controlador principal del catálogo de libros.
 * Se encarga de mostrar la página principal con filtros, paginación y el detalle de cada libro.
 */
class BookController extends Controller
{
    /**
     * Muestra la página principal (Home / Top Ventas / Catálogo).
     * Recibe el objeto Request para poder leer los parámetros de la URL (ej: ?search=harry&author=Rowling).
     */
    public function index(Request $request)
    {
        // 1. Iniciamos el "Query Builder" (Constructor de consultas).
        // Book::query() nos permite ir encadenando filtros (WHERE) antes de ir a la base de datos.
        $query = Book::query();

        // 2. Filtro de Búsqueda de texto libre (Título o Autor)
        // 'filled' comprueba que el parámetro 'search' exista en la URL y no esté vacío.
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            // Agrupamos esta condición en una función anónima (Closure).
            // Esto equivale en SQL a: AND (title LIKE '%texto%' OR author LIKE '%texto%')
            // Es vital agruparlo para que el "OR" no rompa otros filtros que añadamos después.
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('author', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 3. Filtro exacto por Autor (Desplegable)
        if ($request->filled('author')) {
            // Aquí buscamos una coincidencia exacta en la columna 'author'
            $query->where('author', $request->input('author'));
        }

        // 4. Ejecutar la consulta con Paginación
        // En lugar de usar ->get(), usamos ->paginate(10) para traer los resultados de 10 en 10.
        // Laravel genera automáticamente los enlaces de "Siguiente" y "Anterior".
        $books = $query->paginate(10);

        // 5. Mantener los filtros al cambiar de página
        // Si el usuario busca "Harry" y pasa a la página 2, la URL debe ser ?search=Harry&page=2
        // El método appends() inyecta los parámetros actuales en los enlaces de paginación.
        $books->appends([
            'search' => $request->input('search'),
            'author' => $request->input('author')
        ]);

        // 6. Obtener datos auxiliares para la vista
        // Sacamos una lista de autores únicos para rellenar el `<select>` del buscador.
        // distinct(): Evita autores repetidos.
        // pluck('author'): En lugar de traer objetos completos, trae solo un array simple con los nombres.
        $authors = Book::select('author')->distinct()->orderBy('author')->pluck('author');

        // Sacamos los 5 libros más vendidos (Top Ventas) ordenando de mayor a menor.
        $topBooks = Book::orderBy('sales_count', 'desc')->take(5)->get();

        // 7. Enviar todas las variables a la vista 'home'
        // compact('books') es equivalente a ['books' => $books]
        return view('home', compact('books', 'topBooks', 'authors'));
    }

    /**
     * Muestra el detalle de un libro individual(Book $book)
     * Magia de Laravel: "Route Model Binding".
     * Al tipar la variable como "Book", Laravel busca automáticamente en la base de datos
     * el libro cuyo ID coincida con el de la URL (ej: /libro/5).
     * Si no existe, devuelve un error 404 automáticamente. ¡Nos ahorramos el Book::findOrFail($id)!
     */
    public function show(Book $book)
    {
        // Enviamos el objeto $book directamente a la vista 'show'
        return view('show', compact('book'));
    }
}
