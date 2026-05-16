<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriajeController extends Controller
{
    public function ver($id)
    {
        $paciente = DB::table('pacientes')->where('id', $id)->first();
        $triaje = DB::table('triajes')
            ->where('paciente_id', $id)
            ->first();

        return view('triaje', compact('paciente', 'triaje'));
    }

    public function guardar(Request $request)
    {
        try {
            // Paciente fijo
            $paciente_id = $request->paciente_id;
            $triajeExistente = DB::table('triajes')
                ->where('paciente_id', $paciente_id)
                ->first();

            DB::table('triajes')->updateOrInsert(['paciente_id' => $paciente_id], [
                'usuario_id' => session('usuario_id'),

                'hora_triaje' => $triajeExistente && $triajeExistente->hora_triaje
                    ? $triajeExistente->hora_triaje
                    : (
                        $request->categoria && $request->flujo
                        ? now()
                        : null
                    ),

                'tension_sistolica' => $request->tension_sistolica ?: null,
                'tension_diastolica' => $request->tension_diastolica ?: null,
                'frecuencia_cardiaca' => $request->frecuencia_cardiaca ?: null,
                'frecuencia_respiratoria' => $request->frecuencia_respiratoria ?: null,
                'temperatura' => $request->temperatura ?: null,
                'saturacion_oxigeno' => $request->saturacion_oxigeno ?: null,
                'glasgow' => $request->glasgow ?: null,
                'eva' => $request->eva ?: null,
                'glucemia' => $request->glucemia ?: null,

                'vomitos' => $request->has('vomitos') ? 1 : 0,
                'deposiciones' => $request->has('deposiciones') ? 1 : 0,
                'diuresis' => $request->has('diuresis') ? 1 : 0,

                'peso' => $request->peso ?: null,
                'talla' => $request->talla ?: null,

                'categoria' => $request->categoria,
                'flujo' => $request->flujo,
            ]);

            return redirect('/seguimiento/paciente/' . $paciente_id);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}