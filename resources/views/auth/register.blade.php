<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Mi Librería</title>

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
<body class="bg-brand-cream text-slate-800 antialiased min-h-screen flex items-center justify-center py-12">

<div class="w-full max-w-2xl px-4">

    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-block text-4xl mb-2 hover:scale-110 transition-transform duration-300">
            📚
        </a>
        <h2 class="text-3xl font-serif font-bold text-brand-dark">Únete a nuestra comunidad</h2>
        <p class="text-brand-gray text-sm mt-2">Crea tu cuenta para gestionar tus compras</p>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-gray-100 relative overflow-hidden">

        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-brand-gold rounded-full opacity-5 blur-3xl"></div>

        <form action="{{ route('register.attempt') }}" method="POST" class="space-y-6 relative z-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">DNI</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="12345678A">
                    @error('dni') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="Lector_99">
                    @error('username') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="Tu nombre">
                    @error('name') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Apellidos</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="Tus apellidos">
                    @error('lastname') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Dirección Completa</label>
                <input type="text" name="addrs" value="{{ old('addrs') }}" required
                       class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                       placeholder="Calle Ejemplo 123, Ciudad...">
                @error('addrs') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                       placeholder="ejemplo@correo.com">
                @error('email') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2 ml-1">Repetir Contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold transition-all"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-brand-dark text-white py-3.5 rounded-xl font-medium hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 mt-4">
                Crear Cuenta
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                ¿Ya tienes cuenta?
                <a href="{{ route('login.form') }}" class="font-bold text-brand-gold hover:text-amber-700 hover:underline transition-colors">
                    Inicia sesión aquí
                </a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
