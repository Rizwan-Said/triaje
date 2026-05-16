<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Si ya está logueado
        if (session()->has('usuario_id')) {

            if (session('rol') == 'profesor') {
                return redirect('/panel');
            } else {
                return redirect('/');
            }
        }

        return view('login');
    }



    public function login(Request $request)
    {

        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        if (Hash::check($request->password, $user->password)) {

            session([
                'usuario_id' => $user->id,
                'usuario_nombre' => $user->name,
                'rol' => $user->rol
            ]);

            // Obligarlo a cambiar contraseña
            if ((int)$user->debe_cambiar_password === 1) {
                return redirect('/cambiar-password');
            }

            if ($user->rol == 'profesor') {
                return redirect('/panel');
            } else {
                return redirect('/');
            }
        }

        return back()->with('error', 'Contraseña incorrecta');
    }

    public function showRegistro()
    {
        return view('registro');
    }

    public function registro(Request $request)
    {
        DB::table('users')->insert([
            'name' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol //recibe el rol
        ]);

        return redirect('/login')->with('success', 'Usuario creado correctamente');
    }

    public function showCambiarPassword()
    {
        if (!session()->has('usuario_id')) {
            return redirect('/login');
        }

        return view('cambiar-password');
    }

    public function cambiarPassword(Request $request)
    {
        if (!session()->has('usuario_id')) {
            return redirect('/login');
        }

        // Validar que las contraseñas coincidan
        if ($request->password !== $request->password2) {
            return back()->with('error', 'Las contraseñas no coinciden');
        }

        // Validar longitud mínima
        if (strlen($request->password) < 6) {
            return back()->with('error', 'La contraseña debe tener al menos 6 caracteres');
        }

        // Actualizar contraseña en la BD
        DB::table('users')
            ->where('id', session('usuario_id'))
            ->update([
                'password' => Hash::make($request->password),
                'debe_cambiar_password' => 0 // Marcar como ya cambió
            ]);

        // Redirigir según el rol
        if (session('rol') == 'profesor') {
            return redirect('/panel')->with('success', 'Contraseña cambiada correctamente');
        } else {
            return redirect('/')->with('success', 'Contraseña cambiada correctamente');
        }
    }
}