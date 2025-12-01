<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0 text-dark">Gestión de: {{ $profesor->name }}</h2>
            <span class="badge bg-secondary">{{ $profesor->email }}</span>
        </div>

        <div class="row g-4">
            
            <!-- MOSTRAR ERRORES -->
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger border-start border-5 border-danger p-3" role="alert">
                        <p class="fw-bold">¡Atención!</p>
                        <p>{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <!-- MOSTRAR ÉXITO -->
            @if(session('success'))
                <div class="col-12">
                    <div class="alert alert-success border-start border-5 border-success p-3" role="alert">
                        <p class="fw-bold">¡Éxito!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- 1. SECCIÓN DE HORARIOS -->
            <div class="col-12">
                <div class="card border-start border-5" style="border-color: var(--color-primary) !important;">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: var(--color-primary);">📅 Gestión de Horarios</h3>
                        
                        <!-- FORMULARIO DE AGREGAR HORARIO -->
                        <form action="{{ route('director.schedule.store', $profesor->id) }}" method="POST" class="row g-3 mb-4 p-3 border rounded" style="background-color: #f9fafb;">
                            @csrf
                            
                            <div class="col-md-2">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">Día</label>
                                <select name="dia" class="form-select form-select-sm">
                                    @foreach($dias_semana as $d) <option>{{ $d }}</option> @endforeach
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

                        <!-- TABLA VISUAL DE HORARIOS -->
                        <h4 class="small fw-bold text-muted text-uppercase mb-2">Vista Gráfica Semanal</h4>
                        
                        <div class="table-responsive border rounded shadow-sm">
                            <table class="table table-bordered table-sm m-0" style="table-layout: fixed;">
                                <thead>
                                    <tr class="text-white text-center" style="background-color: var(--color-primary);">
                                        <th style="width: 10%;">Hora</th>
                                        @foreach($dias_semana as $dia)
                                            <th>{{ $dia }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach($bloques_horarios as $hora_bloque)
                                        <tr style="height: 3rem;">
                                            
                                            <td class="text-center small fw-bold" style="background-color: #f9fafb; width: 10%;">
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
                                                    <td rowspan="{{ $filas_a_ocupar }}" class="p-0" style="vertical-align: top;">
                                                        <div class="h-100 w-100 position-relative p-1">
                                                            <div class="p-2 border-start border-4 h-100 d-flex flex-column justify-content-center align-items-center rounded shadow-sm"
                                                                style="background-color: @if(in_array($clase_encontrada->ciclo, ['I','1'])) #dbeafe; border-color: #3b82f6 !important; color: #1e3a8a; 
                                                                       @elseif(in_array($clase_encontrada->ciclo, ['II','2'])) #d1fae5; border-color: #10b981 !important; color: #065f46;
                                                                       @elseif(in_array($clase_encontrada->ciclo, ['III','3'])) #fef3c7; border-color: #f59e0b !important; color: #b45309;
                                                                       @else #ede9fe; border-color: #8b5cf6 !important; color: #6d28d9; @endif">
                                                                
                                                                <div class="fw-bold" style="font-size: 0.8rem;">
                                                                    {{ date('H:i', strtotime($clase_encontrada->hora_inicio)) }} - {{ date('H:i', strtotime($clase_encontrada->hora_fin)) }}
                                                                </div>
                                                                <div class="fw-bolder text-uppercase mt-1" style="font-size: 0.7rem;">{{ $clase_encontrada->salon }}</div>
                                                                <div class="small opacity-75" style="font-size: 0.6rem;">Ciclo: {{ $clase_encontrada->ciclo }}</div>

                                                                <!-- Botón Eliminar (Visible al pasar el mouse) -->
                                                                <form action="{{ route('director.schedule.destroy', $clase_encontrada->id) }}" method="POST" class="position-absolute top-0 end-0 p-1">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle p-0 d-flex justify-content-center align-items-center" style="width: 1.25rem; height: 1.25rem; font-size: 0.75rem;" onclick="return confirm('¿Eliminar horario?');">
                                                                        &times;
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @elseif(!$celda_ocupada)
                                                    <td class="bg-white p-0"></td>
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

            <!-- 2. SECCIÓN CAPACITACIONES -->
            <div class="col-12">
                <div class="card h-100 border-start border-5" style="border-color: #10b981 !important;">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3" style="color: #10b981;">🎓 Gestión de Capacitaciones</h3>
                        
                        <!-- Formulario Agregar -->
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

                        <!-- Lista de Capacitaciones (SOLO LECTURA Y ELIMINAR) -->
                        <div class="p-3 border rounded" style="background-color: #f9fafb;">
                            <h4 class="small fw-bold text-muted text-uppercase mb-2">Historial de cursos</h4>
                            
                            <ul class="list-group list-group-flush">
                                @forelse($capacitaciones as $c)
                                    <li class="list-group-item d-flex justify-content-between align-items-center rounded mb-1 shadow-sm" style="background-color: #fff;">
                                        <div class="d-flex align-items-center">
                                            <span class="rounded-circle me-2" style="width: 0.5rem; height: 0.5rem; background-color: #10b981;"></span>
                                            <strong class="text-dark me-2" style="font-size: 0.875rem;">{{ $c->nombre }}</strong>
                                            <span class="text-muted" style="font-size: 0.75rem;">({{ date('d/m/Y', strtotime($c->fecha)) }})</span>
                                        </div>
                                        
                                        <!-- BOTÓN ELIMINAR -->
                                        <form action="{{ route('director.training.destroy', $c->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger border-danger" style="font-size: 0.65rem; padding: 0.1rem 0.5rem; background-color: #fef2f2;" onclick="return confirm('¿Borrar esta capacitación?');">
                                                Eliminar
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted fst-italic">No hay capacitaciones asignadas.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 3. ZONA DE PELIGRO (ELIMINAR PROFESOR) -->
            <div class="col-12 mt-5">
                <div class="card border-danger border-2 shadow-sm" style="background-color: #fef2f2;">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3 d-flex align-items-center gap-2" style="color: #dc2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Zona de Peligro
                        </h3>
                        <p class="text-muted mb-4">
                            Esta acción es irreversible. Al eliminar al profesor <strong>{{ $profesor->name }}</strong>, se borrarán permanentemente todos sus horarios asignados y su acceso al sistema.
                        </p>
                        
                        <div class="d-flex justify-content-end border-top pt-3">
                            <form action="{{ route('director.profesor.destroy', $profesor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger text-white fw-bold d-flex align-items-center gap-2" onclick="return confirm('¿Estás COMPLETAMENTE SEGURO? Esta acción no se puede deshacer.');">
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