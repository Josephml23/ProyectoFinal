<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// --- LÓGICA DE REDIRECCIÓN AL ENTRAR ---
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'director') {
        return redirect()->route('director.dashboard');
    } 
    
    return redirect()->route('profesor.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- RUTAS DE PERFIL (PROFILE) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- ZONA DEL DIRECTOR ---
Route::middleware(['auth', 'role:director'])->group(function () {
    
    // 1. Panel Principal
    Route::get('/director/panel', [DirectorController::class, 'index'])->name('director.dashboard');
    
    // 2. Gestión de Profesores
    Route::post('/director/nuevo-profesor', [DirectorController::class, 'storeProfesor'])->name('director.profesor.store');
    
    // NOTA: Se ha cambiado {id} por {profesor} para la inyección implícita de modelo.
    Route::get('/director/profesor/{profesor}', [DirectorController::class, 'showProfesor'])->name('director.profesor.show');
    Route::delete('/director/profesor/{profesor}', [DirectorController::class, 'destroyProfesor'])->name('director.profesor.destroy');

    // 3. Gestión de Horarios y Capacitaciones (Profesor específico)
    // NOTA: Se ha cambiado {id} por {profesor} para la inyección implícita de modelo.
    Route::post('/director/profesor/{profesor}/horario', [DirectorController::class, 'storeSchedule'])->name('director.schedule.store');
    Route::delete('/director/horario/{id}', [DirectorController::class, 'destroySchedule'])->name('director.schedule.destroy');

    Route::post('/director/profesor/{profesor}/capacitacion', [DirectorController::class, 'storeTraining'])->name('director.training.store');
    Route::put('/director/capacitacion/{id}', [DirectorController::class, 'updateTraining'])->name('director.training.update'); 
    Route::delete('/director/capacitacion/{id}', [DirectorController::class, 'destroyTraining'])->name('director.training.destroy');
    
    // 4. GESTIÓN DE CATÁLOGOS (CURSOS, SALONES Y CICLOS)
    
    // Cursos
    Route::post('/director/cursos', [DirectorController::class, 'storeCourse'])->name('director.courses.store');
    Route::delete('/director/cursos/{id}', [DirectorController::class, 'destroyCourse'])->name('director.courses.destroy');

    // Salones
    Route::post('/director/salones', [DirectorController::class, 'storeClassroom'])->name('director.classrooms.store');
    Route::delete('/director/salones/{id}', [DirectorController::class, 'destroyClassroom'])->name('director.classrooms.destroy');

    // Ciclos
    Route::post('/director/ciclos', [DirectorController::class, 'storeCycle'])->name('director.cycles.store');
    Route::delete('/director/ciclos/{id}', [DirectorController::class, 'destroyCycle'])->name('director.cycles.destroy');

});


// --- ZONA DEL PROFESOR ---
Route::middleware(['auth', 'role:profesor'])->group(function () {
    // Panel
    Route::get('/profesor/panel', [ProfesorController::class, 'index'])->name('profesor.dashboard');
    
    // Actualización de Contraseña (Ruta añadida para solucionar el RouteNotFoundException)
    Route::post('/profesor/update-password', [ProfesorController::class, 'updatePassword'])->name('profesor.update.password');
});

require __DIR__.'/auth.php';