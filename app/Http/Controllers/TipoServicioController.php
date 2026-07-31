<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposServicios = TipoServicio::orderBy('nombre')->get();

        return view(
            'tipos-servicios.index',
            compact('tiposServicios')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipos-servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:80',
                    'unique:tipo_servicios,nombre',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del tipo de servicio es obligatorio.',

                'nombre.unique' =>
                    'Ya existe un tipo de servicio con este nombre.',

                'nombre.max' =>
                    'El nombre no puede superar los 80 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 150 caracteres.',
            ]
        );

        TipoServicio::create($datosValidados);

        return redirect()
            ->route('tipos-servicios.index')
            ->with(
                'success',
                'Tipo de servicio registrado correctamente.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoServicio $tipoServicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoServicio $tipoServicio)
    {
        return view(
            'tipos-servicios.edit',
            compact('tipoServicio')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoServicio $tipoServicio) 
    {
        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:80',
                    Rule::unique('tipo_servicios', 'nombre')
                        ->ignore($tipoServicio),
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del tipo de servicio es obligatorio.',

                'nombre.unique' =>
                    'Ya existe otro tipo de servicio con este nombre.',

                'nombre.max' =>
                    'El nombre no puede superar los 80 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 150 caracteres.',
            ]
        );

        $tipoServicio->update($datosValidados);

        return redirect()
            ->route('tipos-servicios.index')
            ->with(
                'success',
                'Tipo de servicio actualizado correctamente.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoServicio $tipoServicio)
    {
        if ($tipoServicio->servicios()->exists()) {
            return redirect()
                ->route('tipos-servicios.index')
                ->with(
                    'error',
                    'No se puede eliminar el tipo de servicio porque tiene servicios asociados.'
                );
        }

        $tipoServicio->delete();

        return redirect()
            ->route('tipos-servicios.index')
            ->with(
                'success',
                'Tipo de servicio eliminado correctamente.'
            );
    }
}
