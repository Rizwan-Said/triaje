<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AdmisionController;//admision
use App\Http\Controllers\TriajeController;//triaje
use App\Http\Controllers\AtencionController;//atencion
use App\Http\Controllers\PanelController;//panel de control para profesor
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;*/ //es para crear el primer profesor


Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cambiar-password', [AuthController::class, 'showCambiarPassword'])->name('cambiar-password');
Route::post('/cambiar-password', [AuthController::class, 'cambiarPassword'])->name('cambiar-password.post');
Route::post('/admision', [AdmisionController::class, 'guardar']);//admision
Route::get('/admision/{id}', [AdmisionController::class, 'ver']);
Route::post('/admision/update/{id}', [AdmisionController::class, 'actualizar']);
Route::get('/triaje/{id}', [TriajeController::class, 'ver']);//triaje
Route::post('/triaje', [TriajeController::class, 'guardar']);//triaje
Route::get('/atencion/{id}', [AtencionController::class, 'ver']);//atencion
Route::post('/atencion', [AtencionController::class, 'guardar']);//atencion
Route::get('/seguimiento', [PanelController::class, 'index']); //panel de control para prof
Route::get('/seguimiento/paciente/{id}', [PanelController::class, 'verPaciente']); //detalles paciente para profesor
Route::get('/seguimiento/paciente/{id}', [PanelController::class, 'verPaciente'])->name('seguimiento.paciente'); //detalle para el alumno
Route::get('/seguimiento/feedback/{id}', [PanelController::class, 'verFeedback']); //ver feedback del profesor para el alumno
Route::post('/seguimiento/feedback', [PanelController::class, 'guardarFeedback']);// guardar feedback del profesor para el alumno
Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');//vista para registrar nuevos alumnos
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.post');//registrar nuevos alumnos
Route::get('/mis-feedbacks', [AtencionController::class, 'misFeedbacks']); //vista para que el alumno vea los feedbacks de sus pacientes
Route::get('/mis-feedbacks/{id}', [AtencionController::class, 'verFeedback']); //vista para que el alumno vea el detalle de un feedback específico de uno de sus pacientes


Route::get('/crear-profesor', function () {
    DB::table('users')->updateOrInsert(
        ['email' => 'profesor@correo.com'],
        [
            'name' => 'profesor1',
            'password' => Hash::make('123456'),
            'rol' => 'profesor'
        ]
    );

    return "Profesor creado correctamente";
});  //es para crear profesor, desde sql no admitia hash.




//sesion profesor
Route::get('/panel', function () { //dirige a la ruta del panel

    if (!session()->has('usuario_id')) {
        return redirect('/login');
    }

    if (session('rol') != 'profesor') {
        return redirect('/');
    }

    $pacientes = DB::table('pacientes')
        ->leftJoin('triajes', 'pacientes.id', '=', 'triajes.paciente_id')
        ->leftJoin('atenciones', 'pacientes.id', '=', 'atenciones.paciente_id')
        ->where('pacientes.alumno_id', session('usuario_id'))
        ->select(
            'pacientes.*',
            'triajes.categoria',
            'triajes.hora_triaje',
            DB::raw('IF(atenciones.id IS NULL, "Pendiente", "Atendido") as estado')
        )
        ->orderByDesc('pacientes.fecha_llegada')
        ->get();

    return view('profesor', compact('pacientes'));
});


//sesion alumno
Route::get('/', function () {

    if (!session()->has('usuario_id')) {
        return redirect('/login');
    }

    if (session('rol') != 'alumno') {
        return redirect('/panel');
    }

    $pacientes = DB::table('pacientes')
        ->leftJoin('triajes', 'pacientes.id', '=', 'triajes.paciente_id')
        ->leftJoin('atenciones', 'pacientes.id', '=', 'atenciones.paciente_id')
        ->where('pacientes.alumno_id', session('usuario_id'))
        ->select(
            'pacientes.*',
            'triajes.categoria',
            'triajes.hora_triaje',
            DB::raw('IF(atenciones.id IS NULL, "Pendiente", "Atendido") as estado')
        )
        ->orderByDesc('pacientes.fecha_llegada')
        ->get();

    return view('alumno', compact('pacientes'));
});

//admision
Route::get('/admision', function () {

    if (!session()->has('usuario_id')) {
        return redirect('/login');
    }

    return view('admision');
});


//triaje
Route::get('/triaje', function () {

    if (!session()->has('usuario_id')) {
        return redirect('/login');
    }

    return view('triaje');
});


//logout
Route::post('/logout', function () {
    Session::flush();
    return redirect('/login');
});


Route::get('/usuarios', function () { //ver todos los usuarios en panel del profesor

    if (!session()->has('usuario_id')) {
        return redirect('/login');
    }

    if (session('rol') != 'profesor') {
        return redirect('/');
    }

    $usuarios = DB::table('users')
        ->orderBy('name')
        ->get();

    return view('usuarios', compact('usuarios'));
});

Route::post('/reset-password/{id}', function ($id) { //fucion para resetear password

    if (session('rol') != 'profesor') {
        return redirect('/');
    }

    DB::table('users')
        ->where('id', $id)
        ->update([
            'password' => Hash::make('123456'),
            'debe_cambiar_password' => 1 //deja en 1 al pinchar el boton
        ]);

    return back();
});