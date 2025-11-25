<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administración - Vagos School') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Buscar Profesor</h3>
                <form method="GET" action="{{ route('director.dashboard') }}" class="flex gap-4">
                    <input type="text" name="search" placeholder="Escribe el nombre del profesor..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold">Buscar</button>
                </form>

                @if(isset($profesores) && count($profesores) > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($profesores as $profe)
                            <a href="{{ route('director.profesor.show', $profe->id) }}" class="block p-4 border border-indigo-200 rounded bg-indigo-50 hover:bg-indigo-100 transition">
                                <div class="font-bold text-indigo-700">{{ $profe->name }}</div>
                                <div class="text-sm text-gray-600">{{ $profe->email }}</div>
                            </a>
                        @endforeach
                    </div>
                @elseif(request('search'))
                    <p class="mt-4 text-red-500">No se encontraron profesores.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>👨‍🏫</span> Registrar Nuevo Profesor
                    </h3>
                    
                    <form method="POST" action="{{ route('director.profesor.store') }}" class="space-y-3">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Docente</label>
                            <input type="text" name="name" placeholder="Ej: Juan Pérez" required 
                                   class="w-full rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Correo Institucional</label>
                            <input type="email" name="email" placeholder="profe@vagoschool.com" required 
                                   class="w-full rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contraseña Inicial</label>
                            <input type="password" name="password" placeholder="********" required 
                                   class="w-full rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 p-2">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-bold shadow">
                            Registrar Docente
                        </button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📜 Historial Reciente</h3>
                    <ul class="space-y-3">
                        @if(isset($logs))
                            @foreach($logs as $log)
                                <li class="text-sm border-b pb-2">
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