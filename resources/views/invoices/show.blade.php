<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10 min-h-screen">
<div class="max-w-4xl mx-auto bg-white p-10 rounded-lg shadow-xl">

    <div class="flex justify-between border-b pb-8 mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">INVOICE</h1>
            <span class="text-gray-500">#{{ $invoice->invoice_number }}</span>
            <p class="text-sm text-gray-500 mt-2">Fecha: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="text-right">
            <h2 class="font-bold text-xl text-gray-700">{{ App\Models\Invoice::LIBRARY_NAME }}</h2>
            <p class="text-gray-500">{{ App\Models\Invoice::LIBRARY_ADDRESS }}</p>
            <p class="text-gray-500">CIF: {{ App\Models\Invoice::LIBRARY_CIF }}</p>
            <p class="text-gray-500">{{ App\Models\Invoice::LIBRARY_PHONE }}</p>
        </div>
    </div>

    <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-100">
        <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider mb-2">Facturado a:</h3>
        <p class="text-lg font-bold text-gray-800">{{ $invoice->client_name }} {{ $invoice->client_lastname }}</p>
        <p class="text-gray-600">DNI: {{ $invoice->client_dni }}</p>
        <p class="text-gray-600">{{ $invoice->client_address }}</p>
    </div>

    <table class="w-full mb-8">
        <thead>
        <tr class="text-left border-b-2 border-gray-200">
            <th class="py-3 text-gray-600">Descripción</th>
            <th class="py-3 text-center text-gray-600">Cant.</th>
            <th class="py-3 text-right text-gray-600">Precio Unit. (Sin IVA)</th>
            <th class="py-3 text-right text-gray-600">IVA</th>
            <th class="py-3 text-right text-gray-600">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->lines as $line)
            <tr class="border-b border-gray-100">
                <td class="py-3 font-medium text-gray-700">{{ $line->book->title }}</td>
                <td class="py-3 text-center">{{ $line->quantity }}</td>
                <td class="py-3 text-right">{{ number_format($line->price, 2) }} €</td>
                <td class="py-3 text-right">{{ $line->tax_rate * 100 }}%</td>
                <td class="py-3 text-right font-bold">{{ number_format($line->line_total_with_tax, 2) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="flex justify-end mb-12">
        <div class="w-1/2">
            <div class="flex justify-between py-2 text-gray-600">
                <span>Base Imponible</span>
                <span>{{ number_format($invoice->total_base, 2) }} €</span>
            </div>
            <div class="flex justify-between py-2 text-gray-600 border-b border-gray-200">
                <span>Impuestos Total</span>
                <span>{{ number_format($invoice->total_tax, 2) }} €</span>
            </div>
            <div class="flex justify-between pt-4">
                <span class="text-2xl font-bold text-gray-800">TOTAL PAGADO</span>
                <span class="text-2xl font-bold text-blue-600">{{ number_format($invoice->total, 2) }} €</span>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-4 print:hidden">
        <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
            Seguir Comprando
        </a>
        <a href="{{ route('invoices.index') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
            Ver todas mis compras
        </a>
    </div>
</div>
</body>
</html>
