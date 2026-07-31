<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Mecanico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MecanicoController extends Controller
{
    public function index()
    {
        $mecanicos = Mecanico::with('especialidad')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view(
            'mecanicos.index',
            compact('mecanicos')
        );
    }

    public function create()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();

        return view(
            'mecanicos.create',
            compact('especialidades')
        );
    }

    public function store(Request $request)
    {
        $datos = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'apellido' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'ci' => [
                    'nullable',
                    'string',
                    'max:30',
                    'unique:mecanicos,ci',
                ],
                'telefono' => [
                    'nullable',
                    'string',
                    'max:30',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:150',
                    'unique:mecanicos,email',
                ],
                'especialidad_id' => [
                    'required',
                    'exists:especialidades,id',
                ],
                'estado' => [
                    'required',
                    Rule::in(['Activo', 'Inactivo']),
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre es obligatorio.',

                'apellido.required' =>
                    'El apellido es obligatorio.',

                'ci.unique' =>
                    'El CI ya está registrado.',

                'email.unique' =>
                    'El correo ya está registrado.',

                'especialidad_id.required' =>
                    'Debe seleccionar una especialidad.',
            ]
        );

        Mecanico::create($datos);

        return redirect()
            ->route('mecanicos.index')
            ->with(
                'success',
                'Mecánico registrado correctamente.'
            );
    }

    public function edit(Mecanico $mecanico)
    {
        $especialidades = Especialidad::orderBy('nombre')->get();

        return view(
            'mecanicos.edit',
            compact('mecanico', 'especialidades')
        );
    }

    public function update(
        Request $request,
        Mecanico $mecanico
    ) {
        $datos = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'apellido' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'ci' => [
                    'nullable',
                    'string',
                    'max:30',
                    Rule::unique('mecanicos', 'ci')
                        ->ignore($mecanico->id),
                ],
                'telefono' => [
                    'nullable',
                    'string',
                    'max:30',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:150',
                    Rule::unique('mecanicos', 'email')
                        ->ignore($mecanico->id),
                ],
                'especialidad_id' => [
                    'required',
                    'exists:especialidades,id',
                ],
                'estado' => [
                    'required',
                    Rule::in(['Activo', 'Inactivo']),
                ],
            ]
        );

        $mecanico->update($datos);

        return redirect()
            ->route('mecanicos.index')
            ->with(
                'success',
                'Mecánico actualizado correctamente.'
            );
    }

    public function destroy(Mecanico $mecanico)
    {
        if ($mecanico->ordenesTrabajo()->exists()) {
            return redirect()
                ->route('mecanicos.index')
                ->with(
                    'error',
                    'No se puede eliminar el mecánico porque está asignado a órdenes de trabajo.'
                );
        }

        $mecanico->delete();

        return redirect()
            ->route('mecanicos.index')
            ->with(
                'success',
                'Mecánico eliminado correctamente.'
            );
    }
}