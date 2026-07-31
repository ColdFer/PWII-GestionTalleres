<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ModeloVehiculo;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculos = Vehiculo::with([
            'cliente',
            'modelo.marca',
        ])->get();

        return view(
            'vehiculos.index',
            compact('vehiculos')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
  public function create()
    {
        $clientes = Cliente::orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $modelos = ModeloVehiculo::with('marca')
            ->orderBy('nombre')
            ->get();

        return view(
            'vehiculos.create',
            compact('clientes', 'modelos')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'placa' => [
                'required',
                'string',
                'max:20',
                'unique:vehiculos,placa',
            ],
            'anio' => [
                'required',
                'integer',
                'min:1901',
                'max:' . date('Y'),
            ],
            'color' => [
                'required',
                'string',
                'max:30',
            ],
            'kilometraje' => [
                'required',
                'integer',
                'min:0',
            ],
            'cliente_id' => [
                'required',
                'exists:clientes,id',
            ],
            'modelo_id' => [
                'required',
                'exists:modelos,id',    
            ],
        ],
        [
            'placa.required' => 'La placa es obligatoria.',
            'placa.unique' => 'Ya existe un vehículo registrado con esta placa.',
            'placa.max' => 'La placa no puede superar los 20 caracteres.',

            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año ingresado no es válido.',
            'anio.max' => 'El año no puede ser mayor al año actual.',

            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede superar los 30 caracteres.',

            'kilometraje.required' => 'El kilometraje es obligatorio.',
            'kilometraje.integer' => 'El kilometraje debe ser un número entero.',
            'kilometraje.min' => 'El kilometraje no puede ser negativo.',

            'cliente_id.required' => 'Debe seleccionar un propietario.',
            'cliente_id.exists' => 'El propietario seleccionado no existe.',
            'modelo_id.required' =>
            'Debe seleccionar una marca y modelo.',

            'modelo_id.exists' =>
                'El modelo seleccionado no existe.',
        ]);

        Vehiculo::create($datosValidados);

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Vehiculo $vehiculo)
    {
        $clientes = Cliente::orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $modelos = ModeloVehiculo::with('marca')
            ->orderBy('nombre')
            ->get();

        return view(
            'vehiculos.edit',
            compact(
                'vehiculo',
                'clientes',
                'modelos'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Vehiculo $vehiculo) 
    {
        $datosValidados = $request->validate([
            'placa' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehiculos', 'placa')
                    ->ignore($vehiculo),
            ],
            'anio' => [
                'required',
                'integer',
                'min:1901',
                'max:' . date('Y'),
            ],
            'color' => [
                'required',
                'string',
                'max:30',
            ],
            'kilometraje' => [
                'required',
                'integer',
                'min:0',
            ],
            'cliente_id' => [
                'required',
                'exists:clientes,id',
            ],
            'modelo_id' => [
                'required',
                'exists:modelos,id',
            ],
        ],
        [
            'placa.required' => 'La placa es obligatoria.',
            'placa.unique' => 'Ya existe un vehículo registrado con esta placa.',
            'placa.max' => 'La placa no puede superar los 20 caracteres.',

            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año ingresado no es válido.',
            'anio.max' => 'El año no puede ser mayor al año actual.',

            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede superar los 30 caracteres.',

            'kilometraje.required' => 'El kilometraje es obligatorio.',
            'kilometraje.integer' => 'El kilometraje debe ser un número entero.',
            'kilometraje.min' => 'El kilometraje no puede ser negativo.',

            'cliente_id.required' => 'Debe seleccionar un propietario.',
            'cliente_id.exists' => 'El propietario seleccionado no existe.',
            'modelo_id.required' =>
                'Debe seleccionar una marca y modelo.',

            'modelo_id.exists' =>
                'El modelo seleccionado no existe.',
        ]);

        $vehiculo->update($datosValidados);

        return redirect()
            ->route('vehiculos.index')
            ->with(
                'success',
                'Vehículo actualizado correctamente.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        /*
        |--------------------------------------------------------------------------
        | Evitar eliminar vehículos con órdenes
        |--------------------------------------------------------------------------
        */

        if ($vehiculo->ordenesTrabajo()->exists()) {
            return redirect()
                ->route('vehiculos.index')
                ->with(
                    'error',
                    'No se puede eliminar el vehículo porque tiene órdenes de trabajo registradas.'
                );
        }

        $vehiculo->delete();

        return redirect()
            ->route('vehiculos.index')
            ->with(
                'success',
                'Vehículo eliminado correctamente.'
            );
    }
}
