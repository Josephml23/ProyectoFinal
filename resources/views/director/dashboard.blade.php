<x-app-layout>
    
    <!-- SIN X-SLOT HEADER -->
    
    <div class="container py-4">
        
        <!-- MENSAJES DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success border-start border-4 p-3 mb-4" role="alert" style="border-color: var(--color-primary) !important; background-color: #d1fae5; color: #065f46;">
                <p class="fw-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="row g-4 mb-4">
            
            <!-- GESTIÓN DE SALONES -->
            <div class="col-md-6">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-primary) !important;">
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
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: var(--color-card-bg);">
                                    <span class="fw-bold text-dark">{{ $salon->name }}</span>
                                    <form action="{{ route('director.classrooms.destroy', $salon->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link" style="color: var(--color-danger);" onclick="return confirm('¿Borrar salón?');" title="Eliminar">🗑️</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-card-bg);">No hay salones registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- GESTIÓN DE CICLOS -->
            <div class="col-md-6">
                <div class="card h-100 border-start border-5" style="border-color: var(--color-secondary) !important;">
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
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: var(--color-card-bg);">
                                    <span class="fw-bold text-dark">{{ $ciclo->name }}</span>
                                    <form action="{{ route('director.cycles.destroy', $ciclo->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link" style="color: var(--color-danger);" onclick="return confirm('¿Borrar ciclo?');" title="Eliminar">🗑️</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted fst-italic" style="background-color: var(--color-card-bg);">No hay ciclos registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- BLOQUE 1: BUSCADOR DE PROFESORES -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title fw-bold mb-3">🔍 Buscar Profesor</h3>
                <form method="GET" action="{{ route('director.dashboard') }}" class="d-flex gap-3">
                    <input type="text" name="search" placeholder="Escribe el nombre del profesor..." class="form-control form-control-sm">
                    <button type="submit" class="btn btn-primary btn-sm text-white" style="background-color: var(--color-primary);">Buscar</button>
                </form>

                @if(isset($profesores) && count($profesores) > 0)
                    <div class="row g-3 mt-3">
                        @foreach($profesores as $profe)
                            <div class="col-md-6">
                                <div class="card border-primary shadow-sm" style="position: relative; border-color: var(--color-secondary) !important;">
                                    <div class="card-body p-3">
                                        <a href="{{ route('director.profesor.show', $profe->id) }}" class="text-decoration-none">
                                            <div class="fw-bold" style="color: var(--color-primary);">{{ $profe->name }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $profe->email }}</div>
                                        </a>
                                        <!-- Botón de Eliminar (Flotante) -->
                                        <form action="{{ route('director.profesor.destroy', $profe->id) }}" method="POST" style="position: absolute; top: 0.5rem; right: 0.5rem;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light p-1" onclick="return confirm('¿Eliminar a {{ $profe->name }}?');" title="Eliminar Profesor">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16" style="color: var(--color-danger);">
                                                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2.5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zM4.5 5.5v9a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-9zM4.5 2h7V1.5h-7zm2-1v1h3V1h-3z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(request('search'))
                    <p class="alert alert-danger mt-3">No se encontraron profesores con ese nombre.</p>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- BLOQUE 2: REGISTRAR NUEVO PROFESOR -->
            <div class="col-md-6">
                <div class="card h-100 border-start border-5" style="border-color: #3AAFA9 !important;">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3 d-flex align-items-center gap-2" style="color: #3AAFA9;">
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
                                <input type="email" name="email" placeholder="profe@vagoschool.com" required class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted" style="font-size: 0.8rem;">Contraseña Inicial</label>
                                <input type="password" name="password" placeholder="********" required class="form-control form-control-sm">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2 text-white" style="background-color: #3AAFA9;">Registrar Docente</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BLOQUE 3: HISTORIAL -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title fw-bold mb-3">📜 Historial Reciente</h3>
                        <ul class="list-group list-group-flush" style="max-height: 15rem; overflow-y: auto;">
                            @if(isset($logs))
                                @foreach($logs as $log)
                                    <li class="list-group-item" style="font-size: 0.875rem;">
                                        <span class="fw-bold text-dark">{{ $log->actor }}</span>
                                        <span class="text-muted">{{ $log->accion }}</span>
                                        <div style="font-size: 0.75rem; color: #9ca3af;">{{ $log->created_at->diffForHumans() }}</div>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item text-muted text-center">No hay actividad reciente.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>