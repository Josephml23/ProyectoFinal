<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }} - Perfil</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
/* Definimos la paleta de colores usada en el dashboard del profesor */
:root {
--color-primary: #2B7A78; /* Verde Oscuro/Teal principal */
--color-secondary: #3AAFA9;
--color-card-bg: #DEF2F1; /* Fondo de las tarjetas claras (como en la imagen) */
--color-body-bg: #F3F4F6; /* Fondo de la página (gris muy claro) */
--color-dark-nav: #1F2937; /* Color oscuro para la barra de navegación */
}
.profile-card-bg {
background-color: var(--color-card-bg) !important;
/* Borde izquierdo color primario para el estilo de tarjeta de la imagen */
border-left: 4px solid var(--color-primary);
}

/* 🚀 SOLUCIÓN CLAVE: Selector agresivo para hacer visible el texto en las tarjetas */
/* Forzamos el color del texto a gris oscuro para que se vea sobre el fondo verde claro */
.profile-card-bg,
.profile-card-bg * { /* Selecciona el contenedor y todos sus hijos */
    color: #1F2937 !important; /* Usamos el color de la navegación (gris oscuro) para el texto */
}

/* ESTILOS PARA LOS CAMPOS DE TEXTO (INPUTS) - Mantener fondo blanco y texto negro */
.profile-card-bg input[type="text"],
.profile-card-bg input[type="email"],
.profile-card-bg input[type="password"] {
    background-color: white !important; /* Fondo blanco */
    color: #000000 !important; /* Letras negras para lo que escribe el usuario */
    border: 1px solid #D1D5DB; 
}
/* Estilo para los placeholders (texto que sale antes de escribir) */
.profile-card-bg input::placeholder {
    color: #4B5563 !important; /* Gris oscuro para placeholders */
}

/* Aseguramos que el título principal (fuera de las tarjetas) también sea oscuro */
.text-gray-800 {
    color: #1F2937 !important;
}

/* Si el botón SAVE sigue sin verse, forzamos su color */
.profile-card-bg button {
    color: #1F2937 !important; /* Texto del botón oscuro */
}

</style>

</head>
<body class="font-sans antialiased" style="background-color: var(--color-body-bg);">

<header class="w-full shadow border-b border-gray-700" style="background-color: var(--color-dark-nav);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <div class="flex items-center space-x-4">
            <a href="{{ url('/') }}" class="text-xl font-extrabold tracking-tight text-teal-400 hover:text-teal-300">
                Vagos School
            </a>
            <a href="{{ route('dashboard') }}" class="text-gray-200 text-base font-medium hover:text-white transition duration-150">
                Panel Principal
            </a>
        </div>

        <div class="text-gray-200 text-base font-light">
            @auth
                {{ Auth::user()->name }}
            @endauth
        </div>
    </div>
</header>
<div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">

<div class="py-6 w-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
                <h2 class="text-3xl font-extrabold text-gray-800 mb-6">{{ __('Perfil de Usuario') }}</h2>

                <div class="p-4 sm:p-8 shadow sm:rounded-lg profile-card-bg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

                <div class="p-4 sm:p-8 shadow sm:rounded-lg profile-card-bg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

                <div class="p-4 sm:p-8 shadow sm:rounded-lg profile-card-bg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>


</div>

</body>
</html>