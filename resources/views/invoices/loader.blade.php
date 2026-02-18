<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Compra...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="3;url={{ route('invoice.show', $invoice->id) }}" />
</head>
<body class="bg-gray-100 h-screen flex flex-col justify-center items-center">

<div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-blue-600 mb-6"></div>

<h2 class="text-3xl font-bold text-gray-800 mb-2">Generando Factura...</h2>
<p class="text-gray-500">Estamos preparando tu pedido. Por favor espera.</p>

<script>
    setTimeout(function(){
        window.location.href = "{{ route('invoice.show', $invoice->id) }}";
    }, 3000);
</script>
</body>
</html>
