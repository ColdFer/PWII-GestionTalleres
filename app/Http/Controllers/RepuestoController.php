<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RepuestoController extends Controller
{
    public function index()
    {
        $repuestos = Repuesto::orderBy('nombre')
            ->get();

        return view(
            'repuestos.index',
            compact('repuestos')
        );
    }

    public function create()
    {
        return view('repuestos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate(
            [
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:repuestos,codigo',
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'stock' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'estado' => [
                    'required',
                    Rule::in([
                        'Activo',
                        'Inactivo',
                    ]),
                ],
            ],
            [
                'codigo.required' =>
                    'El código es obligatorio.',

                'codigo.unique' =>
                    'El código ya está registrado.',

                'nombre.required' =>
                    'El nombre es obligatorio.',

                'precio_compra.required' =>
                    'El precio de compra es obligatorio.',

                'precio_venta.required' =>
                    'El precio de venta es obligatorio.',

                'stock.required' =>
                    'El stock es obligatorio.',

                'stock.min' =>
                    'El stock no puede ser negativo.',

                'stock_minimo.required' =>
                    'El stock mínimo es obligatorio.',
            ]
        );

        Repuesto::create($datos);

        return redirect()
            ->route('repuestos.index')
            ->with(
                'success',
                'Repuesto registrado correctamente.'
            );
    }

    public function edit(Repuesto $repuesto)
    {
        return view(
            'repuestos.edit',
            compact('repuesto')
        );
    }

    public function update(
        Request $request,
        Repuesto $repuesto
    ) {
        $datos = $request->validate(
            [
                'codigo' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique(
                        'repuestos',
                        'codigo'
                    )->ignore($repuesto->id),
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'precio_compra' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'precio_venta' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
                'stock' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'stock_minimo' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'estado' => [
                    'required',
                    Rule::in([
                        'Activo',
                        'Inactivo',
                    ]),
                ],
            ],
            [
                'codigo.required' =>
                    'El código es obligatorio.',

                'codigo.unique' =>
                    'El código ya está registrado.',

                'nombre.required' =>
                    'El nombre es obligatorio.',

                'stock.min' =>
                    'El stock no puede ser negativo.',
            ]
        );

        $repuesto->update($datos);

        return redirect()
            ->route('repuestos.index')
            ->with(
                'success',
                'Repuesto actualizado correctamente.'
            );
    }

    public function destroy(Repuesto $repuesto)
    {
        if ($repuesto->ordenesTrabajo()->exists()) {
            return redirect()
                ->route('repuestos.index')
                ->with(
                    'error',
                    'No se puede eliminar el repuesto porque está utilizado en órdenes de trabajo.'
                );
        }

        $repuesto->delete();

        return redirect()
            ->route('repuestos.index')
            ->with(
                'success',
                'Repuesto eliminado correctamente.'
            );
    }
}