<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administración - Vagos School') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800">¡Bienvenido, Director! 🎓</h3>
                    <p class="text-gray-600">Tiene el control total de <strong>Vagos School</strong>.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-gray-700">Profesores</h4>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Gestión</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">Registrar, editar o eliminar personal docente.</p>
                    <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Administrar</button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-gray-700">Alumnos</h4>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Base de Datos</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">Ver lista de estudiantes y sus estados.</p>
                    <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">Ver Alumnos</button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-gray-700">Reportes</h4>
                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Estadísticas</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4">Rendimiento académico general del colegio.</p>
                    <button class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">Ver Reportes</button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>