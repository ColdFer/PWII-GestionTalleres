<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    Public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credenciales = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                ],
                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'email.required' =>
                    'El correo electrónico es obligatorio.',

                'email.email' =>
                    'Debe ingresar un correo electrónico válido.',

                'password.required' =>
                    'La contraseña es obligatoria.',
            ]
        );

        if (!Auth::attempt($credenciales)) {
            return back()
                ->withErrors([
                    'email' =>
                        'El correo o la contraseña son incorrectos.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Cliente
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->tieneRol('Cliente')
            && $usuario->tienePermiso('portal_cliente.ver')
        ) {
            return redirect()
                ->route('cliente.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Administrador o empleado
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->tienePermiso('panel.administrativo')
        ) {
            return redirect()
                ->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Usuario sin permisos
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors([
                'email' =>
                    'Su cuenta no tiene un rol o permisos asignados.',
            ])
            ->onlyInput('email');
    }
    Public function logout(Request $request)
    {
       Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
