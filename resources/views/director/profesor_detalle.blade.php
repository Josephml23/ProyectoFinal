<!-- Carga de estilos de emergencia -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Alpine.js ya no es crítico para las capacitaciones, pero lo dejamos por si otras funciones lo necesitan -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js" defer></script> 

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de: {{ $profesor->name }}
            </h2>
            <span class="text-xs font-mono bg-gray-200 text-gray-600 px-2 py-1 rounded">
                {{ $profesor->email }}
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- MOSTRAR ERRORES -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow" role="alert">
                    <p class="font-bold">¡Atención!</p>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- MOSTRAR ÉXITO -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- 1. SECCIÓN DE HORARIOS -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                <h3 class="text-lg font-bold text-indigo-800 mb-4 flex items-center gap-2">
                    <span>📅</span> Gestión de Horarios
                </h3>
                
                <!-- FORMULARIO DE AGREGAR HORARIO -->
                <form action="{{ route('director.schedule.store', $profesor->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8 bg-gray-50 p-4 rounded border border-gray-200">
                    @csrf
                    
                    <!-- DÍA -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Día</label>
                        <select name="dia" class="w-full rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($dias_semana as $d) <option>{{ $d }}</option> @endforeach
                        </select>
                    </div>

                    <!-- SALÓN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Salón</label>
                        <select name="salon" class="w-full rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($salones as $salon) <option value="{{ $salon }}">{{ $salon }}</option> @endforeach
                        </select>
                    </div>

                    <!-- CICLO -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ciclo</label>
                        <select name="ciclo" class="w-full rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($ciclos as $ciclo) <option value="{{ $ciclo }}">{{ $ciclo }}</option> @endforeach
                        </select>
                    </div>

                    <!-- HORA INICIO -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Inicio</label>
                        <select name="hora_inicio" class="w-full rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                        </select>
                    </div>

                    <!-- HORA FIN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Fin</label>
                        <select name="hora_fin" class="w-full rounded border-gray-300 p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold p-2 text-sm shadow transition transform hover:scale-105">
                            + Agregar
                        </button>
                    </div>
                </form>

                <!-- TABLA VISUAL DE HORARIOS -->
                <div class="overflow-x-auto">
                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Vista Gráfica Semanal</h4>
                    
                    <div class="min-w-[900px] border rounded-lg overflow-hidden shadow-sm">
                        <table class="w-full border-collapse bg-white text-xs table-fixed">
                            <thead>
                                <tr class="bg-indigo-600 text-white h-10 text-center">
                                    <th class="border border-indigo-500 w-24">Hora</th>
                                    @foreach($dias_semana as $dia)
                                        <th class="border border-indigo-500 uppercase font-semibold">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($bloques_horarios as $hora_bloque)
                                    <tr class="h-12">
                                        
                                        <!-- COLUMNA DE LA HORA -->
                                        <td class="border border-gray-200 bg-gray-50 font-bold text-gray-500 text-center align-middle">
                                            {{ $hora_bloque }}
                                        </td>

                                        <!-- CELDAS DE LOS DÍAS -->
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
                                                <td rowspan="{{ $filas_a_ocupar }}" class="p-0 border border-gray-300 relative align-top z-10 bg-white">
                                                    <div class="absolute inset-0 p-0.5">
                                                        <div class="h-full w-full rounded shadow-sm border-l-4 flex flex-col justify-center items-center relative group overflow-hidden transition hover:brightness-95 cursor-pointer
                                                            @if(in_array($clase_encontrada->ciclo, ['I','1'])) bg-blue-100 border-blue-500 text-blue-900
                                                            @elseif(in_array($clase_encontrada->ciclo, ['II','2'])) bg-green-100 border-green-500 text-green-900
                                                            @elseif(in_array($clase_encontrada->ciclo, ['III','3'])) bg-yellow-100 border-yellow-500 text-yellow-900
                                                            @else bg-purple-100 border-purple-500 text-purple-900 @endif">
                                                            <div class="font-bold text-sm text-center leading-tight">
                                                                {{ date('H:i', strtotime($clase_encontrada->hora_inicio)) }} - {{ date('H:i', strtotime($clase_encontrada->hora_fin)) }}
                                                            </div>
                                                            <div class="font-extrabold text-xs text-center uppercase mt-1 px-1 truncate w-full">{{ $clase_encontrada->salon }}</div>
                                                            <div class="text-[10px] text-center opacity-80 font-semibold">Ciclo: {{ $clase_encontrada->ciclo }}</div>
                                                            <form action="{{ route('director.schedule.destroy', $clase_encontrada->id) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition duration-200">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs hover:bg-red-700 shadow-md" onclick="return confirm('¿Eliminar horario?');">✕</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            @elseif(!$celda_ocupada)
                                                <td class="border border-gray-200 bg-white p-0"></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. SECCIÓN CAPACITACIONES (SOLO ELIMINAR) -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-green-700 mb-4 flex items-center gap-2">
                    <span>🎓</span> Capacitaciones
                </h3>
                
                <!-- Formulario Agregar -->
                <form action="{{ route('director.training.store', $profesor->id) }}" method="POST" class="flex gap-4 mb-6">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="nombre" placeholder="Nombre del curso o taller..." required class="w-full rounded border-gray-300 p-2 text-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <input type="date" name="fecha" required class="w-full rounded border-gray-300 p-2 text-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700 shadow transition">
                        Asignar
                    </button>
                </form>

                <!-- Lista de Capacitaciones (SOLO LECTURA Y ELIMINAR) -->
                <div class="bg-gray-50 rounded border border-gray-200 p-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Historial de cursos</h4>
                    
                    <ul class="space-y-3">
                        @forelse($capacitaciones as $c)
                            <li class="bg-white p-3 rounded border border-gray-200 shadow-sm hover:shadow-md transition flex justify-between items-center">
                                
                                <!-- DETALLES DE LA CAPACITACIÓN -->
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    <div>
                                        <strong class="text-gray-800 block text-sm">{{ $c->nombre }}</strong>
                                        <span class="text-xs text-gray-500 italic">{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                
                                <!-- BOTÓN ELIMINAR -->
                                <form action="{{ route('director.training.destroy', $c->id) }}" method="POST" class="flex items-center">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase tracking-wide px-3 py-1 rounded border border-red-300 bg-red-50 hover:bg-red-100 transition" onclick="return confirm('¿Borrar esta capacitación?');">
                                        Eliminar
                                    </button>
                                </form>

                            </li>
                        @empty
                            <li class="text-gray-400 italic text-sm text-center py-2">No hay capacitaciones asignadas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- 3. ZONA DE PELIGRO (ELIMINAR PROFESOR) -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-red-200 mt-8">
                <h3 class="text-lg font-bold text-red-600 mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Zona de Peligro
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Esta acción es irreversible. Al eliminar al profesor <strong>{{ $profesor->name }}</strong>, se borrarán permanentemente todos sus horarios asignados, su historial de capacitaciones y su acceso al sistema.
                </p>
                
                <div class="flex justify-end border-t pt-4">
                    <form action="{{ route('director.profesor.destroy', $profesor->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-800 transition shadow flex items-center gap-2 text-sm" onclick="return confirm('¿Estás COMPLETAMENTE SEGURO? Esta acción no se puede deshacer.');">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar Profesor Definitivamente
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>