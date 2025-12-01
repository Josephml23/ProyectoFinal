<x-guest-layout>

    <style>

        :root {
            --primary-color: #3AAFA9;
            --secondary-color: #2B7A78;
            --accent-color: #FEFFFF;
            --bg-principal: #0f1a27;
            --shadow-dark: rgba(0, 0, 0, 0.5);
        }

        /* CUADRO PRINCIPAL DEL LOGIN */
        .login-container {
            background-color: var(--bg-principal);
            border-radius: 1.5rem;
            padding: 3.5rem 2.5rem;
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 15px 40px var(--shadow-dark);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* 🔥 SUBIMOS EL TÍTULO */
        .header-section {
            margin-top: -2.8rem; /* ajustar si deseas más */
            margin-bottom: 2rem;
        }

        .school-name {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--primary-color);
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            margin-bottom: .8rem;
        }

        .login-subtitle {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--accent-color);
            margin-bottom: 2.5rem;
        }

        /* FORMULARIO */
        .form-group-spaced {
            margin-bottom: 1.7rem;
            text-align: left;
        }

        .input-label {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: .4rem;
            font-size: .9rem;
            font-weight: 600;
        }

        .text-input {
            width: 100%;
            padding: .85rem 1rem;
            border-radius: .7rem;
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--accent-color);
            font-size: 1rem;
        }

        .remember-forgot-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            font-size: .85rem;
        }

        .forgot-password-link {
            color: var(--primary-color);
            font-weight: 600;
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: var(--bg-principal);
            border-radius: .7rem;
            border: none;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
        }

        .login-button:hover {
            background-color: #55C4BD;
        }

    </style>

    <div class="login-container">

        <!-- 🔥 TÍTULO MÁS ARRIBA -->
        <div class="header-section">
            <h2 class="school-name">Vagos School</h2>
            <h3 class="login-subtitle">Inicio de Sesión</h3>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group-spaced">
                <label for="email" class="input-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="text-input">
                <x-input-error :messages="$errors->get('email')" class="mt-2 error-message" />
            </div>

            <div class="form-group-spaced">
                <label for="password" class="input-label">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="text-input">
                <x-input-error :messages="$errors->get('password')" class="mt-2 error-message" />
            </div>

            <div class="remember-forgot-section">

                <!-- 🔥 SOLO CAMBIADO A BLANCO -->
                <label style="color: white;">
                    <input type="checkbox" name="remember">
                    <span style="color: white;">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot-password-link" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="login-button">
                INICIAR SESIÓN
            </button>
        </form>
    </div>
</x-guest-layout>
