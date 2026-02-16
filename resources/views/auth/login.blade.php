<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mi Librería</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

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
</head>
<body class="bg-brand-cream text-slate-800 antialiased min-h-screen flex items-center justify-center py-10">

<div class="w-full max-w-md px-4">

    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-block text-4xl mb-2 hover:scale-110 transition-transform duration-300">
            📚
        </a>
        <h2 class="text-3xl font-serif font-bold text-brand-dark">Bienvenido de nuevo</h2>
        <p class="text-brand-gray text-sm mt-2">Introduce tus credenciales para acceder</p>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-gray-100 relative overflow-hidden">

        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-brand-gold rounded-full opacity-5 blur-2xl"></div>

        @error('credentials')
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r text-sm text-red-700">
            <p class="font-bold">Error de acceso</p>
            <p>{{ $message }}</p>
        </div>
        @enderror

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-6 relative z-10">
            @csrf

            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Correo Electrónico</label>
                <input type="email"
                       name="email"
                       class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                       placeholder="ejemplo@correo.com"
                       value="{{ old('email') }}"
                       required autofocus>
                @error('email')
                <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Contraseña</label>
                <input type="password"
                       name="password"
                       class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                       placeholder="••••••••"
                       required>
            </div>

            <button type="submit" class="w-full bg-brand-dark text-white py-3.5 rounded-xl font-medium hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex justify-center items-center gap-2">
                <span>Iniciar Sesión</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>

        <div class="mt-8 text-center space-y-4">
            <p class="text-sm text-gray-600">
                ¿No tienes cuenta?
                <a href="{{ route('register.form') }}" class="font-bold text-brand-gold hover:text-amber-700 hover:underline transition-colors">
                    Regístrate gratis
                </a>
            </p>
            <div class="pt-4 border-t border-gray-100">
                <a href="{{ route('home') }}" class="inline-flex items-center text-xs text-gray-400 hover:text-brand-dark transition-colors">
                    <span class="mr-1">←</span> Volver a la tienda
                </a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
