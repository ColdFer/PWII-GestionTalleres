<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('usuario')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view(
            'clientes.index',
            compact('clientes')
        );
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:80',
                ],
                'apellido' => [
                    'required',
                    'string',
                    'max:80',
                ],
                'ci' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:clientes,ci',
                ],
                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                ],
                'correo' => [
                    'required',
                    'email',
                    'max:100',
                    'unique:clientes,correo',
                    'unique:users,email',
                ],
                'direccion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre es obligatorio.',

                'apellido.required' =>
                    'El apellido es obligatorio.',

                'ci.required' =>
                    'El CI es obligatorio.',

                'ci.unique' =>
                    'Ya existe un cliente registrado con este CI.',

                'telefono.required' =>
                    'El teléfono es obligatorio.',

                'correo.required' =>
                    'El correo de acceso es obligatorio.',

                'correo.email' =>
                    'Debe ingresar un correo electrónico válido.',

                'correo.unique' =>
                    'El correo ya está siendo utilizado.',

                'password.required' =>
                    'La contraseña es obligatoria.',

                'password.min' =>
                    'La contraseña debe tener al menos 8 caracteres.',

                'password.confirmed' =>
                    'La confirmación de la contraseña no coincide.',
            ]
        );

        $rolCliente = Role::where(
            'nombre',
            'Cliente'
        )->firstOrFail();

        DB::transaction(function () use (
            $datosValidados,
            $rolCliente
        ) {
            $usuario = User::create([
                'name' =>
                    $datosValidados['nombre'].' '.
                    $datosValidados['apellido'],

                'email' =>
                    $datosValidados['correo'],

                'password' => Hash::make(
                    $datosValidados['password']
                ),

                'role_id' => $rolCliente->id,
            ]);

            Cliente::create([
                'user_id' => $usuario->id,
                'nombre' => $datosValidados['nombre'],
                'apellido' => $datosValidados['apellido'],
                'ci' => $datosValidados['ci'],
                'telefono' => $datosValidados['telefono'],
                'correo' => $datosValidados['correo'],
                'direccion' =>
                    $datosValidados['direccion'] ?? null,
            ]);
        });

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Cliente y cuenta de acceso registrados correctamente.'
            );
    }

    public function show(Cliente $cliente)
    {
        return redirect()
            ->route('clientes.edit', $cliente);
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('usuario');

        return view(
            'clientes.edit',
            compact('cliente')
        );
    }

    public function update(
        Request $request,
        Cliente $cliente
    ) {
        $cliente->load('usuario');

        $reglaCorreoUsuario = Rule::unique(
            'users',
            'email'
        );

        if ($cliente->user_id) {
            $reglaCorreoUsuario->ignore(
                $cliente->user_id
            );
        }

        $reglasPassword = $cliente->user_id
            ? [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ]
            : [
                'required',
                'string',
                'min:8',
                'confirmed',
            ];

        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:80',
                ],
                'apellido' => [
                    'required',
                    'string',
                    'max:80',
                ],
                'ci' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('clientes', 'ci')
                        ->ignore($cliente->id),
                ],
                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                ],
                'correo' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('clientes', 'correo')
                        ->ignore($cliente->id),
                    $reglaCorreoUsuario,
                ],
                'direccion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
                'password' => $reglasPassword,
            ],
            [
                'nombre.required' =>
                    'El nombre es obligatorio.',

                'apellido.required' =>
                    'El apellido es obligatorio.',

                'ci.required' =>
                    'El CI es obligatorio.',

                'ci.unique' =>
                    'Ya existe otro cliente con este CI.',

                'telefono.required' =>
                    'El teléfono es obligatorio.',

                'correo.required' =>
                    'El correo de acceso es obligatorio.',

                'correo.email' =>
                    'Debe ingresar un correo electrónico válido.',

                'correo.unique' =>
                    'El correo ya está siendo utilizado.',

                'password.required' =>
                    'Debe ingresar una contraseña para crear la cuenta.',

                'password.min' =>
                    'La contraseña debe tener al menos 8 caracteres.',

                'password.confirmed' =>
                    'La confirmación de la contraseña no coincide.',
            ]
        );

        $rolCliente = Role::where(
            'nombre',
            'Cliente'
        )->firstOrFail();

        DB::transaction(function () use (
            $datosValidados,
            $cliente,
            $rolCliente
        ) {
            $usuario = $cliente->usuario;

            if (!$usuario) {
                $usuario = User::create([
                    'name' =>
                        $datosValidados['nombre'].' '.
                        $datosValidados['apellido'],

                    'email' =>
                        $datosValidados['correo'],

                    'password' => Hash::make(
                        $datosValidados['password']
                    ),

                    'role_id' => $rolCliente->id,
                ]);
            } else {
                $datosUsuario = [
                    'name' =>
                        $datosValidados['nombre'].' '.
                        $datosValidados['apellido'],

                    'email' =>
                        $datosValidados['correo'],

                    'role_id' => $rolCliente->id,
                ];

                if (!empty($datosValidados['password'])) {
                    $datosUsuario['password'] = Hash::make(
                        $datosValidados['password']
                    );
                }

                $usuario->update($datosUsuario);
            }

            $cliente->update([
                'user_id' => $usuario->id,
                'nombre' => $datosValidados['nombre'],
                'apellido' => $datosValidados['apellido'],
                'ci' => $datosValidados['ci'],
                'telefono' => $datosValidados['telefono'],
                'correo' => $datosValidados['correo'],
                'direccion' =>
                    $datosValidados['direccion'] ?? null,
            ]);
        });

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Cliente y cuenta de acceso actualizados correctamente.'
            );
    }

    public function destroy(Cliente $cliente)
    {
        /*
        |--------------------------------------------------------------------------
        | Impedir eliminar clientes con vehículos
        |--------------------------------------------------------------------------
        |
        | No eliminamos automáticamente sus vehículos porque estos podrían
        | contener órdenes y formar parte del historial del taller.
        |
        */

        if ($cliente->vehiculos()->exists()) {
            return redirect()
                ->route('clientes.index')
                ->with(
                    'error',
                    'No se puede eliminar el cliente porque tiene vehículos registrados.'
                );
        }

        DB::transaction(function () use ($cliente) {
            $usuario = $cliente->usuario;

            $cliente->delete();

            if ($usuario) {
                $usuario->delete();
            }
        });

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Cliente y cuenta de acceso eliminados correctamente.'
            );
    }
}