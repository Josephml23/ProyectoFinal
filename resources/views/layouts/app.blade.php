<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap 5.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS Global Custom (Definición de paleta de colores) -->
        <style>
            :root {
                --color-bg-dark: #17252A;
                --color-primary: #2B7A78; /* Teal Oscuro */
                --color-secondary: #3AAFA9; /* Turquesa Claro */
                --color-white: #FEFFFF;
                --color-card-bg: #DEF2F1; /* Blanco Hielo */
                --color-danger: #DC2626; /* Rojo */
                --color-text-light: #f3f4f6;
            }
            body { 
                font-family: 'Figtree', sans-serif; 
                background-color: var(--color-white);
                min-height: 100vh;
            }
            .header-transparent {
                background-color: transparent !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .header-transparent .container {
                padding: 0 !important;
            }
            .min-vh-100 {
                min-height: 100vh;
            }
            .text-bg-dark {
                color: var(--color-bg-dark) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-vh-100">
            
            @include('layouts.navigation')

            @isset($header)
                <header class="header-transparent">
                    <div class="container header-transparent">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="py-4">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Bootstrap JS Bundle (para menús desplegables) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>