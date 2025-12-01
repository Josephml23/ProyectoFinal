<x-app-layout>
    
    <div class="container py-4">
        
        <!-- MENSAJES DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-success) !important; background-color: #d1fae5; color: #065f46;">
                <p class="fw-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- MOSTRAR ERRORES GENERALES -->
        @if ($errors->any())
            @unless($errors->has('email'))
            <div class="alert alert-danger border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-danger) !important; background-color: #fef2f2; color: var(--color-danger);">
                <p class="fw-bold">Error de Validación</p>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endunless
        @endif

        <!-- ---------------------------------------------------- -->
        <!-- NUEVA FILA SUPERIOR: GESTIÓN DE CATÁLOGOS -->
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
                                    <form action="{{ route('director.classrooms.destroy', $salon->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link" style="color: var(--color-danger);" onclick="return confirm('¿Borrar salón?');" title="Eliminar">🗑️</button>
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
                                    <form action="{{ route('director.cycles.destroy', $ciclo->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link" style="color: var(--color-danger);" onclick="return confirm('¿Borrar ciclo?');" title="Eliminar">🗑️</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-white);">No hay ciclos registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- GESTIÓN DE CURSOS (NUEVO BLOQUE) -->
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
                                    <form action="{{ route('director.courses.destroy', $curso->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link" style="color: var(--color-danger);" onclick="return confirm('¿Borrar curso?');" title="Eliminar">🗑️</button>
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
                                            <form action="{{ route('director.profesor.destroy', $profe->id) }}" method="POST" class="d-inline-block ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger" onclick="return confirm('¿Eliminar a {{ $profe->name }}?');" title="Eliminar Profesor">
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