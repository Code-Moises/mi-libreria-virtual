<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InvoiceController;

/*
| RUTAS PÚBLICAS
*/
Route::controller(BookController::class)->group(function () {
    // Página principal (Home)
    Route::get('/', 'index')->name('home');

    // Detalle de un libro.
    Route::get('/libro/{book}', 'show')->name('book.show');
});


/*
| RUTAS PARA NO LOGUEADOS (GUEST)
*/
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Vistas de los formularios (GET)
    Route::get('/register', 'showRegister')->name('register.form');
    Route::get('/login', 'showLogin')->name('login.form');

    // Procesamiento de los formularios (POST)
    Route::post('/register', 'register')->name('register.attempt');
    Route::post('/login', 'login')->name('login.attempt');
});


/*
| RUTAS DEL CARRITO DE COMPRAS
| Accesible para todos.
*/
Route::prefix('carrito')->name('cart.')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/add/{id}', 'add')->name('add');                   // Desde el catálogo o la página de detalle
    Route::get('/increment/{id}', 'increment')->name('increment'); // Botón +
    Route::get('/decrease/{id}', 'decrease')->name('decrease');    // Botón -
    Route::get('/delete/{id}', 'delete')->name('delete');          // Papelera
    Route::get('/clear', 'clear')->name('clear');                  // Vaciar todo
});


/*
| RUTAS PARA LOGUEADOS (AUTH)
| Si no estás logueado, Laravel te manda al login.
*/
Route::middleware('auth')->group(function () {

    // 1. Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Facturación y Compras
    Route::controller(InvoiceController::class)->group(function () {

        // Iniciar el proceso de pago (Checkout)
        Route::get('/checkout', 'store')->name('checkout.process');

        // Pantalla de espera interactiva (Loader)
        Route::get('/invoice/loading/{id}', 'loader')->name('invoice.loader');

        // Ver el detalle de una factura específica
        Route::get('/invoice/{id}', 'show')->name('invoice.show');

        // Historial completo de compras del usuario
        Route::get('/mis-compras', 'index')->name('invoices.index');
    });

});
