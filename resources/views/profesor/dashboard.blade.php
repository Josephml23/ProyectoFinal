<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sala de Profesores - Vagos School') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Mis Clases Asignadas 📚</h3>
                    <p class="text-sm text-gray-500">Ciclo Escolar 2025</p>
                </div>
            </div>

            <div class="space-y-4">
                
                <div class="bg-white flex flex-col md:flex-row justify-between items-center p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-orange-500">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Matemáticas Avanzadas</h4>
                        <p class="text-gray-500">5to Grado de Secundaria - Sección A</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-3">
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Lista</button>
                        <button class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Subir Notas</button>
                    </div>
                </div>

                <div class="bg-white flex flex-col md:flex-row justify-between items-center p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-teal-500">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Historia Universal</h4>
                        <p class="text-gray-500">4to Grado de Secundaria - Sección B</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-3">
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Lista</button>
                        <button class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600">Subir Notas</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>