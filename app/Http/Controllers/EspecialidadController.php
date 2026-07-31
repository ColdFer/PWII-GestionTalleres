<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EspecialidadController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::withCount('mecanicos')
            ->orderBy('nombre')
            ->get();

        return view(
            'especialidades.index',
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
                    'unique:especialidades,nombre',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre de la especialidad es obligatorio.',

                'nombre.unique' =>
                    'La especialidad ya está registrada.',
            ]
        );

        Especialidad::create($datos);

        return redirect()
            ->route('especialidades.index')
            ->with(
                'success',
                'Especialidad registrada correctamente.'
            );
    }

    public function edit(Especialidad $especialidad)
    {
        return view(
            'especialidades.edit',
            compact('especialidad')
        );
    }

    public function update(
        Request $request,
        Especialidad $especialidad
    ) {
        $datos = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('especialidades', 'nombre')
                        ->ignore($especialidad->id),
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre de la especialidad es obligatorio.',

                'nombre.unique' =>
                    'La especialidad ya está registrada.',
            ]
        );

        $especialidad->update($datos);

        return redirect()
            ->route('especialidades.index')
            ->with(
                'success',
                'Especialidad actualizada correctamente.'
            );
    }

    public function destroy(Especialidad $especialidad)
    {
        if ($especialidad->mecanicos()->exists()) {
            return redirect()
                ->route('especialidades.index')
                ->with(
                    'error',
                    'No se puede eliminar la especialidad porque tiene mecánicos registrados.'
                );
        }

        $especialidad->delete();

        return redirect()
            ->route('especialidades.index')
            ->with(
                'success',
                'Especialidad eliminada correctamente.'
            );
    }
}