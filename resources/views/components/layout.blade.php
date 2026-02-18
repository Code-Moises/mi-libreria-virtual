<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Si no se pasa titulo, usa "Mi Librería" por defecto --}}
    <title>{{ $title ?? 'Mi Librería Premium' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

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
                            dark: '#1e293b',
                            gold: '#b45309',
                            cream: '#fdfbf7',
                            gray: '#64748b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        nav[role="navigation"] p.text-sm { display: none !important; }
        nav[role="navigation"] .sm\:justify-between { justify-content: center !important; }
    </style>
</head>
<body class="bg-brand-cream text-slate-800 antialiased min-h-screen flex flex-col">

<nav class="bg-brand-dark text-white sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <a class="text-3xl font-serif font-bold tracking-wide flex items-center gap-2 hover:text-brand-gold transition-colors" href="{{ route('home') }}">
                <span class="text-3xl">📚</span> Mi Librería
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
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md relative" role="alert">
            <strong class="font-bold">¡Genial!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md relative" role="alert">
            <strong class="font-bold">¡Atención!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</div>

{{-- CONTENIDO PRINCIPAL --}}
<main class="flex-grow">
    {{ $slot }}
</main>

<footer class="bg-white border-t border-gray-200 mt-12 py-8 text-center text-gray-500 text-sm">
    <p>&copy; 2026 La libreria más premium de todo internet. Quien quiera los derechos que los pida por bizum: Moisés.</p>
</footer>

</body>
</html>
