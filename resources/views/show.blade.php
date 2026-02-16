<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle: {{ $book->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
                    colors: { brand: { dark: '#1e293b', gold: '#b45309', cream: '#fdfbf7' } }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-cream text-slate-800 antialiased min-h-screen flex flex-col">

<nav class="bg-brand-dark text-white py-4 shadow-lg">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a class="text-2xl font-serif font-bold tracking-wide flex items-center gap-2 hover:text-brand-gold transition-colors" href="{{ route('home') }}">
                <span class="text-3xl">📚</span> Mi Librería
            </a>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('cart.index') }}" class="text-sm hover:text-brand-gold transition-colors">🛒 Ver Carrito</a>

            @auth
                <span class="text-sm text-gray-300">Hola, {{ Auth::user()->username }}</span>
            @else
                <a href="{{ route('login.form') }}" class="text-sm font-medium hover:text-brand-gold transition-colors">Iniciar Sesión</a>
                <a href="{{ route('register.form') }}" class="px-5 py-2 bg-brand-gold text-white rounded-full font-medium hover:bg-amber-700 transition-all shadow-md transform hover:-translate-y-0.5">
                    Registrarse
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 py-12 flex-grow">

    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-500 hover:text-brand-gold transition-colors font-medium">
            <span class="mr-2">←</span> Volver al catálogo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[500px]">

        <div class="md:w-5/12 relative h-96 md:h-auto bg-gray-100">
            <img src="{{ $book->image }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $book->title }}">
        </div>

        <div class="md:w-7/12 p-8 md:p-12 flex flex-col justify-center">

            <div class="mb-6">
                <p class="text-brand-gold font-bold text-sm uppercase tracking-widest mb-2">
                    {{ $book->author }}
                </p>
                <h1 class="font-serif text-4xl md:text-5xl text-brand-dark font-bold leading-tight mb-4">
                    {{ $book->title }}
                </h1>

                <div class="flex items-baseline gap-3 border-b border-gray-100 pb-6">
                    <span class="text-4xl font-serif font-bold text-slate-900">{{ number_format($book->pvp, 2) }}€</span>
                    <span class="text-gray-400 text-sm font-light">+ IVA</span>
                </div>
            </div>

            <div class="prose prose-slate text-gray-600 mb-8 leading-relaxed">
                {{ $book->description }}
            </div>

            <div class="grid grid-cols-2 gap-4 bg-brand-cream rounded-xl p-6 mb-8 border border-gray-100">
                <div>
                    <span class="block text-xs text-gray-400 uppercase font-bold mb-1">Editorial</span>
                    <span class="font-serif text-brand-dark">{{ $book->editorial }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase font-bold mb-1">Stock</span>
                    @if($book->stock > 0)
                        <span class="text-green-600 font-bold">Disponible ({{ $book->stock }})</span>
                    @else
                        <span class="text-red-500 font-bold">Agotado</span>
                    @endif
                </div>
            </div>

            <div class="mt-auto">
                {{-- Usamos la ruta cart.add para añadir --}}
                <a href="{{ route('cart.add', $book->id) }}"
                   class="block w-full bg-brand-dark text-white text-center py-4 rounded-xl font-bold hover:bg-slate-800 transition-all shadow-lg hover:-translate-y-1">
                    Añadir al Carrito
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
