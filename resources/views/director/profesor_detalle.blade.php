<x-app-layout>
    
    <style>
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

        /* --- BLOQUES DE CLASE (CORRECCIÓN CRÍTICA DE ROWSPAN) --- */
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

        /* --- LISTAS DE CAPACITACIONES (Estilos simplificados) --- */
        .training-list-wrapper { background-color: #f9fafb; border-radius: var(--radius); border: 1px solid #e5e7eb; padding: 1rem; }
        .btn-delete-catalog { color: var(--color-danger); border: 1px solid #fca5a5; background-color: #fef2f2; }
        .btn-delete-catalog:hover { color: #b91c1c; background-color: #fee2e2; }
    </style>
    
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0 text-dark">Gestión de: {{ $profesor->name }}</h2>
            <span class="badge bg-secondary">{{ $profesor->email }}</span>
        </div>

        <div class="row g-4">
            
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger border-start border-5 p-3" role="alert" style="border-color: var(--color-danger) !important;">
                        <p class="fw-bold">¡Atención!</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="col-12">
                    <div class="alert alert-success border-start border-5 p-3" role="alert" style="border-color: var(--color-success) !important;">
                        <p class="fw-bold">¡Éxito!</p>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="col-12">
                <div class="card border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-primary);">📅 Gestión de Horarios</h3>
                        
                        <form action="{{ route('director.schedule.store', $profesor->id) }}" method="POST" class="row g-3 mb-4 p-3 border rounded" style="background-color: #f9fafb;">
                            @csrf
                            
                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Día</label>
                                <select name="dia" class="form-select form-select-sm">
                                    @foreach($dias_semana as $d) <option>{{ $d }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Curso (ID)</label>
                                <select name="course_codigo" class="form-select form-select-sm">
                                    @foreach($cursos as $curso) <option value="{{ $curso->codigo }}">{{ $curso->codigo }} ({{ $curso->nombre }})</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Salón</label>
                                <select name="salon" class="form-select form-select-sm">
                                    @foreach($salones as $salon) <option value="{{ $salon }}">{{ $salon }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Ciclo</label>
                                <select name="ciclo" class="form-select form-select-sm">
                                    @foreach($ciclos as $ciclo) <option value="{{ $ciclo }}">{{ $ciclo }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Inicio</label>
                                <select name="hora_inicio" class="form-select form-select-sm">
                                    @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Fin</label>
                                <select name="hora_fin" class="form-select form-select-sm">
                                    @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: var(--color-primary);">+ Agregar</button>
                            </div>
                        </form>

                        <h4 class="small fw-bold text-muted text-uppercase mb-2">Vista Gráfica Semanal</h4>
                        
                        <div class="table-responsive border rounded shadow-sm">
                            <table class="schedule-table">
                                <thead>
                                    <tr class="table-head-row">
                                        <th class="time-cell-header" style="background-color: var(--color-primary); color: white;">Hora</th>
                                        @foreach($dias_semana as $dia)
                                            <th>{{ $dia }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach($bloques_horarios as $hora_bloque)
                                        <tr class="table-body-row">
                                            
                                            <td class="time-cell">
                                                {{ $hora_bloque }}
                                            </td>

                                            @foreach($dias_semana as $dia)
                                                @php
                                                    $base_fecha = "2024-01-01 "; 
                                                    $t_bloque = strtotime($base_fecha . $hora_bloque);
                                                    $clase_encontrada = null; $es_inicio_bloque = false; $celda_ocupada = false; $filas_a_ocupar = 1;

                                                    foreach($horarios as $clase) {
                                                        if(trim($clase->dia) != $dia) continue;
                                                        $t_inicio = strtotime($base_fecha . $clase->hora_inicio);
                                                        $t_fin = strtotime($base_fecha . $clase->hora_fin);
                                                        
                                                        if (abs($t_inicio - $t_bloque) < 60) {
                                                            $clase_encontrada = $clase; $es_inicio_bloque = true;
                                                            $filas_a_ocupar = ceil(($t_fin - $t_inicio) / 1800); 
                                                            break;
                                                        }
                                                        if ($t_bloque > $t_inicio && $t_bloque < $t_fin) { $celda_ocupada = true; break; }
                                                    }
                                                @endphp

                                                @if($es_inicio_bloque)
                                                    <td rowspan="{{ $filas_a_ocupar }}" class="class-day-cell">
                                                        <div class="class-block-wrapper">
                                                            @php
                                                                // Lógica para asignar un color basado en el ID del horario
                                                                // Esto garantiza que cada bloque de horario tenga un color diferente.
                                                                $color_index = $clase_encontrada->id % 7; 
                                                            @endphp
                                                            <div class="class-block color-{{ $color_index }}">
                                                                
                                                                <div class="fw-bolder" style="font-size: 0.8rem; margin-bottom: 0.1rem;">
                                                                    {{ $clase_encontrada->course_codigo }}
                                                                </div>

                                                                <div class="block-time">
                                                                    {{ date('H:i', strtotime($clase_encontrada->hora_inicio)) }} - {{ date('H:i', strtotime($clase_encontrada->hora_fin)) }}
                                                                </div>
                                                                <div class="block-salon">{{ $clase_encontrada->salon }}</div>
                                                                <div class="block-cycle">Ciclo: {{ $clase_encontrada->ciclo }}</div>
                                                                
                                                                <form id="delete-schedule-{{ $clase_encontrada->id }}" 
                                                                    action="{{ route('director.schedule.destroy', $clase_encontrada->id) }}" 
                                                                    method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" 
                                                                            class="btn-delete-schedule delete-trigger" 
                                                                            data-form-id="delete-schedule-{{ $clase_encontrada->id }}"
                                                                            data-message="¿Eliminar el horario del curso {{ $clase_encontrada->course_codigo }}?">
                                                                        ✕
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @elseif(!$celda_ocupada)
                                                    <td class="day-cell" style="background-color: white;"></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card h-100 border-start border-5" style="border-color: #10b981 !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: #10b981;">🎓 Gestión de Capacitaciones</h3>
                        
                        <form action="{{ route('director.training.store', $profesor->id) }}" method="POST" class="row g-3 mb-4">
                            @csrf
                            <div class="col-md-7">
                                <input type="text" name="nombre" placeholder="Nombre del curso o taller..." required class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="fecha" required class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-sm w-100" style="background-color: #10b981;">Asignar</button>
                            </div>
                        </form>

                        <div class="p-3 border rounded" style="background-color: #f9fafb;">
                            <h4 class="small fw-bold text-muted text-uppercase mb-2">Historial de cursos</h4>
                            
                            <ul class="list-group list-group-flush">
                                @forelse($capacitaciones as $c)
                                    <li class="list-group-item d-flex justify-content-between align-items-center rounded mb-1 shadow-sm" style="background-color: var(--color-white);">
                                        <div class="d-flex align-items-center">
                                            <span class="rounded-circle me-2" style="width: 0.5rem; height: 0.5rem; background-color: #10b981;"></span>
                                            <strong class="text-dark me-2" style="font-size: 0.875rem;">{{ $c->nombre }}</strong>
                                            <span class="text-muted" style="font-size: 0.75rem;">({{ date('d/m/Y', strtotime($c->fecha)) }})</span>
                                        </div>
                                        
                                        <form id="delete-training-{{ $c->id }}" action="{{ route('director.training.destroy', $c->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-sm btn-delete-catalog delete-trigger" 
                                                    data-form-id="delete-training-{{ $c->id }}"
                                                    data-message="¿Borrar la capacitación '{{ $c->nombre }}'?">
                                                Eliminar
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No hay capacitaciones asignadas.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-5">
                <div class="card border-danger border-2 shadow-sm" style="background-color: #fef2f2;">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: #dc2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Zona de Peligro
                        </h3>
                        <p class="text-muted mb-4">
                            Esta acción es irreversible. Al eliminar al profesor <strong>{{ $profesor->name }}</strong>, se borrarán permanentemente todos sus horarios asignados y su acceso al sistema.
                        </p>
                        
                        <div class="d-flex justify-content-end border-top pt-3">
                            <form id="delete-profesor-{{ $profesor->id }}" 
                                action="{{ route('director.profesor.destroy', $profesor->id) }}" 
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="btn btn-danger text-white fw-bold d-flex align-items-center gap-2 delete-trigger" 
                                        data-form-id="delete-profesor-{{ $profesor->id }}"
                                        data-message="¿Estás COMPLETAMENTE SEGURO de eliminar al profesor {{ $profesor->name }}? Esta acción no se puede deshacer.">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar Profesor Definitivamente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>