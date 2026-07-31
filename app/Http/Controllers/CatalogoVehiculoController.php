<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\ModeloVehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogoVehiculoController extends Controller
{
    public function index()
    {
        $marcas = Marca::with([
            'modelos' => function ($consulta) {
                $consulta->orderBy('nombre');
            },
        ])
            ->orderBy('nombre')
            ->get();

        return view(
            'catalogos-vehiculos.index',
            compact('marcas')
        );
    }

    public function guardarMarca(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre_marca' => [
                    'required',
                    'string',
                    'max:80',
                    'unique:marcas,nombre',
                ],
            ],
            [
                'nombre_marca.required' =>
                    'El nombre de la marca es obligatorio.',

                'nombre_marca.unique' =>
                    'La marca ya está registrada.',

                'nombre_marca.max' =>
                    'El nombre no puede superar los 80 caracteres.',
            ]
        );

        Marca::create([
            'nombre' => $datosValidados['nombre_marca'],
        ]);

        return back()->with(
            'success',
            'Marca registrada correctamente.'
        );
    }

    public function guardarModelo(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'marca_id' => [
                    'required',
                    'exists:marcas,id',
                ],
                'nombre_modelo' => [
                    'required',
                    'string',
                    'max:80',
                    Rule::unique('modelos', 'nombre')
                        ->where(function ($consulta) use ($request) {
                            $consulta->where(
                                'marca_id',
                                $request->marca_id
                            );
                        }),
                ],
            ],
            [
                'marca_id.required' =>
                    'Debe seleccionar una marca.',

                'marca_id.exists' =>
                    'La marca seleccionada no existe.',

                'nombre_modelo.required' =>
                    'El nombre del modelo es obligatorio.',

                'nombre_modelo.unique' =>
                    'Este modelo ya está registrado para la marca seleccionada.',

                'nombre_modelo.max' =>
                    'El nombre no puede superar los 80 caracteres.',
            ]
        );

        ModeloVehiculo::create([
            'marca_id' => $datosValidados['marca_id'],
            'nombre' => $datosValidados['nombre_modelo'],
        ]);

        return back()->with(
            'success',
            'Modelo registrado correctamente.'
        );
    }
}