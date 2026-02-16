<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [BookController::class, 'index'])->name('home');
// Ruta para ver el detalle de un libro específico
Route::get('/libro/{book}', [BookController::class, 'show'])->name('book.show');

// --- RUTAS PARA INVITADOS (GUEST) ---
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Mostrar formularios
    Route::get('/register', 'showRegister')->name('register.form');
    Route::get('/login', 'showLogin')->name('login.form');

    // Procesar datos
    Route::post('/register', 'register')->name('register.attempt');
    Route::post('/login', 'login')->name('login.attempt');
});

// --- RUTAS PARA USUARIOS AUTENTICADOS (AUTH) ---
Route::middleware('auth')->group(function () {

    // Cerrar sesión (necesita estar logueado para salir)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Aquí pondremos más adelante la ruta de "Mis Compras"
    // Route::get('/mis-compras', ...);
});

// --- RUTAS DEL CARRITO ---
Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/add/{id}', [CartController::class, 'add'])->name('add');          // Solo para la tienda
    Route::get('/increment/{id}', [CartController::class, 'increment'])->name('increment'); // Solo para el botón +
    Route::get('/decrease/{id}', [CartController::class, 'decrease'])->name('decrease');
    Route::get('/delete/{id}', [CartController::class, 'delete'])->name('delete');
    Route::get('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::middleware('auth')->get('/comprar', function () {
    return "Compra realizada"; // Aquí iría tu lógica de compra
})->name('purchase');

Route::middleware('auth')->group(function () {
    // Procesar la compra (Acción del botón Comprar)
    Route::get('/checkout', [InvoiceController::class, 'store'])->name('checkout.process');

    // Pantalla de carga
    Route::get('/invoice/loading/{id}', [InvoiceController::class, 'loader'])->name('invoice.loader');

    // Ver una factura
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');

    // Ver todas mis facturas
    Route::get('/mis-compras', [InvoiceController::class, 'index'])->name('invoices.index');
});
