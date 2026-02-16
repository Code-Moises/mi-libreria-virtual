<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Librería Premium</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#1e293b',  /* Gris azulado muy oscuro */
                            gold: '#b45309',  /* Dorado/Bronce elegante */
                            cream: '#fdfbf7', /* Fondo tipo papel */
                            gray: '#64748b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Un poco de CSS extra para suavizar el scroll */
        html { scroll-behavior: smooth; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-brand-cream text-slate-800 antialiased">

<nav class="bg-brand-dark text-white sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <a class="text-3xl font-serif font-bold tracking-wide flex items-center gap-2 hover:text-brand-gold transition-colors duration-300" href="{{ route('home') }}">
                <span class="text-4xl">📚</span> Mi Librería
            </a>

            <div class="mt-4 md:mt-0 flex items-center space-x-6">
                <a href="{{ route('cart.index') }}" class="text-sm hover:text-brand-gold transition-colors">🛒 Ver Carrito</a>
                @guest
                    <a href="{{ route('login.form') }}" class="text-sm font-medium hover:text-brand-gold transition-colors">Iniciar Sesión</a>
                    <a href="{{ route('register.form') }}" class="px-5 py-2 bg-brand-gold text-white rounded-full font-medium hover:bg-amber-700 transition-all shadow-md transform hover:-translate-y-0.5">
                        Registrarse
                    </a>
                @endguest

                @auth
                    <div class="flex items-center gap-4">
                        <span class="font-serif italic text-brand-gold">Hola, {{ Auth::user()->username }}</span>
                        <a href="{{ route('invoices.index') }}" class="text-sm hover:text-yellow-500 mr-4">📜 Mis Compras</a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs border border-slate-600 px-3 py-1 rounded hover:bg-red-500 hover:border-red-500 hover:text-white transition-all">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
<div class="container mx-auto px-6 py-6">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md relative" role="alert">
            <strong class="font-bold">¡Genial!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md relative" role="alert">
            <strong class="font-bold">¡Atención!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</div>

<div class="container mx-auto px-6 py-12">

    <div class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b border-gray-200 pb-4">
            <h2 class="text-4xl font-serif font-bold text-brand-dark">
                 Top Ventas
            </h2>
            <span class="text-sm text-brand-gray italic">Lo más leído esta semana</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($topBooks as $book)
                <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                    <div class="absolute top-2 right-2 bg-brand-gold text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">
                        {{ $book->sales_count }} vendidos
                    </div>

                    <div class="h-48 overflow-hidden bg-gray-100 relative">
                        <img src="{{ $book->image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $book->title }}">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                    </div>

                    <div class="p-4 text-center">
                        <h6 class="font-serif font-bold text-lg text-slate-900 truncate mb-1">{{ $book->title }}</h6>
                        <p class="text-xs text-brand-gray uppercase tracking-wider">{{ $book->author }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-xl mb-16 border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-brand-gold rounded-full opacity-10 blur-xl"></div>

        <form action="{{ route('home') }}" method="GET" class="relative z-10">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-center">

                <div class="w-full md:w-1/2 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-400 group-focus-within:text-brand-gold transition-colors">🔍</span>
                    </div>
                    <input type="text"
                           name="search"
                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-transparent focus:bg-white transition-all outline-none shadow-inner"
                           placeholder="Buscar por título o autor..."
                           value="{{ request('search') }}">
                </div>

                <div class="w-full md:w-1/4">
                    <select name="author" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold focus:border-transparent cursor-pointer appearance-none text-gray-700">
                        <option value="">-- Todos los autores --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author }}" {{ request('author') == $author ? 'selected' : '' }}>
                                {{ $author }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button class="flex-1 md:flex-none px-6 py-3 bg-brand-dark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-lg shadow-slate-300/50" type="submit">
                        Buscar
                    </button>

                    @if(request('search') || request('author'))
                        <a href="{{ route('home') }}" class="px-6 py-3 border border-gray-300 text-gray-600 rounded-xl font-medium hover:bg-gray-100 transition-colors">
                            Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="mb-8">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-3xl font-serif font-bold text-slate-900">Catálogo</h2>
            <span class="text-sm bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm text-gray-500">
                    Página {{ $books->currentPage() }} de {{ $books->lastPage() }}
                </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($books as $book)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex overflow-hidden border border-gray-100 group h-64">
                    <div class="w-1/3 relative overflow-hidden">
                        <img src="{{ $book->image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="...">
                    </div>

                    <div class="w-2/3 p-6 flex flex-col justify-between">
                        <div>
                            <h5 class="font-serif font-bold text-xl text-slate-800 leading-tight mb-1 line-clamp-2">{{ $book->title }}</h5>
                            <p class="text-sm text-brand-gold font-medium mb-3">{{ $book->author }}</p>

                            <div class="flex items-baseline gap-1 mb-2">
                                <h4 class="text-2xl font-bold text-slate-900">{{ number_format($book->pvp, 2) }}€</h4>
                                <span class="text-xs text-gray-400 font-light">+IVA</span>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('cart.add', $book->id) }}" class="flex-1 bg-brand-dark text-white text-sm py-2 rounded-lg hover:bg-slate-800 transition-colors shadow-md text-center">
                                + Carrito
                            </a>
                            <a href="{{ route('book.show', $book->id) }}" class="px-3 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-brand-gold transition-colors">
                                👁️
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            <div class="bg-white px-4 py-2 rounded-full shadow-md border border-gray-100">
                {{ $books->links() }}
            </div>
        </div>
    </div>

</div>

<footer class="bg-white border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
    <p>&copy; 2024 Mi Librería Premium. Todos los derechos reservados.</p>
</footer>

</body>
</html>
