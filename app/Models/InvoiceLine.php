<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $guarded = [];

    // Relación con el libro
    public function book() {
        return $this->belongsTo(Book::class);
    }

    // --- MÉTODOS DE CÁLCULO (Adaptados de tu DetailsBill) ---

    // Precio unitario con IVA
    public function getPriceWithTaxAttribute(): float
    {
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
