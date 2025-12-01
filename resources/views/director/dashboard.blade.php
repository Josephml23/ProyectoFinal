<x-app-layout>
    
    <!-- CSS PERSONALIZADO --><style>
        /* --- DEFINICIÓN DE VARIABLES --- */
        :root {
            --color-primary: #2B7A78; /* Teal Oscuro (Acción Principal, Títulos) */
            --color-secondary: #3AAFA9; /* Turquesa Claro (Botones, Destacados) */
            --color-green: #10b981;
            --color-red: #DC2626; 
            --color-danger: var(--color-red); 
            --color-card-bg: #DEF2F1; /* Blanco Hielo */
            --color-white: #FEFFFF;
            --color-text-dark: #1f2937;
            --color-text-light: #6b7280;
            --color-bg-light: #f9fafb; /* Fondo de celdas */
            --color-border: #e5e7eb;
            --radius: 0.5rem;
        }

        /* --- LAYOUT GENERAL --- */
        .container {
            max-width: 90rem;
        }
        .card-body { padding: 1.5rem; }
        .card-title { color: var(--color-primary); }

        /* --- FORMULARIO Y SELECTS --- */
        .form-select, .form-control {
            border: 1px solid var(--color-border);
            transition: border-color 0.15s;
        }
        .form-select:focus, .form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 0.25rem rgba(43, 122, 120, 0.25); }
        .btn-primary { background-color: var(--color-primary); border-color: var(--color-primary); }
        .btn-primary:hover { background-color: #246a68; border-color: #246a68; }

        /* --- TABLA DE HORARIOS --- */
        .schedule-table {
            width: 100%;
            border-collapse: collapse; 
            font-size: 0.75rem;
            table-layout: fixed;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid var(--color-border);
        }
        .table-head-row { background-color: var(--color-primary); color: white; height: 2.5rem; text-align: center; }
        .time-cell-header { background-color: #f9fafb; width: 10%; }
        .time-cell { background-color: #f9fafb; width: 10%; font-weight: bold; color: var(--color-text-light); }
        .table-body-row { height: 3rem; }

        /* --- BLOQUES DE CLASE --- */
        .class-day-cell { 
            padding: 0 !important; 
            vertical-align: top; 
            background-color: var(--color-card-bg); 
            height: 3rem;
        } 

        .class-block-wrapper { 
            height: 100%; 
            width: 100%; 
            position: relative; 
            overflow: visible;
        }
        .class-block {
            position: absolute;
            top: -1px; 
            right: -1px; 
            bottom: -1px; 
            left: -1px;
            
            height: calc(100% + 2px); 
            width: calc(100% + 2px); 
            margin-top: -1px;
            margin-right: -1px; 
            
            padding: 0.5rem;
            border-left: 4px solid;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: filter 0.2s;
            cursor: pointer;
            border-radius: 0; 
            overflow: hidden; 
            z-index: 1;
        }
        .class-block:hover { filter: brightness(0.9); }
        
        /* --- ESTILOS DE COLOR DE BLOQUE --- */
        .color-0 { background-color: #dbeafe; border-color: #3b82f6 !important; color: #1e3a8a; } /* Blue */
        .color-1 { background-color: #d1fae5; border-color: #10b981 !important; color: #065f46; } /* Green */
        .color-2 { background-color: #fef3c7; border-color: #f59e0b !important; color: #b45309; } /* Yellow */
        .color-3 { background-color: #ede9fe; border-color: #8b5cf6 !important; color: #6d28d9; } /* Purple */
        .color-4 { background-color: #fee2e2; border-color: #ef4444 !important; color: #991b1b; } /* Red */
        .color-5 { background-color: #e0f2fe; border-color: #0ea5e9 !important; color: #075985; } /* Light Blue */
        .color-6 { background-color: #e5e7eb; border-color: #6b7280 !important; color: #374151; } /* Gray */

        .block-time { font-weight: 700; font-size: 0.8rem; line-height: 1; }
        .block-salon { font-weight: 800; font-size: 0.7rem; text-transform: uppercase; }
        .block-cycle { font-size: 0.6rem; opacity: 0.8; }


        /* Botón de eliminar horario */
        .btn-delete-schedule {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background-color: var(--color-danger);
            color: white;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            border: none;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 2;
        }
        .class-block:hover .btn-delete-schedule { opacity: 1; }

        /* --- LISTAS DE CATÁLOGOS (Estilos específicos para listas de catálogos) --- */
        .btn-delete-catalog { color: var(--color-danger); border: 1px solid #fca5a5; background-color: #fef2f2; }
        .btn-delete-catalog:hover { color: #b91c1c; background-color: #fee2e2; }
    </style>
    
    <div class="container py-4">
        
        <!-- MENSAJES DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-success) !important; background-color: #d1fae5; color: #065f46;">
                <p class="fw-bold">¡Éxito!</p>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- MOSTRAR ERRORES GENERALES -->
        @if ($errors->any())
            @unless($errors->has('email'))
            <div class="alert alert-danger border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-danger) !important; background-color: #fef2f2; color: var(--color-danger);">
                <p class="fw-bold">Error de Validación</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endunless
        @endif

        <!-- ---------------------------------------------------- -->
        <!-- FILA SUPERIOR: GESTIÓN DE CATÁLOGOS -->
        <!-- ---------------------------------------------------- -->
        <div class="row g-4 mb-4">
            
            <!-- GESTIÓN DE SALONES -->
            <div class="col-md-4">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary);">🏫 Gestión de Salones</h3>
                        
                        <!-- Formulario Agregar Salón -->
                        <form action="{{ route('director.classrooms.store') }}" method="POST" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="name" placeholder="Ej: A-101" required class="form-control form-control-sm">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: var(--color-primary);">Agregar</button>
                        </form>

                        <!-- Lista de Salones -->
                        <ul class="list-group list-group-flush" style="max-height: 10rem; overflow-y: auto;">
                            @forelse($salones as $salon)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: var(--color-white);">
                                    <span class="fw-bold text-dark">{{ $salon->name }}</span>
                                    
                                    <!-- ELIMINAR SALÓN: USANDO MODAL GENÉRICO -->
                                    <form id="delete-salon-{{ $salon->id }}" action="{{ route('director.classrooms.destroy', $salon->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-link delete-trigger" 
                                                style="color: var(--color-danger);" 
                                                data-form-id="delete-salon-{{ $salon->id }}"
                                                data-message="¿Eliminar el salón '{{ $salon->name }}'?">
                                            🗑️
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No hay salones registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- GESTIÓN DE CICLOS -->
            <div class="col-md-4">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-secondary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-secondary);">🔄 Gestión de Ciclos</h3>
                        
                        <!-- Formulario Agregar Ciclo -->
                        <form action="{{ route('director.cycles.store') }}" method="POST" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="name" placeholder="Ej: Ciclo VI" required class="form-control form-control-sm">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: var(--color-secondary);">Agregar</button>
                        </form>

                        <!-- Lista de Ciclos -->
                        <ul class="list-group list-group-flush" style="max-height: 10rem; overflow-y: auto;">
                            @forelse($ciclos as $ciclo)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: var(--color-white);">
                                    <span class="fw-bold text-dark">{{ $ciclo->name }}</span>
                                    
                                    <!-- ELIMINAR CICLO: USANDO MODAL GENÉRICO -->
                                    <form id="delete-cycle-{{ $ciclo->id }}" action="{{ route('director.cycles.destroy', $ciclo->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-link delete-trigger" 
                                                style="color: var(--color-danger);" 
                                                data-form-id="delete-cycle-{{ $ciclo->id }}"
                                                data-message="¿Eliminar el ciclo '{{ $ciclo->name }}'?">
                                            🗑️
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No hay ciclos registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- GESTIÓN DE CURSOS -->
            <div class="col-md-4">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary);">📖 Gestión de Cursos</h3>
                        
                        <!-- Formulario Agregar Curso -->
                        <form action="{{ route('director.courses.store') }}" method="POST" class="row g-2 mb-3">
                            @csrf
                            <div class="col-12">
                                <input type="text" name="codigo" placeholder="ID (Ej: WEB101)" required class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <input type="text" name="nombre" placeholder="Nombre (Ej: Desarrollo Web)" required class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm text-white w-100" style="background-color: var(--color-primary);">Agregar Curso</button>
                            </div>
                        </form>

                        <!-- Lista de Cursos -->
                        <ul class="list-group list-group-flush" style="max-height: 10rem; overflow-y: auto;">
                            @forelse($cursos as $curso)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: var(--color-white);">
                                    <div>
                                        <span class="fw-bold text-dark">{{ $curso->codigo }}</span> 
                                        <span class="text-muted" style="font-size: 0.8rem;">({{ $curso->nombre }})</span>
                                    </div>
                                    
                                    <!-- ELIMINAR CURSO: USANDO MODAL GENÉRICO -->
                                    <form id="delete-course-{{ $curso->id }}" action="{{ route('director.courses.destroy', $curso->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-link delete-trigger" 
                                                style="color: var(--color-danger);" 
                                                data-form-id="delete-course-{{ $curso->id }}"
                                                data-message="¿Eliminar el curso '{{ $curso->nombre }} ({{ $curso->codigo }})'?">
                                            🗑️
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No hay cursos registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ---------------------------------------------------- -->
        <!-- SECCIÓN INFERIOR: PROFESORES Y REGISTRO -->
        <!-- ---------------------------------------------------- -->
        <div class="row g-4">
            
            <!-- LISTADO COMPLETO DE PROFESORES -->
            <div class="col-md-6">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary);">📚 Todos los Profesores</h3>
                        
                        <div class="table-responsive" style="max-height: 25rem; overflow-y: auto;">
                            <table class="table table-striped table-sm align-middle m-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 5%;">#</th>
                                        <th scope="col" style="width: 40%;">Nombre</th>
                                        <th scope="col" style="width: 35%;">Correo</th>
                                        <th scope="col" style="width: 20%;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todos_profesores as $profe)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $profe->name }}</td>
                                        <td>{{ $profe->email }}</td>
                                        <td class="text-center">
                                            <!-- Botón para ver perfil (Detalle de Horarios) -->
                                            <a href="{{ route('director.profesor.show', $profe->id) }}" class="btn btn-sm text-white" style="background-color: var(--color-secondary); font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                                Gestionar
                                            </a>
                                            <!-- Botón de Eliminar (ID) -->
                                            
                                            <!-- ELIMINAR PROFESOR: USANDO MODAL GENÉRICO -->
                                            <form id="delete-profesor-{{ $profe->id }}" 
                                                action="{{ route('director.profesor.destroy', $profe->id) }}" 
                                                method="POST" 
                                                class="d-inline-block ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-link delete-trigger" 
                                                        style="color: var(--color-danger);" 
                                                        data-form-id="delete-profesor-{{ $profe->id }}"
                                                        data-message="¿Eliminar a {{ $profe->name }}? Esta acción es irreversible.">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16" style="color: var(--color-danger);">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2.5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zM4.5 5.5v9a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-9zM4.5 2h7V1.5h-7zm2-1v1h3V1h-3z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted fst-italic">No hay profesores registrados.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- REGISTRAR NUEVO PROFESOR Y HISTORIAL -->
            <div class="col-md-6">
                <div class="row g-4">
                    <!-- BLOQUE 2: REGISTRAR NUEVO PROFESOR -->
                    <div class="col-12">
                        <div class="card h-100 border-start border-5" style="border-color: var(--color-secondary) !important; background-color: var(--color-card-bg);">
                            <div class="card-body">
                                <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-secondary);">
                                    <span>👨‍🏫</span> Registrar Nuevo Profesor
                                </h3>
                                
                                <form method="POST" action="{{ route('director.profesor.store') }}" class="needs-validation">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-muted" style="font-size: 0.8rem;">Nombre del Docente</label>
                                        <input type="text" name="name" placeholder="Ej: Juan Pérez" required class="form-control form-control-sm">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted" style="font-size: 0.8rem;">Correo Institucional</label>
                                        <input type="email" name="email" placeholder="profe@vagoschool.com" required class="form-control form-control-sm @error('email') is-invalid @enderror">
                                        
                                        @error('email')
                                            <div class="invalid-feedback d-block" style="color: var(--color-danger);">
                                                El correo ya esta registrado.
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted" style="font-size: 0.8rem;">Contraseña Inicial</label>
                                        <input type="password" name="password" placeholder="********" required class="form-control form-control-sm">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-2 text-white" style="background-color: var(--color-secondary);">Registrar Docente</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE 3: HISTORIAL -->
                    <div class="col-12">
                        <div class="card h-100" style="background-color: var(--color-card-bg);">
                            <div class="card-body">
                                <h3 class="card-title fw-bold mb-3">📜 Historial Reciente</h3>
                                <ul class="list-group list-group-flush" style="max-height: 15rem; overflow-y: auto;">
                                    @forelse($logs as $log)
                                        <li class="list-group-item" style="font-size: 0.875rem; background-color: var(--color-card-bg);">
                                            <span class="fw-bold text-dark">{{ $log->actor }}</span>
                                            <span class="text-muted">{{ $log->accion }}</span>
                                            <div style="font-size: 0.75rem; color: #9ca3af;">{{ $log->created_at->diffForHumans() }}</div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted text-center" style="background-color: var(--color-card-bg);">No hay actividad reciente.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>