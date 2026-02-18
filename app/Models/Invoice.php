<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * $guarded - La alternativa a $fillable.
     * En el modelo Book usamos $fillable para decir "SOLO permite estos campos".
     * Aquí usamos $guarded vacío ([]), que significa "Permite TODOS los campos".
     * * ¿Por qué es seguro aquí? Porque las facturas NO se crean directamente con lo
     * que envía el usuario en un formulario ($request->all()). Se crean internamente
     * en nuestro InvoiceController con un array controlado estrictamente por nosotros.
     */
    protected $guarded = [];

    // --- CONSTANTES DE LA LIBRERÍA ---
    public const LIBRARY_NAME = "Mi Librería Virtual";
    public const LIBRARY_CIF = "B-12345678";
    public const LIBRARY_ADDRESS = "Calle del Conocimiento, 42";
    public const LIBRARY_PHONE = "+34 91 123 45 67";

    /**
     * Relación: Una factura PERTENECE A (belongsTo) un Usuario.
     * Laravel buscará automáticamente la columna 'user_id' en la tabla 'invoices'
     * y la enlazará con la tabla 'users'.
     * Uso: $invoice->user->name (Sacaría el nombre del usuario vinculado a la factura).
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una factura TIENE MUCHAS (hasMany) Líneas de Factura.
     * Laravel buscará la columna 'invoice_id' en la tabla 'invoice_lines'.
     * Uso: $invoice->lines (Devolvería una Colección con todos los libros comprados en esa factura).
     */
    public function lines() {
        return $this->hasMany(InvoiceLine::class);
    }
}
