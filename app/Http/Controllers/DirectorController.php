<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DirectorController extends Controller
{
    public function index()
    {
        return view('director.dashboard');
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
}