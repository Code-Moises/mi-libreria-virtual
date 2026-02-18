<x-layout title="Mi Librería">
    <div class="container mx-auto px-4 py-12 flex-grow">

        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-500 hover:text-brand-gold transition-colors font-medium">
                <span class="mr-2">←</span> Volver al catálogo
            </a>
        </div>

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
</x-layout>
