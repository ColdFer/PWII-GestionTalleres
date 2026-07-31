<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permisos')
            ->withCount('usuarios')
            ->orderBy('nombre')
            ->get();

        return view(
            'roles.index',
            compact('roles')
        );
    }

    public function create()
    {
        $permisos = Permiso::orderBy('nombre')->get();

        return view(
            'roles.create',
            compact('permisos')
        );
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:roles,nombre',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
                'permisos' => [
                    'nullable',
                    'array',
                ],
                'permisos.*' => [
                    'integer',
                    'distinct',
                    'exists:permisos,id',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del rol es obligatorio.',

                'nombre.unique' =>
                    'Ya existe un rol con este nombre.',

                'nombre.max' =>
                    'El nombre no puede superar los 50 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 150 caracteres.',

                'permisos.*.exists' =>
                    'Uno de los permisos seleccionados no existe.',
            ]
        );

        DB::transaction(function () use ($datosValidados) {
            $role = Role::create([
                'nombre' => $datosValidados['nombre'],
                'descripcion' =>
                    $datosValidados['descripcion'] ?? null,
            ]);

            $role->permisos()->sync(
                $datosValidados['permisos'] ?? []
            );
        });

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol registrado correctamente.'
            );
    }

    public function edit(Role $role)
    {
        $role->load('permisos');

        $permisos = Permiso::orderBy('nombre')->get();

        return view(
            'roles.edit',
            compact('role', 'permisos')
        );
    }

    public function update(
        Request $request,
        Role $role
    ) {
        $esAdministrador =
            $role->nombre === 'Administrador';

        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('roles', 'nombre')
                        ->ignore($role->id),
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
                'permisos' => [
                    'nullable',
                    'array',
                ],
                'permisos.*' => [
                    'integer',
                    'distinct',
                    'exists:permisos,id',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del rol es obligatorio.',

                'nombre.unique' =>
                    'Ya existe otro rol con este nombre.',

                'nombre.max' =>
                    'El nombre no puede superar los 50 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 150 caracteres.',

                'permisos.*.exists' =>
                    'Uno de los permisos seleccionados no existe.',
            ]
        );

        DB::transaction(function () use (
            $datosValidados,
            $role,
            $esAdministrador
        ) {
            $role->update([
                'nombre' => $esAdministrador
                    ? 'Administrador'
                    : $datosValidados['nombre'],

                'descripcion' =>
                    $datosValidados['descripcion'] ?? null,
            ]);

            if ($esAdministrador) {
                $role->permisos()->sync(
                    Permiso::pluck('id')->all()
                );
            } else {
                $role->permisos()->sync(
                    $datosValidados['permisos'] ?? []
                );
            }
        });

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol y permisos actualizados correctamente.'
            );
    }

    public function destroy(Role $role)
    {
        $rolesBase = [
            'Administrador',
            'Empleado',
            'Cliente',
        ];

        if (in_array($role->nombre, $rolesBase, true)) {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'Los roles principales del sistema no pueden eliminarse.'
                );
        }

        if ($role->usuarios()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'No se puede eliminar el rol porque está asignado a usuarios.'
                );
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rol eliminado correctamente.'
            );
    }
}