<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    @livewireStyles
    <!-- Aquí puedes incluir Tailwind o Bootstrap si usas CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <main class="container mx-auto p-6">
        {{ $slot }} <!-- ¡Importante! Aquí se inyecta tu componente Livewire -->
    </main>

    @livewireScripts
</body>
</html>