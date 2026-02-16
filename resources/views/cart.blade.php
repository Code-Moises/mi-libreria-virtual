<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
                    colors: { brand: { dark: '#1e293b', gold: '#b45309', cream: '#fdfbf7', gray: '#64748b' } }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-cream text-slate-800 antialiased min-h-screen flex flex-col">

<nav class="bg-brand-dark text-white py-4 shadow-lg">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <a class="text-2xl font-serif font-bold tracking-wide flex items-center gap-2 hover:text-brand-gold transition-colors" href="{{ route('home') }}">
            <span class="text-3xl">📚</span> Mi Librería
        </a>
        <a href="{{ route('home') }}" class="text-sm hover:text-brand-gold transition-colors">← Seguir comprando</a>
        @auth
            <a href="{{ route('invoices.index') }}" class="text-sm hover:text-yellow-500 mr-4">📜 Mis Compras</a>
        @endauth
    </div>
</nav>

<div class="container mx-auto px-4 py-12 flex-grow">

    <h1 class="text-4xl font-serif font-bold text-brand-dark mb-8">Tu Carrito de Compras</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Atención</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if($cartCount > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-2/3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 space-y-6">

                        {{-- BUCLE SOBRE LOS LIBROS AGRUPADOS --}}
                        @foreach($booksGrouped as $item)
                            @php
                                $book = $item['book']; // El objeto libro
                                $qty  = $item['qty'];  // La cantidad calculada
                            @endphp

                            <div class="flex flex-col sm:flex-row items-center gap-6 border-b border-gray-100 pb-6 last:border-0 last:pb-0">

                                <div class="w-24 h-32 flex-shrink-0">
                                    <img src="{{ $book->image }}" class="w-full h-full object-cover rounded-md shadow-sm" alt="{{ $book->title }}">
                                </div>

                                <div class="flex-grow text-center sm:text-left">
                                    <h3 class="font-serif text-xl font-bold text-brand-dark">{{ $book->title }}</h3>
                                    <p class="text-sm text-brand-gray mb-1">{{ $book->author }}</p>
                                    <p class="text-brand-gold font-bold">{{ number_format($book->pvp, 2) }} €</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">

                                        {{-- BOTÓN MENOS --}}
                                        <a href="{{ route('cart.decrease', $book->id) }}"
                                           class="px-3 py-1 bg-gray-50 text-gray-600 font-bold transition-colors
                                             {{ $qty <= 1 ? 'opacity-50 pointer-events-none cursor-not-allowed' : 'hover:bg-gray-200' }}">
                                            −
                                        </a>

                                        <span class="px-3 py-1 font-medium text-brand-dark bg-white border-x border-gray-300 min-w-[40px] text-center">
                                              {{ $qty }}
                                        </span>

                                        {{-- BOTÓN MÁS: OJO AQUÍ, cambiamos la ruta a 'cart.increment' --}}
                                        <a href="{{ route('cart.increment', $book->id) }}"
                                           class="px-3 py-1 bg-gray-50 text-gray-600 font-bold transition-colors
                                             {{ $qty >= $book->stock ? 'opacity-50 pointer-events-none cursor-not-allowed' : 'hover:bg-gray-200' }}">
                                            +
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-6">
                                    <p class="font-bold text-lg text-brand-dark w-24 text-right">
                                        {{ number_format($book->pvp * $qty, 2) }} €
                                    </p>

                                    <a href="{{ route('cart.delete', $book->id) }}"
                                       class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50"
                                       title="Eliminar libro">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-end">
                        <a href="{{ route('cart.clear') }}" class="text-sm text-red-500 hover:text-red-700 font-medium underline">
                            Vaciar todo el carrito
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sticky top-24">
                    <h2 class="font-serif text-2xl font-bold text-brand-dark mb-6">Resumen</h2>

                    <div class="flex justify-between mb-4 text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ number_format($total, 2) }} €</span>
                    </div>
                    <div class="flex justify-between mb-6 text-gray-600">
                        <span>Impuestos (Estimados)</span>
                        <span>Incluidos</span>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-8 flex justify-between items-center">
                        <span class="text-lg font-bold text-brand-dark">Total a Pagar</span>
                        <span class="text-3xl font-serif font-bold text-brand-gold">{{ number_format($total, 2) }} €</span>
                    </div>

                    @auth
                        <a href="{{ route('checkout.process') }}" class="block w-full bg-brand-dark text-white text-center py-4 rounded-xl font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">Comprar Ahora</a>
                    @else
                        <a href="{{ route('login.form') }}" class="block w-full bg-brand-gold text-white text-center py-4 rounded-xl font-bold hover:bg-amber-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                            Inicia Sesión para Comprar
                        </a>
                        <p class="text-xs text-center text-gray-500 mt-2">Necesitas una cuenta para finalizar el pedido</p>
                    @endauth
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-3xl shadow-lg border border-gray-100">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-3xl font-serif font-bold text-brand-dark mb-2">Tu carrito está vacío</h2>
            <p class="text-gray-500 mb-8">Parece que aún no has añadido ningún libro interesante.</p>
            <a href="{{ route('home') }}" class="inline-block bg-brand-gold text-white px-8 py-3 rounded-xl font-bold hover:bg-amber-700 transition-colors shadow-md">
                Explorar Catálogo
            </a>
        </div>
    @endif
</div>

</body>
</html>
