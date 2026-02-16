<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Relación con el usuario actual
            $table->string('invoice_number')->unique();  // Número de factura único

            // Snapshot del Cliente (Datos copiados del usuario al comprar)
            $table->string('client_dni');
            $table->string('client_name');
            $table->string('client_lastname');
            $table->string('client_address');

            $table->decimal('total_base', 10, 2); // Total sin impuestos
            $table->decimal('total_tax', 10, 2);  // Total impuestos
            $table->decimal('total', 10, 2);      // Total final a pagar

            $table->timestamps(); // created_at será la fecha de la factura
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
