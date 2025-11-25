<div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span>👨‍🏫</span> Registrar Nuevo Profesor
    </h3>
    
    <form method="POST" action="{{ route('director.profesor.store') }}" class="space-y-3">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre del Docente</label>
            <input type="text" name="name" placeholder="Ej: Juan Pérez" required 
                   class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Correo Institucional</label>
            <input type="email" name="email" placeholder="profe@vagoschool.com" required 
                   class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contraseña Inicial</label>
            <input type="password" name="password" placeholder="********" required 
                   class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition font-bold">
            Registrar Docente
        </button>
    </form>
</div>