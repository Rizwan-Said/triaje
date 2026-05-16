<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        // Todos los usuarios para el desplegable
        $usuarios = DB::table('users')
            ->select('id', 'name', 'email')
            ->get();

        $pacientes = collect(); // collect es un helper de Laravel para manejar colecciones
        $usuarioSeleccionado = null;

        if ($request->has('usuario_id') && $request->usuario_id != '') {
            $usuarioSeleccionado = $request->usuario_id;

            $pacientes = DB::table('pacientes')
                ->leftJoin('triajes', 'pacientes.id', '=', 'triajes.paciente_id')
                ->leftJoin('atenciones', 'pacientes.id', '=', 'atenciones.paciente_id')
                ->where('pacientes.alumno_id', $usuarioSeleccionado)
                ->select(
                    'pacientes.*',
                    'triajes.categoria',
                    'triajes.hora_triaje',
                    'atenciones.feedback as feedback',
                    DB::raw('IF(atenciones.id IS NULL, "Pendiente", "Atendido") as estado')
                )
                ->orderByDesc('pacientes.fecha_llegada')
                ->get();
        }
        return view('seguimiento', compact('pacientes', 'usuarios', 'usuarioSeleccionado'));
    }

    public function verPaciente($id)
    {
        $paciente = DB::table('pacientes')->where('id', $id)->first();
        $triaje = DB::table('triajes')->where('paciente_id', $id)->first();
        $atencion = DB::table('atenciones')->where('paciente_id', $id)->first();
        $alumno = DB::table('users')->where('id', $paciente->alumno_id)->first();

        return view('detalle_paciente', compact('paciente', 'triaje', 'atencion', 'alumno'));
    }

    public function verFeedback($id)
    {
        $paciente = DB::table('pacientes')->where('id', $id)->first();
        $triaje = DB::table('triajes')->where('paciente_id', $id)->first();
        $atencion = DB::table('atenciones')->where('paciente_id', $id)->first();
        $alumno = DB::table('users')->where('id', $paciente->alumno_id)->first();

        return view('feedback', compact('paciente', 'triaje', 'atencion', 'alumno'));
    }

    public function guardarFeedback(Request $request)
    {
        try {
            DB::table('atenciones')
                ->where('paciente_id', $request->paciente_id)
                ->update(['feedback' => $request->feedback]);

            return redirect('/seguimiento')->with('ok', 'Feedback enviado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el feedback');
        }
    }
}