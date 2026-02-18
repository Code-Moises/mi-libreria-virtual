<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // El trait 'HasFactory' permite usar Book::factory()->create() en los Seeders
    // para generar cientos de libros con datos falsos de prueba rápidamente.
    use HasFactory;

    /**
     * Define explícitamente a qué tabla de la base de datos apunta este modelo.
     */
    protected $table = 'books';

    /**
     * $fillable - Campos permitidos para la Asignación Masiva (Mass Assignment).
     * Si hacemos algo como Book::create($request->all()), Laravel SOLO guardará en la base de datos
     * los campos que estén en esta lista.
     * Si un hacker modifica el HTML del formulario y añade un campo falso `<input name="is_admin" value="1">`,
     * Laravel lo ignorará por completo porque 'is_admin' no está en el $fillable.
     */
    protected $fillable = [
        'isbn',
        'title',
        'author',
        'pvp',
        'iva',
        'stock',
        'description',
        'editorial',
        'image',
        'sales_count',
    ];
}
