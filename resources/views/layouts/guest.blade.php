<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vagos School') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    
    <style>
        :root {
            --primary-color: #3AAFA9; /* Verde azulado para elementos principales */
            --secondary-color: #2B7A78; /* Verde azulado más oscuro para fondos */
            --bg-principal: #1f2937; /* Fondo oscuro del contenedor (la tarjeta) */
        }
        
        body {
            /* Fondo degradado y centrado, eliminando el scroll */
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Elimina la barra de scroll */
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center h-100">
        {{ $slot }}
    </div>
</body>
</html>