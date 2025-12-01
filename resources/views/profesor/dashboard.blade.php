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
            transition: filter 0.2s; 
        }
        .class-block:hover { filter: brightness(0.95); }
        
        /* --- ESTILOS DE COLOR DINÁMICO DE BLOQUE --- */
        .color-0 { background-color: #dbeafe; border-color: #3b82f6 !important; color: #1e3a8a; } 
        .color-1 { background-color: #d1fae5; border-color: var(--color-green) !important; color: #065f46; } 
        .color-2 { background-color: #fef3c7; border-color: #f59e0b !important; color: #b45309; } 
        .color-3 { background-color: #ede9fe; border-color: #8b5cf6 !important; color: #6d28d9; } 
        .color-4 { background-color: #fee2e2; border-color: var(--color-danger) !important; color: #991b1b; } 
        .color-5 { background-color: #e0f2fe; border-color: #0ea5e9 !important; color: #075985; } 
        .color-6 { background-color: #e5e7eb; border-color: #6b7280 !important; color: #374151; } 

        .block-course { font-weight: 700; font-size: 0.8rem; line-height: 1; margin-bottom: 0.1rem; }
        .block-detail { font-size: 0.7rem; }

        /* --- Estilos de Descarga PDF --- */
        #horario-container { width: 100%; margin-bottom: 2rem; }
        .btn-pdf-descarga {
            background-color: var(--color-secondary);
            color: var(--color-white);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius);
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 0.9rem;
        }

        .btn-pdf-descarga:hover { background-color: #318a85; }
        
        /* ============================================================= */
        /* AJUSTES CLAVE DE CSS PARA LA IMPRESIÓN/PDF */
        /* ============================================================= */
        @media print {
            .btn-pdf-descarga { display: none !important; }
            .row.g-4 { display: none !important; } 
            .container.py-4 { padding: 0 !important; margin: 0 !important; }
            #horario-a-descargar, .card-body, .table-responsive {
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background-color: white !important;
            }
            .schedule-table { 
                font-size: 0.6rem !important; 
                max-width: 100%;
                table-layout: auto !important; 
            }
            .table-body-row { height: 2.5rem !important; }
            .block-course { font-size: 0.7rem !important; }
            .block-detail, .block-time { font-size: 0.6rem !important; }
            .schedule-table { width: 100% !important; }
            #perfil-header { margin-bottom: 0.5rem !important; }
            .profile-card { border: none !important; box-shadow: none !important; padding: 0.5rem 0 !important; }
            .profile-details, .course-list-print { font-size: 0.8rem !important; }
        }
        
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
            <div class="alert alert-success border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-green) !important;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-danger) !important;">
                Por favor, corrige los errores en el formulario.
            </div>
        @endif

        <div class="row g-4 mb-4" id="perfil-header">
            
            <div class="col-lg-6">
                <div class="card h-100 border-start border-5 profile-card" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="card-title h5 mb-0" style="color: var(--color-primary);">👤 Perfil de Usuario</h3>
                            <button class="btn btn-sm btn-outline-secondary" style="font-size: 0.8rem;"
                                data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                Modificar Perfil
                            </button>
                        </div>
                        
                        <div class="profile-details">
                            <p class="mb-1"><strong class="text-muted">Nombre Completo:</strong> {{ $profesor->name ?? 'N/A' }} {{ $profesor->lastname ?? 'N/A' }}</p>
                            <p class="mb-1"><strong class="text-muted">DNI:</strong> {{ $profesor->dni ?? 'N/A' }}</p>
                            <p class="mb-1"><strong class="text-muted">Email:</strong> {{ $profesor->email ?? 'N/A' }}</p>
                            <p class="mb-1"><strong class="text-muted">Dirección:</strong> {{ $profesor->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 border-start border-5 profile-card" style="border-color: var(--color-secondary) !important; background-color: var(--color-card-bg);">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-secondary);">📚 Cursos Asignados</h3>
                        
                        <div class="course-list-print" style="max-height: 150px; overflow-y: auto;">
                            <ul class="list-group list-group-flush small">
                                @php
                                    $cursos_asignados = [];
                                    foreach($horarios as $clase) {
                                        $codigo = $clase->course_codigo;
                                        if (!isset($cursos_asignados[$codigo])) {
                                            $cursos_asignados[$codigo] = $cursos[$codigo] ?? 'Curso Desconocido';
                                        }
                                    }
                                @endphp

                                @forelse($cursos_asignados as $codigo => $nombre)
                                    <li class="list-group-item d-flex justify-content-between align-items-center rounded mb-1 shadow-sm" style="background-color: var(--color-white); font-size: 0.85rem;">
                                        <strong class="text-dark">{{ $nombre }}</strong>
                                        <span class="badge" style="background-color: var(--color-primary);">{{ $codigo }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No tiene cursos asignados para este periodo.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="horario-container">
            <div id="horario-a-descargar" class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important; background-color: var(--color-card-bg);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="card-title h5 mb-0" style="color: var(--color-primary);">🗓️ Horario de Clases Asignadas</h3>
                        
                        <button id="descargar-pdf" class="btn-pdf-descarga">
                            <i class="fas fa-file-pdf me-1"></i> Descargar Horario PDF
                        </button>
                    </div>
                    
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
                                                             $color_index = $clase_encontrada->id % 7; 
                                                        @endphp
                                                        <div class="class-block color-{{ $color_index }}">
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


        <div class="row g-4">
            <div class="col-lg-6">
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
            </div>

            <div class="col-lg-6">
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

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--color-primary); color: white;">
                    <h5 class="modal-title" id="editProfileModalLabel">📝 Modificar Datos de Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profesor.update.profile') }}" method="POST">
                    @csrf
                    @method('PUT') <div class="modal-body">
                        
                        <div class="mb-3">
                            <label for="profile_name" class="form-label small">Nombre(s)</label>
                            <input type="text" class="form-control form-control-sm" id="profile_name" name="name" value="{{ $profesor->name ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_lastname" class="form-label small">Apellido(s)</label>
                            <input type="text" class="form-control form-control-sm" id="profile_lastname" name="lastname" value="{{ $profesor->lastname ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_dni" class="form-label small">DNI</label>
                            <input type="text" class="form-control form-control-sm" id="profile_dni" name="dni" value="{{ $profesor->dni ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="profile_address" class="form-label small">Dirección</label>
                            <input type="text" class="form-control form-control-sm" id="profile_address" name="address" value="{{ $profesor->address ?? '' }}">
                        </div>
                        <div class="alert alert-info small" role="alert">
                            Nota: La dirección de correo electrónico solo puede ser modificada por la administración.
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--color-primary); border-color: var(--color-primary);">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica para la descarga de PDF (se mantiene)
            const botonDescarga = document.getElementById('descargar-pdf');
            const elementoADescargar = document.getElementById('horario-a-descargar');

            if (botonDescarga && elementoADescargar) {
                botonDescarga.addEventListener('click', function() {
                    botonDescarga.style.display = 'none';

                    const opciones = {
                        margin:       [5, 5, 5, 5], 
                        filename:     'horario_profesor_{{ $profesor->id ?? 'desconocido' }}.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 1.5, logging: true, dpi: 192, letterRendering: true },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' } 
                    };

                    html2pdf().set(opciones).from(elementoADescargar).save()
                        .then(function() {
                            botonDescarga.style.display = 'block';
                        })
                        .catch(function(error) {
                            console.error('Error durante la generación del PDF:', error);
                            alert('Hubo un error al generar el PDF. Por favor, inténtalo de nuevo.');
                            botonDescarga.style.display = 'block';
                        });
                });
            }
        });
    </script>

</x-app-layout>