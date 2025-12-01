<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Training;
use App\Models\SystemLog;
use App\Models\Classroom;
use App\Models\Cycle;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DirectorController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $profesores_busqueda = collect();

        $todos_profesores = User::where('role', 'profesor')->orderBy('name')->get();

        if ($query) {
            $profesores_busqueda = User::where('role', 'profesor')
                                 ->where('name', 'LIKE', "%{$query}%")
                                 ->get();
        }

        $logs = SystemLog::latest()->take(10)->get(); 
        
        $salones = Classroom::all();
        $ciclos = Cycle::all();
        $cursos = Course::all();

        return view('director.dashboard', compact(
            'profesores_busqueda', 'logs', 'salones', 'ciclos',
            'todos_profesores', 'cursos'
        ));
    }

    public function storeCourse(Request $request) {
        $request->validate([
            'codigo' => 'required|unique:courses',
            'nombre' => 'required',
        ]);
        
        Course::create(['codigo' => $request->codigo, 'nombre' => $request->nombre]);
        
        $this->logAction('Crear Curso', 'Se agregó el curso: ' . $request->codigo);

        return back()->with('success', 'Curso agregado.');
    }

    public function destroyCourse($id) {
        $course = Course::findOrFail($id);
        $codigo = $course->codigo;
        $course->delete();

        $this->logAction('Eliminar Curso', 'Se eliminó el curso: ' . $codigo);

        return back()->with('success', 'Curso eliminado.');
    }
    
    public function storeClassroom(Request $request) {
        $request->validate(['name' => 'required|unique:classrooms']);
        Classroom::create(['name' => $request->name]);
        $this->logAction('Crear Salón', 'Se agregó el salón: ' . $request->name);
        return back()->with('success', 'Salón agregado.');
    }

    public function destroyClassroom($id) {
        $classroom = Classroom::findOrFail($id);
        $nombre = $classroom->name;
        $classroom->delete();
        $this->logAction('Eliminar Salón', 'Se eliminó el salón: ' . $nombre);
        return back()->with('success', 'Salón eliminado.');
    }

    public function storeCycle(Request $request) {
        $request->validate(['name' => 'required|unique:cycles']);
        Cycle::create(['name' => $request->name]);
        $this->logAction('Crear Ciclo', 'Se agregó el ciclo: ' . $request->name);
        return back()->with('success', 'Ciclo agregado.');
    }

    public function destroyCycle($id) {
        $cycle = Cycle::findOrFail($id);
        $nombre = $cycle->name;
        $cycle->delete();
        $this->logAction('Eliminar Ciclo', 'Se eliminó el ciclo: ' . $nombre);
        return back()->with('success', 'Ciclo eliminado.');
    }

    public function storeProfesor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', 
            'password' => 'required|string|min:8'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor',
            'email_verified_at' => now(),
        ]);

        $this->logAction('Registrar Profesor', 'Se registró al docente ' . $user->name);

        return back()->with('success', 'Nuevo Profesor registrado exitosamente.');
    }

    /**
     * Muestra la vista de detalle de un profesor.
     * Utiliza Inyección Implícita de Modelo (User $profesor).
     */
    public function showProfesor(User $profesor)
    {
        // $profesor ya es el objeto User, no se necesita findOrFail($id)
        $horarios = $profesor->schedules; 
        $capacitaciones = $profesor->trainings;

        $salones = Classroom::pluck('name'); 
        $ciclos = Cycle::pluck('name');
        $cursos = Course::all(); 
        
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        $horas_opciones = [];
        for ($i = 7; $i < 22; $i++) {
            $hora = str_pad($i, 2, '0', STR_PAD_LEFT);
            $horas_opciones[] = $hora . ':00';
            if ($i < 22) $horas_opciones[] = $hora . ':30';
        }

        $bloques_horarios = array_filter($horas_opciones, fn($h) => $h < '22:00');

        return view('director.profesor_detalle', compact(
            'profesor', 'horarios', 'capacitaciones',
            'bloques_horarios', 'horas_opciones', 
            'dias_semana', 'salones', 'ciclos', 'cursos'
        ));
    }

    /**
     * Almacena un nuevo horario.
     * Utiliza Inyección Implícita de Modelo (User $profesor).
     */
    public function storeSchedule(Request $request, User $profesor)
    {
        if ($request->hora_inicio >= $request->hora_fin) {
            return back()->withErrors(['error' => 'La hora de fin debe ser posterior a la de inicio.']);
        }

        $cruce = Schedule::where('user_id', $profesor->id) // USAMOS $profesor->id
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
            'user_id' => $profesor->id, // USAMOS $profesor->id
            'course_codigo' => $request->course_codigo,
            'salon' => $request->salon,
            'ciclo' => $request->ciclo,
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        $this->logAction('Asignar Horario', "Curso {$request->course_codigo} al profesor {$profesor->name}");

        return back()->with('success', 'Horario agregado correctamente.');
    }

    public function destroySchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return back()->with('success', 'Horario eliminado.');
    }

    /**
     * Implementación del método storeTraining (solución al error Field 'user_id' doesn't have a default value).
     * Utiliza Inyección Implícita de Modelo (User $profesor).
     */
    public function storeTraining(Request $request, User $profesor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        Training::create([
            'user_id' => $profesor->id, // USAMOS $profesor->id del objeto inyectado
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
        ]);

        $this->logAction('Asignar Capacitación', "Se asignó la capacitación '{$request->nombre}' a {$profesor->name}.");

        return back()->with('success', 'Capacitación asignada correctamente.');
    }
    
    /**
     * Elimina un profesor.
     * Utiliza Inyección Implícita de Modelo (User $profesor).
     */
    public function destroyProfesor(User $profesor)
    {
        if ($profesor->role !== 'profesor') {
            return back()->withErrors(['error' => 'No puedes eliminar a un administrador.']);
        }

        $nombre = $profesor->name;
        $profesor->delete();

        $this->logAction('Eliminar Profesor', 'Se dio de baja al docente: ' . $nombre);

        return redirect()->route('director.dashboard')->with('success', 'Profesor eliminado correctamente.');
    } 

    public function updateTraining(Request $request, $id)
    {
        $training = Training::findOrFail($id);
        
        $training->update([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
        ]);

        $this->logAction('Actualizar Capacitación', "Se editó la capacitación ID: $id");

        return back()->with('success', 'Capacitación actualizada correctamente.');
    }

    public function destroyTraining($id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return back()->with('success', 'Capacitación eliminada.');
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