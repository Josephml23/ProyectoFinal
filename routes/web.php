<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ProfesorController;

Route::get('/', function () {
    return view('welcome');
});

// RUTA DASHBOARD INTELIGENTE
// Cuando entran a /dashboard, Laravel decide a dónde mandarlos según su rol
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'director') {
        return redirect()->route('director.dashboard');
    } 
    
    return redirect()->route('profesor.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- ZONA DEL DIRECTOR ---
Route::middleware(['auth', 'role:director'])->group(function () {
    Route::get('/director/panel', [DirectorController::class, 'index'])->name('director.dashboard');
});

// --- ZONA DEL PROFESOR ---
Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::get('/profesor/panel', [ProfesorController::class, 'index'])->name('profesor.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';