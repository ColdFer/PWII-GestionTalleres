<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with([
            'rol',
            'cliente',
        ])
            ->orderBy('name')
            ->get();

        return view(
            'usuarios.index',
            compact('usuarios')
        );
    }

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Roles disponibles
        |--------------------------------------------------------------------------
        |
        | El rol Cliente no aparece porque las cuentas de clientes deben
        | crearse desde el módulo Clientes.
        |
        */

        $roles = Role::where(
            'nombre',
            '!=',
            'Cliente'
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'usuarios.create',
            compact('roles')
        );
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
                'role_id' => [
                    'required',
                    Rule::exists('roles', 'id')
                        ->where(function ($consulta) {
                            $consulta->where(
                                'nombre',
                                '!=',
                                'Cliente'
                            );
                        }),
                ],
            ],
            [
                'name.required' =>
                    'El nombre del usuario es obligatorio.',

                'email.required' =>
                    'El correo electrónico es obligatorio.',

                'email.email' =>
                    'Debe ingresar un correo electrónico válido.',

                'email.unique' =>
                    'El correo ya está siendo utilizado.',

                'password.required' =>
                    'La contraseña es obligatoria.',

                'password.min' =>
                    'La contraseña debe tener al menos 8 caracteres.',

                'password.confirmed' =>
                    'La confirmación de la contraseña no coincide.',

                'role_id.required' =>
                    'Debe seleccionar un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',
            ]
        );

        User::create([
            'name' => $datosValidados['name'],
            'email' => $datosValidados['email'],
            'password' => Hash::make(
                $datosValidados['password']
            ),
            'role_id' => $datosValidados['role_id'],
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario registrado correctamente.'
            );
    }

    public function edit(User $usuario)
    {
        /*
        |--------------------------------------------------------------------------
        | Usuarios clientes
        |--------------------------------------------------------------------------
        |
        | Si la cuenta está vinculada a un cliente, debe editarse desde el
        | módulo Clientes para mantener sincronizados ambos registros.
        |
        */

        if ($usuario->cliente) {
            return redirect()
                ->route('clientes.edit', $usuario->cliente)
                ->with(
                    'error',
                    'Las cuentas de clientes deben editarse desde el módulo Clientes.'
                );
        }

        $roles = Role::where(
            'nombre',
            '!=',
            'Cliente'
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'usuarios.edit',
            compact('usuario', 'roles')
        );
    }

    public function update(
        Request $request,
        User $usuario
    ) {
        if ($usuario->cliente) {
            return redirect()
                ->route('clientes.edit', $usuario->cliente)
                ->with(
                    'error',
                    'Las cuentas de clientes deben editarse desde el módulo Clientes.'
                );
        }

        $datosValidados = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')
                        ->ignore($usuario->id),
                ],
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed',
                ],
                'role_id' => [
                    'required',
                    Rule::exists('roles', 'id')
                        ->where(function ($consulta) {
                            $consulta->where(
                                'nombre',
                                '!=',
                                'Cliente'
                            );
                        }),
                ],
            ],
            [
                'name.required' =>
                    'El nombre del usuario es obligatorio.',

                'email.required' =>
                    'El correo electrónico es obligatorio.',

                'email.email' =>
                    'Debe ingresar un correo electrónico válido.',

                'email.unique' =>
                    'El correo ya está siendo utilizado.',

                'password.min' =>
                    'La contraseña debe tener al menos 8 caracteres.',

                'password.confirmed' =>
                    'La confirmación de la contraseña no coincide.',

                'role_id.required' =>
                    'Debe seleccionar un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',
            ]
        );

        $datosUsuario = [
            'name' => $datosValidados['name'],
            'email' => $datosValidados['email'],
            'role_id' => $datosValidados['role_id'],
        ];

        if (!empty($datosValidados['password'])) {
            $datosUsuario['password'] = Hash::make(
                $datosValidados['password']
            );
        }

        $usuario->update($datosUsuario);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }

    public function destroy(User $usuario)
    {
        /*
        |--------------------------------------------------------------------------
        | Impedir eliminar cuentas vinculadas a clientes
        |--------------------------------------------------------------------------
        */

        if ($usuario->cliente()->exists()) {
            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'La cuenta está vinculada a un cliente. Debe gestionarla desde el módulo Clientes.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Impedir eliminar usuarios que crearon órdenes
        |--------------------------------------------------------------------------
        |
        | Conservamos el usuario para mantener el historial de quién registró
        | cada orden de trabajo.
        |
        */

        if ($usuario->ordenesCreadas()->exists()) {
            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'No se puede eliminar este usuario porque tiene órdenes de trabajo registradas a su nombre.'
                );
        }


        if ($usuario->pagosRegistrados()->exists()) {
            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'No se puede eliminar este usuario porque tiene pagos registrados a su nombre.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Impedir eliminar al último administrador
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->tieneRol('Administrador')
            && User::where(
                'role_id',
                $usuario->role_id
            )->count() <= 1
        ) {
            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'No puede eliminar al único administrador del sistema.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Impedir eliminar la cuenta autenticada
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'No puede eliminar la cuenta con la que inició sesión.'
                );
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario eliminado correctamente.'
            );
    }
}