<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::get('/director/profesor/{id}', [DirectorController::class, 'showProfesor'])->name('director.profesor.show');

    // 3. Gestión de Horarios y Capacitaciones (Profesor específico)
    Route::post('/director/profesor/{id}/horario', [DirectorController::class, 'storeSchedule'])->name('director.schedule.store');
    Route::post('/director/profesor/{id}/capacitacion', [DirectorController::class, 'storeTraining'])->name('director.training.store');
    Route::delete('/director/horario/{id}', [DirectorController::class, 'destroySchedule'])->name('director.schedule.destroy');

    // 4. GESTIÓN DE CATÁLOGOS (SALONES Y CICLOS) - ¡NUEVO! 👇
    // Salones
    Route::post('/director/salones', [DirectorController::class, 'storeClassroom'])->name('director.classrooms.store');
    Route::delete('/director/salones/{id}', [DirectorController::class, 'destroyClassroom'])->name('director.classrooms.destroy');

    // Ciclos
    Route::post('/director/ciclos', [DirectorController::class, 'storeCycle'])->name('director.cycles.store');
    Route::delete('/director/ciclos/{id}', [DirectorController::class, 'destroyCycle'])->name('director.cycles.destroy');

});


// --- ZONA DEL PROFESOR ---
Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::get('/profesor/panel', [ProfesorController::class, 'index'])->name('profesor.dashboard');
});

require __DIR__.'/auth.php';