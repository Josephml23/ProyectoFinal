<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de: {{ $profesor->name }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-indigo-600 mb-4">📅 Gestión de Horarios</h3>
                
                <form action="{{ route('director.schedule.store', $profesor->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6 bg-gray-50 p-4 rounded border">
                    @csrf
                    <select name="dia" class="rounded border-gray-300 p-2">
                        <option>Lunes</option><option>Martes</option><option>Miércoles</option><option>Jueves</option><option>Viernes</option><option>Sábado</option><option>Domingo</option>
                    </select>
                    <input type="text" name="salon" placeholder="Salón (Ej: A-101)" required class="rounded border-gray-300 p-2">
                    <input type="text" name="ciclo" placeholder="Ciclo (Ej: III)" required class="rounded border-gray-300 p-2">
                    <input type="time" name="hora_inicio" required class="rounded border-gray-300 p-2">
                    <input type="time" name="hora_fin" required class="rounded border-gray-300 p-2">
                    <button type="submit" class="bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold p-2">Agregar</button>
                </form>

                <div class="mt-8 overflow-x-auto">
                    <h4 class="text-lg font-bold text-gray-700 mb-2">Vista Gráfica (Intervalos de 30 min)</h4>
                    
                    <div class="min-w-[800px] border rounded-lg overflow-hidden shadow">
                        <table class="w-full border-collapse bg-white text-xs">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="p-2 border border-blue-500 w-16">Hora</th>
                                    @foreach($dias_semana as $dia)
                                        <th class="p-2 border border-blue-500 w-32 uppercase">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($bloques_horarios as $hora)
                                    <tr>
                                        <td class="p-1 border border-gray-200 bg-gray-50 font-bold text-gray-600 text-center align-middle h-12">
                                            {{ $hora }}
                                        </td>

                                        @foreach($dias_semana as $dia)
                                            <td class="p-0 border border-gray-200 relative h-12 align-top transition hover:bg-gray-50">
                                                
                                                @foreach($horarios as $clase)
                                                    @php
                                                        // TRUCO: Tomamos solo los primeros 5 caracteres (Ej: "07:30")
                                                        // Así comparamos TEXTO con TEXTO y no falla.
                                                        $inicio_clase = substr($clase->hora_inicio, 0, 5);
                                                    @endphp

                                                    {{-- Comparamos si el día coincide Y si la hora (07:30) es idéntica --}}
                                                    @if($clase->dia == $dia && $inicio_clase == $hora)
                                                        <div class="absolute top-0 left-0 w-full z-10 p-0.5">
                                                            <div class="rounded p-1 shadow border-l-4 opacity-90 hover:opacity-100 cursor-pointer
                                                                @if(in_array($clase->ciclo, ['I','1'])) bg-blue-100 border-blue-500 text-blue-900
                                                                @elseif(in_array($clase->ciclo, ['II','2'])) bg-green-100 border-green-500 text-green-900
                                                                @elseif(in_array($clase->ciclo, ['III','3'])) bg-yellow-100 border-yellow-500 text-yellow-900
                                                                @else bg-purple-100 border-purple-500 text-purple-900
                                                                @endif
                                                            ">
                                                                <div class="font-bold text-[10px] leading-tight">
                                                                    {{ substr($clase->hora_inicio,0,5) }} - {{ substr($clase->hora_fin,0,5) }}
                                                                </div>
                                                                <div class="text-[9px] font-bold truncate">{{ $clase->salon }}</div>
                                                                <div class="text-[9px]">{{ $clase->ciclo }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-green-700 mb-4">🎓 Capacitaciones</h3>
                
                <form action="{{ route('director.training.store', $profesor->id) }}" method="POST" class="flex gap-4 mb-4">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre del curso/taller" required class="w-full rounded border-gray-300 p-2 border">
                    <input type="date" name="fecha" required class="rounded border-gray-300 p-2 border">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold">Asignar</button>
                </form>

                <ul class="list-disc pl-5 space-y-2">
                    @foreach($capacitaciones as $c)
                        <li class="text-gray-700">
                            <strong>{{ $c->nombre }}</strong> <span class="text-gray-500 text-sm">({{ $c->fecha }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>