<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Training;
use App\Models\SystemLog;
use App\Models\Classroom; // <--- Modelo Salones
use App\Models\Cycle;     // <--- Modelo Ciclos
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DirectorController extends Controller
{
    // 1. Dashboard Principal
    public function index(Request $request)
    {
        $query = $request->input('search');
        $profesores = collect();

        if ($query) {
            $profesores = User::where('role', 'profesor')
                              ->where('name', 'LIKE', "%{$query}%")
                              ->get();
        }

        $logs = SystemLog::latest()->take(10)->get(); 
        
        // OBTENEMOS LOS CATÁLOGOS DE LA BASE DE DATOS
        $salones = Classroom::all();
        $ciclos = Cycle::all();

        return view('director.dashboard', compact('profesores', 'logs', 'salones', 'ciclos'));
    }

    // --- GESTIÓN DE SALONES ---
    public function storeClassroom(Request $request) {
        $request->validate(['name' => 'required|unique:classrooms']);
        Classroom::create(['name' => $request->name]);
        return back()->with('success', 'Salón agregado.');
    }

    public function destroyClassroom($id) {
        Classroom::destroy($id);
        return back()->with('success', 'Salón eliminado.');
    }

    // --- GESTIÓN DE CICLOS ---
    public function storeCycle(Request $request) {
        $request->validate(['name' => 'required|unique:cycles']);
        Cycle::create(['name' => $request->name]);
        return back()->with('success', 'Ciclo agregado.');
    }

    public function destroyCycle($id) {
        Cycle::destroy($id);
        return back()->with('success', 'Ciclo eliminado.');
    }

    // 2. Crear Profesor
    public function storeProfesor(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor' 
        ]);

        $this->logAction('Registrar Profesor', 'Se registró al docente ' . $request->name);
        return back()->with('success', 'Nuevo Profesor registrado.');
    }

    // 3. Ver Perfil (AHORA USA LA BASE DE DATOS)
    public function showProfesor($id)
    {
        $profesor = User::findOrFail($id);
        $horarios = $profesor->schedules; 
        $capacitaciones = $profesor->trainings;

        // OBTENEMOS SOLO LOS NOMBRES DE LA BD PARA LOS SELECTS
        $salones = Classroom::pluck('name'); 
        $ciclos = Cycle::pluck('name');
        
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Generar horas de 30 en 30 min
        $horas_opciones = [];
        for ($i = 7; $i < 22; $i++) {
            $hora = str_pad($i, 2, '0', STR_PAD_LEFT);
            $horas_opciones[] = $hora . ':00';
            if($i < 22) $horas_opciones[] = $hora . ':30';
        }

        $bloques_horarios = array_filter($horas_opciones, function($h) {
            return $h < '22:00'; 
        });

        return view('director.profesor_detalle', compact(
            'profesor', 'horarios', 'capacitaciones', 
            'bloques_horarios', 'horas_opciones', 
            'dias_semana', 'salones', 'ciclos'
        ));
    }

    // 4. Agregar Horario
    public function storeSchedule(Request $request, $profesor_id)
    {
        if ($request->hora_inicio >= $request->hora_fin) {
            return back()->withErrors(['error' => 'La hora de fin debe ser posterior a la de inicio.']);
        }

        $cruce = Schedule::where('user_id', $profesor_id)
            ->where('dia', $request->dia)
            ->where(function ($query) use ($request) {
                $query->where('hora_inicio', '<', $request->hora_fin)
                      ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($cruce) {
            return back()->withErrors(['error' => '¡Conflicto de Horario! Ya existe una clase asignada en ese rango.']);
        }

        Schedule::create([
            'user_id' => $profesor_id,
            'salon' => $request->salon,
            'ciclo' => $request->ciclo,
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        return back()->with('success', 'Horario agregado correctamente');
    }

    public function storeTraining(Request $request, $profesor_id)
    {
        Training::create([
            'user_id' => $profesor_id,
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
        ]);
        return back()->with('success', 'Capacitación agregada');
    }

    public function destroySchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return back()->with('success', 'Horario eliminado.');
    }

    private function logAction($accion, $detalle)
    {
        SystemLog::create([
            'actor' => Auth::user()->name,
            'accion' => $accion,
            'detalle' => $detalle
        ]);
    }
}