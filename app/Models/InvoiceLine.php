<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    /**
     * $guarded vacío.
     * Al igual que en Invoice, permitimos la asignación masiva de todos los campos
     * porque estos datos los generamos nosotros mismos de forma segura en el InvoiceController,
     * no vienen directamente de un formulario público rellenado por el usuario.
     */
    protected $guarded = [];

    /**
     * Relación: Una línea de factura PERTENECE A (belongsTo) un Libro específico.
     * Laravel asume que en la tabla 'invoice_lines' existe una columna llamada 'book_id'.
     * Uso en Blade: {{ $line->book->title }} (Nos trae el título del libro asociado a esta línea).
     */
    public function book() {
        return $this->belongsTo(Book::class);
    }

    // --- MÉTODOS DE CÁLCULO ---

    /**
     * Calcula el precio unitario del libro sumándole su propio impuesto (IVA).
     */
    public function getPriceWithTaxAttribute(): float
    {
        // Ej: 20.00 * (1 + 0.21) = 24.20
        return $this->price * (1 + $this->tax_rate);
    }

    // Total de la línea SIN IVA (Precio x Cantidad)
    public function getLineTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    // Total de la línea CON IVA
    public function getLineTotalWithTaxAttribute(): float
    {
        return $this->getPriceWithTaxAttribute() * $this->quantity;
    }
}
