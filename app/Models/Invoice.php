<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    // --- CONSTANTES DE LA LIBRERÍA (Como pediste) ---
    public const LIBRARY_NAME = "Mi Librería Virtual";
    public const LIBRARY_CIF = "B-12345678";
    public const LIBRARY_ADDRESS = "Calle del Conocimiento, 42";
    public const LIBRARY_PHONE = "+34 91 123 45 67";

    // Relaciones
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lines() {
        return $this->hasMany(InvoiceLine::class);
    }
}
