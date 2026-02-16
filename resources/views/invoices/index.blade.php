<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-fdfbf7 text-slate-800">

<nav class="bg-gray-900 text-white py-4 mb-8">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-xl">📚 Mi Librería</a>
        <a href="{{ route('home') }}" class="text-sm hover:text-yellow-500">← Volver al inicio</a>
    </div>
</nav>

<div class="container mx-auto px-4">
    <h1 class="text-3xl font-serif font-bold mb-6">Mis Compras</h1>

    @if($invoices->isEmpty())
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-500">Aún no has realizado ninguna compra.</p>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline mt-2 block">Ir a comprar libros</a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-4 text-left">Nº Factura</th>
                    <th class="p-4 text-left">Fecha</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $inv)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-bold">{{ $inv->invoice_number }}</td>
                        <td class="p-4 text-gray-600">{{ $inv->created_at->format('d/m/Y') }}</td>
                        <td class="p-4 text-right font-bold text-green-600">{{ number_format($inv->total, 2) }} €</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('invoice.show', $inv->id) }}" class="text-blue-600 hover:underline text-sm font-bold">Ver Factura</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</body>
</html>
