<!-- Carga de estilos de emergencia -->
<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <x-slot name="header">
        <!-- ENCABEZADO CORREGIDO CON COLOR BLANCO -->
        <h2 class="font-semibold text-xl text-white leading-tight bg-indigo-700 p-2 rounded-lg shadow-lg">
            {{ __('Administración - Vagos School') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- MENSAJES DE ÉXITO -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- SECCIÓN DE GESTIÓN DE CATÁLOGOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- GESTIÓN DE SALONES -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">🏫 Gestión de Salones</h3>
                    
                    <!-- Formulario Agregar Salón -->
                    <form action="{{ route('director.classrooms.store') }}" method="POST" class="flex gap-2 mb-4">
                        @csrf
                        <input type="text" name="name" placeholder="Ej: A-101" required class="flex-1 rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700 transition">Agregar</button>
                    </form>

                    <!-- Lista de Salones -->
                    <ul class="space-y-2 max-h-40 overflow-y-auto pr-2">
                        @forelse($salones as $salon)
                            <li class="flex justify-between items-center bg-gray-50 p-2 rounded border">
                                <span class="font-bold text-gray-700">{{ $salon->name }}</span>
                                <form action="{{ route('director.classrooms.destroy', $salon->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-2" onclick="return confirm('¿Borrar salón?');" title="Eliminar">🗑️</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm text-center italic">No hay salones registrados.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- GESTIÓN DE CICLOS -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">🔄 Gestión de Ciclos</h3>
                    
                    <!-- Formulario Agregar Ciclo -->
                    <form action="{{ route('director.cycles.store') }}" method="POST" class="flex gap-2 mb-4">
                        @csrf
                        <input type="text" name="name" placeholder="Ej: Ciclo VI" required class="flex-1 rounded border-gray-300 p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-purple-700 transition">Agregar</button>
                    </form>

                    <!-- Lista de Ciclos -->
                    <ul class="space-y-2 max-h-40 overflow-y-auto pr-2">
                        @forelse($ciclos as $ciclo)
                            <li class="flex justify-between items-center bg-gray-50 p-2 rounded border">
                                <span class="font-bold text-gray-700">{{ $ciclo->name }}</span>
                                <form action="{{ route('director.cycles.destroy', $ciclo->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-2" onclick="return confirm('¿Borrar ciclo?');" title="Eliminar">🗑️</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm text-center italic">No hay ciclos registrados.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- BLOQUE 1: BUSCADOR DE PROFESORES -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Buscar Profesor</h3>
                <form method="GET" action="{{ route('director.dashboard') }}" class="flex gap-4">
                    <input type="text" name="search" placeholder="Escribe el nombre del profesor..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold">Buscar</button>
                </form>

                @if(isset($profesores) && count($profesores) > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($profesores as $profe)
                            <div class="relative group">
                                <a href="{{ route('director.profesor.show', $profe->id) }}" class="block p-4 border border-indigo-200 rounded bg-indigo-50 hover:bg-indigo-100 transition">
                                    <div class="font-bold text-indigo-700 group-hover:text-indigo-900">{{ $profe->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $profe->email }}</div>
                                </a>

                                <!-- Botón de Eliminar (Flotante) -->
                                <form action="{{ route('director.profesor.destroy', $profe->id) }}" method="POST" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 bg-white rounded-full p-1 shadow hover:shadow-md transition" onclick="return confirm('¿Estás SEGURO de eliminar a {{ $profe->name }}?');" title="Eliminar Profesor">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @elseif(request('search'))
                    <p class="mt-4 text-red-500 bg-red-50 p-2 rounded">No se encontraron profesores con ese nombre.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- BLOQUE 2: REGISTRAR NUEVO PROFESOR -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>👨‍🏫</span> Registrar Nuevo Profesor
                    </h3>
                    
                    <form method="POST" action="{{ route('director.profesor.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Docente</label>
                            <input type="text" name="name" placeholder="Ej: Juan Pérez" required class="w-full rounded border border-gray-300 p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Correo Institucional</label>
                            <input type="email" name="email" placeholder="profe@vagoschool.com" required class="w-full rounded border border-gray-300 p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contraseña Inicial</label>
                            <input type="password" name="password" placeholder="********" required class="w-full rounded border border-gray-300 p-2">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-bold shadow">Registrar Docente</button>
                    </form>
                </div>

                <!-- BLOQUE 3: HISTORIAL -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📜 Historial Reciente</h3>
                    <ul class="space-y-3 overflow-y-auto max-h-64 pr-2">
                        @if(isset($logs))
                            @foreach($logs as $log)
                                <li class="text-sm border-b pb-2 hover:bg-gray-50 p-1 rounded">
                                    <span class="font-bold text-gray-700">{{ $log->actor }}</span>
                                    <span class="text-gray-500">{{ $log->accion }}</span>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                </li>
                            @endforeach
                        @else
                            <li class="text-gray-500">No hay actividad reciente.</li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>