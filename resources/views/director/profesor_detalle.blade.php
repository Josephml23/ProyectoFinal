<!-- Carga de estilos de emergencia -->
<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de: {{ $profesor->name }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- MOSTRAR ERRORES (CRUCES DE HORARIO, ETC) -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- MOSTRAR ÉXITO -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow">
                    <strong class="font-bold">Éxito:</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-indigo-600 mb-4">📅 Gestión de Horarios</h3>
                
                <!-- FORMULARIO DE AGREGAR HORARIO -->
                <form action="{{ route('director.schedule.store', $profesor->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6 bg-gray-50 p-4 rounded border">
                    @csrf
                    
                    <!-- DÍA -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Día</label>
                        <select name="dia" class="w-full rounded border-gray-300 p-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($dias_semana as $d) <option>{{ $d }}</option> @endforeach
                        </select>
                    </div>

                    <!-- SALÓN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Salón</label>
                        <select name="salon" class="w-full rounded border-gray-300 p-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($salones as $salon) <option value="{{ $salon }}">{{ $salon }}</option> @endforeach
                        </select>
                    </div>

                    <!-- CICLO -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Ciclo</label>
                        <select name="ciclo" class="w-full rounded border-gray-300 p-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($ciclos as $ciclo) <option value="{{ $ciclo }}">{{ $ciclo }}</option> @endforeach
                        </select>
                    </div>

                    <!-- HORA INICIO -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Inicio</label>
                        <select name="hora_inicio" class="w-full rounded border-gray-300 p-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                        </select>
                    </div>

                    <!-- HORA FIN -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Fin</label>
                        <select name="hora_fin" class="w-full rounded border-gray-300 p-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($horas_opciones as $h) <option value="{{ $h }}">{{ $h }}</option> @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold p-2 text-sm shadow transition">
                            + Agregar
                        </button>
                    </div>
                </form>

                <!-- TABLA VISUAL DE HORARIOS -->
                <div class="mt-8 overflow-x-auto">
                    <h4 class="text-lg font-bold text-gray-700 mb-2">Vista Gráfica Semanal</h4>
                    
                    <div class="min-w-[800px] border rounded-lg overflow-hidden shadow">
                        <table class="w-full border-collapse bg-white text-xs table-fixed">
                            <thead>
                                <tr class="bg-blue-600 text-white h-10">
                                    <th class="border border-blue-500 w-20">Hora</th>
                                    @foreach($dias_semana as $dia)
                                        <th class="border border-blue-500 uppercase">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($bloques_horarios as $hora_bloque)
                                    <!-- Altura fija h-12 (48px) para mantener proporción -->
                                    <tr class="h-12">
                                        
                                        <!-- COLUMNA DE LA HORA -->
                                        <td class="border border-gray-200 bg-gray-50 font-bold text-gray-600 text-center align-middle">
                                            {{ $hora_bloque }}
                                        </td>

                                        <!-- CELDAS DE LOS DÍAS -->
                                        @foreach($dias_semana as $dia)
                                            @php
                                                // Convertimos todo a SEGUNDOS para cálculos exactos
                                                $base_fecha = "2024-01-01 "; // Fecha dummy para usar strtotime
                                                $t_bloque = strtotime($base_fecha . $hora_bloque);
                                                
                                                $clase_encontrada = null;
                                                $es_inicio_bloque = false;
                                                $celda_ocupada = false;
                                                $filas_a_ocupar = 1;

                                                foreach($horarios as $clase) {
                                                    // Filtramos por día primero
                                                    if(trim($clase->dia) != $dia) continue;

                                                    $t_inicio = strtotime($base_fecha . $clase->hora_inicio);
                                                    $t_fin = strtotime($base_fecha . $clase->hora_fin);
                                                    
                                                    // CASO 1: LA CLASE EMPIEZA AQUÍ (Margen de error de 1 min por si acaso)
                                                    if (abs($t_inicio - $t_bloque) < 60) {
                                                        $clase_encontrada = $clase;
                                                        $es_inicio_bloque = true;
                                                        
                                                        // Calculamos la duración: (Fin - Inicio) / 1800 seg (30 min)
                                                        $duracion_segundos = $t_fin - $t_inicio;
                                                        $filas_a_ocupar = ceil($duracion_segundos / 1800); 
                                                        break;
                                                    }
                                                    
                                                    // CASO 2: ESTA HORA ESTÁ DENTRO DE UNA CLASE YA INICIADA
                                                    if ($t_bloque > $t_inicio && $t_bloque < $t_fin) {
                                                        $celda_ocupada = true;
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            {{-- RENDERIZADO DE LA CELDA --}}
                                            
                                            @if($es_inicio_bloque)
                                                <!-- DIBUJAMOS EL BLOQUE MAESTRO (usa rowspan) -->
                                                <td rowspan="{{ $filas_a_ocupar }}" class="p-0 border border-gray-300 relative align-top z-10 bg-white">
                                                    <!-- Contenedor absoluto para llenar 100% de la celda fusionada -->
                                                    <div class="absolute inset-0 p-0.5">
                                                        <div class="h-full w-full rounded shadow-sm border-l-4 flex flex-col justify-center items-center relative group overflow-hidden transition hover:brightness-95
                                                            @if(in_array($clase_encontrada->ciclo, ['I','1'])) bg-blue-100 border-blue-500 text-blue-900
                                                            @elseif(in_array($clase_encontrada->ciclo, ['II','2'])) bg-green-100 border-green-500 text-green-900
                                                            @elseif(in_array($clase_encontrada->ciclo, ['III','3'])) bg-yellow-100 border-yellow-500 text-yellow-900
                                                            @elseif(in_array($clase_encontrada->ciclo, ['IV','4'])) bg-orange-100 border-orange-500 text-orange-900
                                                            @else bg-purple-100 border-purple-500 text-purple-900
                                                            @endif
                                                        ">
                                                            <!-- INFO VISIBLE -->
                                                            <div class="font-bold text-sm text-center leading-tight">
                                                                {{ date('H:i', strtotime($clase_encontrada->hora_inicio)) }} - {{ date('H:i', strtotime($clase_encontrada->hora_fin)) }}
                                                            </div>
                                                            <div class="font-bold text-xs text-center uppercase mt-1 px-1 truncate w-full">{{ $clase_encontrada->salon }}</div>
                                                            <div class="text-[10px] text-center opacity-80">Ciclo: {{ $clase_encontrada->ciclo }}</div>

                                                            <!-- BOTÓN ELIMINAR (Solo visible al pasar el mouse) -->
                                                            <form action="{{ route('director.schedule.destroy', $clase_encontrada->id) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition duration-200">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs hover:bg-red-700 shadow-md" onclick="return confirm('¿Estás seguro de eliminar este horario?');" title="Eliminar clase">
                                                                    ✕
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            
                                            @elseif($celda_ocupada)
                                                <!-- CELDA OCUPADA: No dibujamos nada porque el rowspan de arriba la cubre -->
                                            
                                            @else
                                                <!-- CELDA VACÍA -->
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

            <!-- SECCIÓN CAPACITACIONES -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-green-700 mb-4">🎓 Capacitaciones</h3>
                
                <form action="{{ route('director.training.store', $profesor->id) }}" method="POST" class="flex gap-4 mb-4">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre del curso/taller" required class="w-full rounded border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500">
                    <input type="date" name="fecha" required class="rounded border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold shadow transition">Asignar</button>
                </form>

                <ul class="list-disc pl-5 space-y-2">
                    @forelse($capacitaciones as $c)
                        <li class="text-gray-700">
                            <strong>{{ $c->nombre }}</strong> <span class="text-gray-500 text-sm">({{ $c->fecha }})</span>
                        </li>
                    @empty
                        <li class="text-gray-400 italic text-sm">No hay capacitaciones asignadas.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>