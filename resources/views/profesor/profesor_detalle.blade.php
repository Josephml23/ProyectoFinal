<div class="mt-8 overflow-x-auto">
    <h4 class="text-lg font-bold text-gray-700 mb-2">Vista Gráfica Semanal</h4>
    
    <div class="min-w-[800px] border rounded-lg overflow-hidden">
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
                        <td class="p-2 border border-gray-200 bg-gray-50 font-bold text-gray-600 text-center align-top h-24">
                            {{ $hora }}
                        </td>

                        @foreach($dias_semana as $dia)
                            <td class="p-1 border border-gray-200 relative h-24 align-top transition hover:bg-gray-50">
                                
                                {{-- LÓGICA: Buscar si hay una clase en este día y hora --}}
                                @foreach($horarios as $clase)
                                    @php
                                        // Convertimos "08:00:00" a "08" para comparar
                                        $hora_clase = substr($clase->hora_inicio, 0, 2); 
                                        $hora_actual = substr($hora, 0, 2);
                                    @endphp

                                    {{-- Si el día coincide Y la hora de inicio coincide --}}
                                    @if($clase->dia === $dia && $hora_clase === $hora_actual)
                                        
                                        {{-- DIBUJAR EL BLOQUE DE CLASE --}}
                                        <div class="absolute top-0 left-0 w-full p-1 z-10">
                                            <div class="rounded p-2 shadow-sm border-l-4 
                                                {{-- Colores aleatorios según el ciclo --}}
                                                @if($clase->ciclo == 'I' || $clase->ciclo == '1') bg-blue-100 border-blue-500 text-blue-800
                                                @elseif($clase->ciclo == 'II' || $clase->ciclo == '2') bg-green-100 border-green-500 text-green-800
                                                @elseif($clase->ciclo == 'III' || $clase->ciclo == '3') bg-yellow-100 border-yellow-500 text-yellow-800
                                                @elseif($clase->ciclo == 'IV' || $clase->ciclo == '4') bg-pink-100 border-pink-500 text-pink-800
                                                @else bg-indigo-100 border-indigo-500 text-indigo-800
                                                @endif
                                            ">
                                                <div class="font-bold text-[10px] leading-tight">{{ substr($clase->hora_inicio,0,5) }} - {{ substr($clase->hora_fin,0,5) }}</div>
                                                <div class="font-bold text-xs mt-1">{{ $clase->salon }}</div>
                                                <div class="text-[10px] opacity-80">Ciclo: {{ $clase->ciclo }}</div>
                                                
                                                {{-- <form action="..." method="POST" class="mt-1 text-right">
                                                    <button class="text-red-500 hover:text-red-700 text-[9px] underline">x</button>
                                                </form> --}}
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