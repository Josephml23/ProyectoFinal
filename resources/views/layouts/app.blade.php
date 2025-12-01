<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vagos School') }}</title>

        <!-- Bootstrap 5.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS Global Custom (Definición de paleta de colores y estilos de modal) -->
        <style>
            :root {
                --color-bg-dark: #17252A; /* Fondo de la Navbar y Modal */
                --color-primary: #2B7A78; /* Teal Oscuro (Botones, Acentos) */
                --color-secondary: #3AAFA9; /* Turquesa Claro */
                --color-white: #FEFFFF; /* Texto Claro */
                --color-card-bg: #DEF2F1; /* Fondo de tarjetas */
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
            
            /* ESTILOS DEL MODAL PERSONALIZADO */
            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.7);
            }
            
            .custom-modal-dialog {
                max-width: 450px;
            }

            .custom-modal-content {
                background-color: var(--color-bg-dark);
                color: var(--color-white);
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
                border: none;
                padding: 1rem;
            }
            
            .custom-modal-title {
                color: var(--color-secondary);
                font-weight: 600;
                font-size: 1.25rem;
            }
            
            .custom-modal-body {
                font-size: 1.1rem;
                padding-top: 1.5rem;
                padding-bottom: 2rem;
            }

            /* Estilos de botones */
            .custom-btn-accept {
                background-color: var(--color-secondary);
                border-color: var(--color-secondary);
                color: var(--color-bg-dark);
                font-weight: bold;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
            }
            .custom-btn-accept.btn-danger-action {
                background-color: var(--color-danger);
                border-color: var(--color-danger);
                color: var(--color-white);
            }
            .custom-btn-accept.btn-danger-action:hover {
                background-color: #A31C1C;
                border-color: #A31C1C;
                color: var(--color-white);
            }
            .custom-btn-accept:hover {
                background-color: #55C4BD;
                border-color: #55C4BD;
                color: var(--color-bg-dark);
            }

            .custom-btn-cancel {
                background-color: transparent;
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: var(--color-white);
                font-weight: 600;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
            }
            .custom-btn-cancel:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: var(--color-white);
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
        
        <!-- MODAL DE CONFIRMACIÓN GENÉRICO -->
        <div class="modal fade" id="genericConfirmModal" tabindex="-1" aria-labelledby="genericConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom-modal-dialog">
                <div class="modal-content custom-modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title custom-modal-title" id="genericConfirmModalLabel">Confirmación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-modal-body text-center">
                        <p id="modal-confirm-message">¿Estás seguro de que quieres realizar esta acción?</p>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                        <button type="button" class="btn custom-btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn custom-btn-accept" id="modal-btn-generic-accept">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CÓDIGO JavaScript CORREGIDO: Se quitó la 'x' de 'xintegrity' para que Bootstrap se cargue correctamente -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const genericModalElement = document.getElementById('genericConfirmModal');
                // IMPORTANTE: Instanciar el modal después de que el DOM esté cargado
                const genericModal = new bootstrap.Modal(genericModalElement); 
                const acceptBtn = document.getElementById('modal-btn-generic-accept');
                const messageParagraph = document.getElementById('modal-confirm-message');
                let formToSubmit = null;
                
                // 1. Manejo del Cierre de Sesión
                document.querySelectorAll('.logout-trigger').forEach(trigger => {
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        const logoutForm = document.getElementById('logout-form');
                        if (logoutForm) {
                            messageParagraph.textContent = '¿Estás seguro de que quieres cerrar la sesión?';
                            acceptBtn.classList.remove('btn-danger-action');
                            formToSubmit = logoutForm;
                            genericModal.show();
                        }
                    });
                });

                // 2. Manejo de Acciones de Eliminación (Ej. Eliminar a Joseph)
                document.querySelectorAll('.delete-trigger').forEach(trigger => {
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        const deleteFormId = trigger.getAttribute('data-form-id');
                        const deleteMessage = trigger.getAttribute('data-message') || '¿Estás seguro de que quieres eliminar este registro? Esta acción no se puede deshacer.';
                        
                        const deleteForm = document.getElementById(deleteFormId);
                        if (deleteForm) {
                            messageParagraph.textContent = deleteMessage;
                            acceptBtn.classList.add('btn-danger-action');
                            formToSubmit = deleteForm;
                            genericModal.show();
                        }
                    });
                });

                // 3. Listener para el botón "Aceptar" del modal genérico
                acceptBtn.addEventListener('click', function () {
                    if (formToSubmit) {
                        genericModal.hide();
                        formToSubmit.submit();
                    }
                });
            });
        </script>
    </body>
</html>