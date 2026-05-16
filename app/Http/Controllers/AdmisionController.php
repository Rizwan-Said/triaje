<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmisionController extends Controller
{
    public function ver($id)
    {
        $paciente = DB::table('pacientes')
            ->where('id', $id)
            ->first();

        return view('admision', compact('paciente'));
    }

    public function actualizar(Request $request, $id)
    {
        try {

            DB::table('pacientes')
                ->where('id', $id)
                ->update([
                    'nhc' => $request->nhc,
                    'nombre' => $request->nombre,
                    'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
                    'telefono' => $request->telefono ?: null,
                    'alergias' => $request->alergias ?: null,
                    'motivo_consulta' => $request->motivo_consulta ?: null,
                ]);

            return redirect('/seguimiento/paciente/' . $id);

        } catch (\Exception $e) {
            return back()->with('error', true);
        }
    }
    public function guardar(Request $request)
    {
        try {

            $id = DB::table('pacientes')->insertGetId([
                'nhc' => $request->nhc,
                'nombre' => $request->nombre,
                'edad' => $request->edad ?: null,
                'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
                'telefono' => $request->telefono ?: null,
                'alergias' => $request->alergias ?: null,
                'motivo_consulta' => $request->motivo_consulta ?: null,
                'alumno_id' => session('usuario_id'),
            ]);

            if (session('rol') == 'profesor') {
                return redirect('/panel');
            }

            return redirect('/');


        } catch (\Exception $e) {
            return back()->with('error', true);
        }
    }
}