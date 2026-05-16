<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtencionController extends Controller
{

    public function ver($id)
    {
        // Obtener paciente
        $paciente = DB::table('pacientes')
            ->where('id', $id)
            ->first();

        // Obtener último triaje del paciente
        $triaje = DB::table('triajes')
            ->where('paciente_id', $id)
            ->first();

        $atencion = DB::table('atenciones')
            ->where('paciente_id', $id)
            ->first();

        return view('atencion', compact('paciente', 'triaje', 'atencion'));
    }

    public function guardar(Request $request)
    {
        try {
            $atencionExistente = DB::table('atenciones')
                ->where('paciente_id', $request->paciente_id)
                ->first();

            DB::table('atenciones')->updateOrInsert(
                ['paciente_id' => $request->paciente_id],
                [
                    'anamnesis' => $request->anamnesis !== null
                        ? $request->anamnesis
                        : ($atencionExistente->anamnesis ?? null),

                    'diagnostico_principal' => $request->diagnostico_principal !== null
                        ? $request->diagnostico_principal
                        : ($atencionExistente->diagnostico_principal ?? null),

                    'diagnosticos_secundarios' => $request->diagnosticos_secundarios !== null
                        ? $request->diagnosticos_secundarios
                        : ($atencionExistente->diagnosticos_secundarios ?? null),

                    'tratamiento' => $request->tratamiento !== null
                        ? $request->tratamiento
                        : ($atencionExistente->tratamiento ?? null),

                    'plan_seguimiento' => $request->plan_seguimiento !== null
                        ? $request->plan_seguimiento
                        : ($atencionExistente->plan_seguimiento ?? null),
                ]
            );

            return redirect('/seguimiento/paciente/' . $request->paciente_id);

        } catch (\Exception $e) {
            return back()->with('error', true);
        }
    }

    public function misFeedbacks()
    {
        $alumno_id = session('usuario_id');

        $pacientes = DB::table('pacientes')
            ->join('atenciones', 'pacientes.id', '=', 'atenciones.paciente_id')
            ->where('pacientes.alumno_id', $alumno_id)
            ->whereNotNull('atenciones.feedback')
            ->where('atenciones.feedback', '!=', '')
            ->select('pacientes.*', 'atenciones.feedback')
            ->get();

        return view('mis_feedbacks', compact('pacientes'));
    }

    public function verFeedback($id)
    {
        $paciente = DB::table('pacientes')->where('id', $id)->first();
        $triaje = DB::table('triajes')->where('paciente_id', $id)->first();
        $atencion = DB::table('atenciones')->where('paciente_id', $id)->first();

        return view('ver_feedback', compact('paciente', 'triaje', 'atencion'));
    }
}