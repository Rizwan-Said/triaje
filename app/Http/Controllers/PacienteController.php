<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function show($id)
    {
        $paciente = DB::table('pacientes')
            ->where('id', $id)
            ->first();

        $triaje = DB::table('triajes')
            ->where('paciente_id', $id)
            ->first();

        $atencion = DB::table('atenciones')
            ->where('paciente_id', $id)
            ->first();

        if (!$paciente) {
            return redirect('/')->with('error', 'Paciente no encontrado');
        }

        return view('detalle_paciente', compact('paciente', 'triaje', 'atencion'));
    }
}