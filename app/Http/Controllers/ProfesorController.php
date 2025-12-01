<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Training;
use App\Models\User;
use App\Models\Course; // Para mostrar el nombre del curso
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfesorController extends Controller
{
    /**
     * Muestra el panel principal del profesor con su horario y capacitaciones.
     */
    public function index()
    {
        // Obtenemos el usuario (profesor) actualmente autenticado
        $profesor = Auth::user();

        // Carga los datos relacionados
        $horarios = $profesor->schedules;
        $capacitaciones = $profesor->trainings;

        // Necesario para la vista de horario
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Generar bloques de 30 min (07:00 a 21:30)
        $bloques_horarios = [];
        for ($i = 7; $i < 22; $i++) {
            $hora = str_pad($i, 2, '0', STR_PAD_LEFT);
            $bloques_horarios[] = $hora . ':00';
            if ($i < 22) $bloques_horarios[] = $hora . ':30';
        }
        $bloques_horarios = array_filter($bloques_horarios, fn($h) => $h < '22:00');
        
        // Cargar todos los cursos para obtener el nombre completo en la vista
        $cursos = Course::pluck('nombre', 'codigo')->toArray();

        return view('profesor.dashboard', compact(
            'profesor',
            'horarios',
            'capacitaciones',
            'bloques_horarios',
            'dias_semana',
            'cursos'
        ));
    }

    /**
     * Permite al profesor actualizar su contraseña.
     */
    public function updatePassword(Request $request)
    {
        // El validador 'confirmed' buscará automáticamente 'new_password_confirmation'
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [], ['new_password' => 'Nueva Contraseña', 'current_password' => 'Contraseña Actual']);

        $user = Auth::user();

        // 1. Verificar si la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            // Usamos 'withErrors' para que Blade pueda mostrar el error en @error('current_password', 'updatePassword')
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'], 'updatePassword');
        }

        // 2. Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}