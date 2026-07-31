<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::with('tipoServicio')
            ->orderBy('nombre')
            ->get();

        return view(
            'servicios.index',
            compact('servicios')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposServicios = TipoServicio::orderBy('nombre')->get();

        return view(
            'servicios.create',
            compact('tiposServicios')
        );
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
                    'max:100',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200',
                ],
                'precio' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:99999999.99',
                ],
                'tipo_servicio_id' => [
                    'required',
                    'exists:tipo_servicios,id',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del servicio es obligatorio.',

                'nombre.max' =>
                    'El nombre no puede superar los 100 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 200 caracteres.',

                'precio.required' =>
                    'El precio es obligatorio.',

                'precio.numeric' =>
                    'El precio debe ser un valor numérico.',

                'precio.min' =>
                    'El precio no puede ser negativo.',

                'precio.max' =>
                    'El precio ingresado supera el límite permitido.',

                'tipo_servicio_id.required' =>
                    'Debe seleccionar un tipo de servicio.',

                'tipo_servicio_id.exists' =>
                    'El tipo de servicio seleccionado no existe.',
            ]
        );

        Servicio::create($datosValidados);

        return redirect()
            ->route('servicios.index')
            ->with(
                'success',
                'Servicio registrado correctamente.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicio $servicio)
    {
        $tiposServicios = TipoServicio::orderBy('nombre')->get();

        return view(
            'servicios.edit',
            compact('servicio', 'tiposServicios')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servicio $servicio)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200',
                ],
                'precio' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:99999999.99',
                ],
                'tipo_servicio_id' => [
                    'required',
                    'exists:tipo_servicios,id',
                ],
            ],
            [
                'nombre.required' =>
                    'El nombre del servicio es obligatorio.',

                'nombre.max' =>
                    'El nombre no puede superar los 100 caracteres.',

                'descripcion.max' =>
                    'La descripción no puede superar los 200 caracteres.',

                'precio.required' =>
                    'El precio es obligatorio.',

                'precio.numeric' =>
                    'El precio debe ser un valor numérico.',

                'precio.min' =>
                    'El precio no puede ser negativo.',

                'precio.max' =>
                    'El precio ingresado supera el límite permitido.',

                'tipo_servicio_id.required' =>
                    'Debe seleccionar un tipo de servicio.',

                'tipo_servicio_id.exists' =>
                    'El tipo de servicio seleccionado no existe.',
            ]
        );

        $servicio->update($datosValidados);

        return redirect()
            ->route('servicios.index')
            ->with(
                'success',
                'Servicio actualizado correctamente.'
            );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        if ($servicio->ordenesTrabajo()->exists()) {
            return redirect()
                ->route('servicios.index')
                ->with(
                    'error',
                    'No se puede eliminar el servicio porque está utilizado en órdenes de trabajo.'
                );
        }

        $servicio->delete();

        return redirect()
            ->route('servicios.index')
            ->with(
                'success',
                'Servicio eliminado correctamente.'
            );
    }
}
