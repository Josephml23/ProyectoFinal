<x-guest-layout>
    <style>
        /* ====================================
           VARIABLES Y ESTILO BASE
           ==================================== */
        :root {
            --primary-color: #3AAFA9; /* Verde azulado para elementos principales */
            --secondary-color: #2B7A78; /* Verde azulado más oscuro para fondos */
            --accent-color: #FEFFFF; /* Blanco para texto y elementos claros */
            --bg-principal: #1f2937; /* Fondo oscuro del contenedor (la tarjeta) */
            --bg-externo: #3AAFA9; /* Fondo externo (el color verde/azul de la pagina) */
            --border-color: #4A5568; /* Color de borde para inputs */
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition-speed: 0.3s ease;
        }

        /* La CLAVE para eliminar el scroll y centrar la página */
        html, body {
            height: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: var(--bg-externo); 
            font-family: 'Figtree', sans-serif; /* Usamos Figtree que ya está importada */
            color: var(--accent-color);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* <--- Esto fuerza la eliminación de la barra de scroll */
        }
        
        /* Asegurar que el contenedor de Laravel/Breeze no agregue altura extra */
        .custom-guest-layout-wrapper {
            /* Esta clase debe existir en guest.blade.php para envolver el contenido */
            min-height: 100vh !important; 
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 0;
            margin: 0;
        }
        .custom-slot-container {
             /* Esta clase debe existir en guest.blade.php para envolver el $slot */
            background-color: transparent !important;
            box-shadow: none !important;
        }


        /* Estilo para el contenedor del formulario */
        .login-container {
            background-color: var(--bg-principal); 
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); 
            width: 100%;
            max-width: 380px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin: 0; 
            flex-shrink: 0;
        }

        /* ====================================
           ENCABEZADO (Logo y Título)
           ==================================== */
        .header-section {
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin-bottom: 0.5rem; 
        }

        .school-icon {
            width: 35px;
            height: 35px;
            margin-right: 0.5rem;
            fill: var(--primary-color); 
        }

        .school-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color); 
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            line-height: 1.1;
        }

        .login-subtitle {
            font-size: 1.1rem;
            margin-top: 0;
            margin-bottom: 2rem;
            color: var(--accent-color);
            font-weight: 400;
            text-shadow: none;
        }

        /* ====================================
           FORMULARIO
           ==================================== */

        .input-label {
            display: block;
            text-align: left;
            margin-bottom: 0.5rem;
            font-size: 0.85rem; 
            color: rgba(255, 255, 255, 0.7); 
            font-weight: 500;
        }

        .text-input {
            width: 100%;
            padding: 0.75rem 1rem; 
            margin-bottom: 1.5rem;
            border-radius: 0.5rem; 
            border: none; 
            background-color: rgba(255, 255, 255, 0.1); 
            color: var(--accent-color);
            font-size: 1rem;
            transition: background-color var(--transition-speed);
        }
        .text-input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 2px var(--primary-color);
        }
        .text-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Contenedor del checkbox y "Olvidaste tu contraseña" */
        .remember-forgot-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 0.8rem; 
        }

        .form-check-input {
            margin-right: 0.5rem;
            accent-color: var(--primary-color);
        }

        .forgot-password-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: color var(--transition-speed);
        }
        .forgot-password-link:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Estilo del botón de Login */
        .login-button {
            width: 100%;
            padding: 0.9rem 1.5rem;
            border-radius: 0.5rem; 
            background-color: var(--primary-color);
            color: var(--accent-color);
            font-size: 1rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background-color var(--transition-speed);
        }
        .login-button:hover {
            background-color: #318a85;
        }

        /* Estilo para los mensajes de error de validación */
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            text-align: left;
        }
    </style>

    <div class="login-container">
        
        <div class="header-section">
            <svg class="school-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M168.3 499.7C116.4 489.1 84.9 458.7 70.7 410.6c-1.8-6.1-4.7-16.1 4.9-24.1c11.2-9.3 28.5-16 43.1-15.6c13.7 .3 28.3 5.4 39.8 11.7c9.3 5 19.1 11.2 27.2 16.5c3.5 2.2 4 4.3 1.9 8.4c-4.4 8.2-11.9 22.8-11.9 22.8c-1.3 2.6-3.7 2.3-5.2 .5c-15.7-18.7-25.7-36.9-42.5-49.8c-26.6-20.5-42.3-30.8-43.2-31.5c-7.9-5.9-12.6-11.8-12.6-24.8V232.7c0-26.3 3.6-56 31.5-84.5c23.1-23.8 53.6-43.5 73.6-49.1c7.7-2.1 12.3-2.9 14.1-2.9c.7 0 3-1.8 3-4.5c0-1.2-.5-2.2-1.1-2.9c-4.1-4.8-12.8-8.7-27.2-12.7c-26-7.3-43.1-12-58.4-18c-3.1-1.2-5.4-2.8-6.7-4.1c-1.3-1.2-1.4-1.9-.3-3.6c2.8-4.4 9-9.1 20-13.8c12.2-5.3 25.1-9.9 37.8-13.7c18.5-5.5 30.2-10 39.4-14.2c2.2-1 .5-2.7-1.1-4.5c-2.3-2.5-5.2-4.9-8.4-7.2c-15.2-11-24.6-19.1-33.8-29.2c-5.9-6.4-9.3-14.7-9.3-22.3V48c0-13.3 10.7-24 24-24s24 10.7 24 24v52.6c11.9 3.5 24.1 6.3 36.3 8.4c17.5 3 34.8 5.4 51 6.8c.2 0 .4 0 .6 0c1.7 .1 3.5 .1 5.3 .1c24.6 0 46.1-2.8 58.7-4.3c15.6-1.9 26.5-2 32.7-2c13.3 0 24 10.7 24 24v49.1c0 8.4-4.8 17.5-12.1 25.4c-9 9.8-19.1 17.8-30.6 25.2c-13.2 8.5-29.8 17.2-40.4 20.8c-10.4 3.5-16.7 5.2-19.9 5.2c-6.1 0-11.7-.5-17.7-1.4c-18.4-2.7-35.3-5.2-46.7-6.2c-1.3-.1-1.9-.2-2.1-.2c-13.3 0-24 10.7-24 24V400c0 13.3 10.7 24 24 24h32.6c1.1 0 2.2 0 3.3 0s2.2 0 3.3 0c.9 0 1.8 0 2.7 0c13.4 0 25.5 1.7 34.6 4.9c4.3 1.6 8.4 3.4 12.3 5.4c13.5 7.1 27 15.1 36.9 24.8c10.3 10.1 19.6 22 26.2 34.7c7.4 14.2 11.2 27.6 11.8 38.6c.7 12.9-10.2 23.4-23.2 23.4c-4.4 0-8.6-.7-12.7-2c-12.1-3.8-25.2-9-38.3-16.1c-12.1-6.6-23.7-13.3-33.8-19.5c-3.1-1.9-4.8-2.6-7.3-2.6c-4.9 0-10.4 2.7-15 5.5c-15.6 9.3-27.1 16.3-39.7 18.6c-1.6 .3-3.2 .5-4.8 .5H168.3z"/>
            </svg>
            <h2 class="school-name">Vagos School</h2>
        </div>
        
        <h3 class="login-subtitle">Inicio de Sesión</h3>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mt-4">
                <x-input-label for="email" value="Email" class="input-label" />
                <x-text-input id="email" class="w-full text-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu.email@ejemplo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 error-message" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" value="Contraseña" class="input-label" />
                <x-text-input id="password" class="w-full text-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 error-message" />
            </div>

            <div class="remember-forgot-section">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <span class="ml-2" style="color: rgba(255, 255, 255, 0.7); font-weight: 500;">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot-password-link" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="login-button">
                    INICIAR SESIÓN
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>