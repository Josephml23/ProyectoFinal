<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Training;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DirectorController extends Controller
{
    // 1. Dashboard Principal y Buscador
    public function index(Request $request)
    {
        $query = $request->input('search');
        $profesores = collect();

        if ($query) {
            $profesores = User::where('role', 'profesor')
                              ->where('name', 'LIKE', "%{$query}%")
                              ->get();
        }

        $logs = SystemLog::latest()->take(10)->get(); // Últimos 10 movimientos

        return view('director.dashboard', compact('profesores', 'logs'));
    }

    // 2. Crear nuevo profesor
    public function storeProfesor(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        
        // Aquí está el cambio clave: 'role' => 'profesor'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor' 
        ]);

        // Guardamos en el historial
        $this->logAction('Registrar Profesor', 'Se registró al docente ' . $request->name);

        return back()->with('success', 'Nuevo Profesor registrado exitosamente.');
    }
    public function showProfesor($id)
    {
        $profesor = User::findOrFail($id);
        $horarios = $profesor->schedules; 
        $capacitaciones = $profesor->trainings;

        // Definimos las horas para la tabla (de 7:00 a 22:00)
        $bloques_horarios = [];
        for ($i = 7; $i <= 22; $i++) {
            // Formato 07:00, 08:00, etc.
            $bloques_horarios[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }

        // Definimos los días para la cabecera
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        return view('director.profesor_detalle', compact('profesor', 'horarios', 'capacitaciones', 'bloques_horarios', 'dias_semana'));
    }

    // 4. Agregar Horario
    public function storeSchedule(Request $request, $profesor_id)
    {
        Schedule::create([
            'user_id' => $profesor_id,
            'salon' => $request->salon,
            'ciclo' => $request->ciclo,
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        $this->logAction('Asignar Horario', "Aula $request->salon al profesor ID: $profesor_id");
        return back()->with('success', 'Horario agregado');
    }

    // 5. Agregar Capacitación
    public function storeTraining(Request $request, $profesor_id)
    {
        Training::create([
            'user_id' => $profesor_id,
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
        ]);

        $this->logAction('Asignar Capacitación', "$request->nombre al profesor ID: $profesor_id");
        return back()->with('success', 'Capacitación agregada');
    }

    // Función auxiliar para guardar historial
    private function logAction($accion, $detalle)
    {
        SystemLog::create([
            'actor' => Auth::user()->name,
            'accion' => $accion,
            'detalle' => $detalle
        ]);
    }
}