<x-app-layout>
    
    <style>
        :root {
            --color-primary: #2B7A78;
            --color-secondary: #3AAFA9;
            --color-green: #10b981;
            --color-danger: #DC2626;
            --color-card-bg: #DEF2F1;
            --color-white: #FEFFFF;
            --color-text-dark: #1f2937;
            --color-border: #e5e7eb;
            --radius: 0.5rem;
        }

        /* Estilos específicos de la tabla de horario */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            table-layout: fixed;
        }
        .schedule-table th, .schedule-table td { border: 1px solid var(--color-border); }
        .table-head-row { background-color: var(--color-primary); color: white; height: 2.5rem; text-align: center; }
        .time-cell { background-color: #f9fafb; width: 10%; font-weight: bold; }
        .table-body-row { height: 3rem; }

        /* Estilos del Bloque de Clase (Rowspan) */
        .class-day-cell { padding: 0 !important; vertical-align: top; background-color: var(--color-card-bg); height: 3rem; } 
        .class-block-wrapper { height: 100%; width: 100%; position: relative; overflow: visible; }
        .class-block {
            position: absolute; top: -1px; right: -1px; bottom: -1px; left: -1px;
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
            border-radius: 0; 
            z-index: 1;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
            /* Sombra para diferenciar del fondo de la tabla */
        }
        
        /* Colores de ejemplo para los bloques */
        .c-blue { background-color: #dbeafe; border-color: #3b82f6 !important; color: #1e3a8a; }
        .c-green { background-color: #d1fae5; border-color: var(--color-green) !important; color: #065f46; }
        .c-yellow { background-color: #fef3c7; border-color: #f59e0b !important; color: #b45309; }
        .c-purple { background-color: #ede9fe; border-color: #8b5cf6 !important; color: #6d28d9; }

        .block-course { font-weight: 700; font-size: 0.8rem; line-height: 1; margin-bottom: 0.1rem; }
        .block-detail { font-size: 0.7rem; }
    </style>

    <div class="container py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background-color: var(--color-card-bg); border-left: 4px solid var(--color-secondary);">
            <h1 class="h4 mb-0 text-dark">
                Bienvenido, {{ $profesor->name }}
            </h1>
            <span class="badge" style="background-color: var(--color-primary); color: white;">
                {{ $profesor->email }}
            </span>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-success) !important;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-danger) !important;">
                Por favor, corrige los errores en el formulario de Contraseña.
            </div>
        @endif

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-primary);">🗓️ Horario de Clases Asignadas</h3>
                        
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
                                                            <div class="class-block
                                                                @if(in_array($clase_encontrada->ciclo, ['I','1'])) c-blue
                                                                @elseif(in_array($clase_encontrada->ciclo, ['II','2'])) c-green
                                                                @elseif(in_array($clase_encontrada->ciclo, ['III','3'])) c-yellow
                                                                @else c-purple @endif">
                                                                
                                                                <div class="block-course">{{ $clase_encontrada->course_codigo }}</div>
                                                                <div class="block-time">
                                                                    {{ date('H:i', strtotime($clase_encontrada->hora_inicio)) }} - {{ date('H:i', strtotime($clase_encontrada->hora_fin)) }}
                                                                </div>
                                                                <div class="block-detail">{{ $cursos[$clase_encontrada->course_codigo] ?? 'Curso Desconocido' }}</div>
                                                                <div class="block-detail">{{ $clase_encontrada->salon }} / Ciclo {{ $clase_encontrada->ciclo }}</div>
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

            <div class="col-lg-4">
                
                <div class="card mb-4 border-start border-5" style="border-color: var(--color-secondary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-secondary);">🔑 Cambiar Contraseña</h3>
                        
                        <form action="{{ route('profesor.update.password') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted" for="current_password" style="font-size: 0.8rem;">Contraseña Actual</label>
                                <input type="password" id="current_password" name="current_password" required class="form-control form-control-sm @error('current_password', 'updatePassword') is-invalid @enderror">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted" for="new_password" style="font-size: 0.8rem;">Nueva Contraseña</label>
                                <input type="password" id="new_password" name="new_password" required class="form-control form-control-sm @error('new_password', 'updatePassword') is-invalid @enderror">
                                @error('new_password', 'updatePassword')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted" for="new_password_confirmation" style="font-size: 0.8rem;">Confirmar Nueva Contraseña</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required class="form-control form-control-sm">
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2" style="background-color: var(--color-secondary);">
                                Actualizar Contraseña
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card h-100 border-start border-5" style="border-color: var(--color-green) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-green);">🎓 Mis Capacitaciones</h3>
                        
                        <div class="p-3 border rounded" style="background-color: #f9fafb;">
                            <h4 class="small fw-bold text-muted text-uppercase mb-2">Cursos Completados</h4>
                            
                            <ul class="list-group list-group-flush">
                                @forelse($capacitaciones as $c)
                                    <li class="list-group-item d-flex justify-content-between align-items-center rounded mb-1 shadow-sm" style="background-color: var(--color-white);">
                                        <div class="d-flex align-items-center">
                                            <span class="rounded-circle me-2" style="width: 0.5rem; height: 0.5rem; background-color: var(--color-green);"></span>
                                            <strong class="text-dark me-2" style="font-size: 0.875rem;">{{ $c->nombre }}</strong>
                                            <span class="text-muted" style="font-size: 0.75rem;">({{ date('d/m/Y', strtotime($c->fecha)) }})</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No tienes capacitaciones registradas.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>