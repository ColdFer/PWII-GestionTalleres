<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Servicio;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Mecanico;
use App\Models\Repuesto;


class OrdenTrabajoController extends Controller
{
    public function index()
    {
        $ordenes = OrdenTrabajo::with([
            'vehiculo.cliente',
            'servicios',
            'creadoPor',
            'mecanico.especialidad',
        ])
            ->latest('fecha_ingreso')
            ->latest('id')
            ->get();

        $mecanicos = Mecanico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view(
            'ordenes.index',
            compact('ordenes', 'mecanicos')
        );
    }

    public function create()
    {
        $vehiculos = Vehiculo::with('cliente')
            ->orderBy('placa')
            ->get();

        $servicios = Servicio::with('tipoServicio')
            ->orderBy('nombre')
            ->get();

        $mecanicos = Mecanico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view(
            'ordenes.create',
            compact('vehiculos', 'servicios', 'mecanicos')
        );
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'vehiculo_id' => [
                    'required',
                    'exists:vehiculos,id',
                ],
                'fecha_ingreso' => [
                    'required',
                    'date',
                ],
                'fecha_salida_estimada' => [
                    'nullable',
                    'date',
                    'after_or_equal:fecha_ingreso',
                ],
                'diagnostico' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'observaciones' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'servicios' => [
                    'required',
                    'array',
                    'min:1',
                ],
                'servicios.*' => [
                    'required',
                    'distinct',
                    'exists:servicios,id',
                ],
                'mecanico_id' => [
                    'nullable',
                    'exists:mecanicos,id',
                ],
            ],
            [
                'vehiculo_id.required' =>
                    'Debe seleccionar un vehículo.',

                'vehiculo_id.exists' =>
                    'El vehículo seleccionado no existe.',

                'fecha_ingreso.required' =>
                    'La fecha de ingreso es obligatoria.',

                'fecha_salida_estimada.after_or_equal' =>
                    'La salida estimada no puede ser anterior al ingreso.',

                'diagnostico.max' =>
                    'El diagnóstico no puede superar los 1000 caracteres.',

                'observaciones.max' =>
                    'Las observaciones no pueden superar los 1000 caracteres.',

                'servicios.required' =>
                    'Debe seleccionar al menos un servicio.',

                'servicios.min' =>
                    'Debe seleccionar al menos un servicio.',

                'servicios.*.exists' =>
                    'Uno de los servicios seleccionados no existe.',
            ]
        );

        DB::transaction(function () use (
            $datosValidados,
            $request
        ) {
            $servicios = Servicio::whereIn(
                'id',
                $datosValidados['servicios']
            )->get();

            $total = $servicios->sum('precio');

            $orden = OrdenTrabajo::create([
                'codigo' => null,
                'vehiculo_id' =>
                    $datosValidados['vehiculo_id'],
                'user_id' =>
                    $request->user()->id,
                'fecha_ingreso' =>
                    $datosValidados['fecha_ingreso'],
                'fecha_salida_estimada' =>
                    $datosValidados['fecha_salida_estimada']
                        ?? null,
                'estado' => 'Pendiente',
                'diagnostico' =>
                    $datosValidados['diagnostico']
                        ?? null,
                'observaciones' =>
                    $datosValidados['observaciones']
                        ?? null,
                'total' => $total,
                'mecanico_id' =>
                    $datosValidados['mecanico_id'] ?? null,
            ]);

            $orden->update([
                'codigo' =>
                    'OT-'.str_pad(
                        (string) $orden->id,
                        5,
                        '0',
                        STR_PAD_LEFT
                    ),
            ]);

            $serviciosParaAdjuntar = [];

            foreach ($servicios as $servicio) {
                $serviciosParaAdjuntar[$servicio->id] = [
                    'precio' => $servicio->precio,
                ];
            }

            $orden->servicios()->attach(
                $serviciosParaAdjuntar
            );
        });

        return redirect()
            ->route('ordenes.index')
            ->with(
                'success',
                'Orden de trabajo registrada correctamente.'
            );
    }

    public function show(OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->load([
            'vehiculo.cliente',
            'vehiculo.modelo.marca',
            'servicios',
            'repuestos',
            'pagos.registradoPor',
            'creadoPor',
            'mecanico.especialidad',
        ]);

        $repuestosDisponibles = Repuesto::where(
            'estado',
            'Activo'
        )
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view(
            'ordenes.show',
            compact('ordenTrabajo', 'repuestosDisponibles')
        );
    }

    public function actualizarEstado(
        Request $request,
        OrdenTrabajo $ordenTrabajo
    ) {
        $datosValidados = $request->validate(
            [
                'estado' => [
                    'required',
                    Rule::in([
                        'Pendiente',
                        'En diagnóstico',
                        'En reparación',
                        'Finalizada',
                        'Entregada',
                        'Cancelada',
                    ]),
                ],
            ],
            [
                'estado.required' =>
                    'Debe seleccionar un estado.',

                'estado.in' =>
                    'El estado seleccionado no es válido.',
            ]
        );

        $ordenTrabajo->update([
            'estado' => $datosValidados['estado'],
        ]);

        return back()->with(
            'success',
            'Estado de la orden actualizado correctamente.'
        );
    }
    public function actualizarMecanico(
        Request $request,
        OrdenTrabajo $ordenTrabajo
    ) {
        $datos = $request->validate(
            [
                'mecanico_id' => [
                    'nullable',
                    'exists:mecanicos,id',
                ],
            ],
            [
                'mecanico_id.exists' =>
                    'El mecánico seleccionado no existe.',
            ]
        );

        $ordenTrabajo->update([
            'mecanico_id' => $datos['mecanico_id'] ?? null,
        ]);

        return back()->with(
            'success',
            'Mecánico responsable actualizado correctamente.'
        );
    }
    public function agregarRepuesto(
        Request $request,
        OrdenTrabajo $ordenTrabajo
    ) {
        if (
            in_array(
                $ordenTrabajo->estado,
                ['Entregada', 'Cancelada'],
                true
            )
        ) {
            return back()->with(
                'error',
                'No se pueden agregar repuestos a una orden entregada o cancelada.'
            );
        }

        $datos = $request->validate(
            [
                'repuesto_id' => [
                    'required',
                    'exists:repuestos,id',
                ],
                'cantidad' => [
                    'required',
                    'integer',
                    'min:1',
                ],
            ],
            [
                'repuesto_id.required' =>
                    'Debe seleccionar un repuesto.',

                'cantidad.required' =>
                    'Debe indicar la cantidad.',

                'cantidad.min' =>
                    'La cantidad debe ser mayor que cero.',
            ]
        );

        $errorStock = null;

        DB::transaction(function () use (
            $datos,
            $ordenTrabajo,
            &$errorStock
        ) {
            $repuesto = Repuesto::lockForUpdate()
                ->findOrFail($datos['repuesto_id']);

            $cantidad = (int) $datos['cantidad'];

            if ($repuesto->estado !== 'Activo') {
                $errorStock =
                    'El repuesto seleccionado está inactivo.';

                return;
            }

            if ($repuesto->stock < $cantidad) {
                $errorStock =
                    'No existe stock suficiente. Disponible: '
                    .$repuesto->stock.'.';

                return;
            }

            $detalleActual = DB::table(
                'detalle_repuesto_orden'
            )
                ->where(
                    'orden_trabajo_id',
                    $ordenTrabajo->id
                )
                ->where(
                    'repuesto_id',
                    $repuesto->id
                )
                ->lockForUpdate()
                ->first();

            if ($detalleActual) {
                $nuevaCantidad =
                    $detalleActual->cantidad + $cantidad;

                $nuevoSubtotal =
                    $nuevaCantidad * $repuesto->precio_venta;

                DB::table('detalle_repuesto_orden')
                    ->where(
                        'id',
                        $detalleActual->id
                    )
                    ->update([
                        'cantidad' => $nuevaCantidad,
                        'precio_unitario' =>
                            $repuesto->precio_venta,
                        'subtotal' => $nuevoSubtotal,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('detalle_repuesto_orden')
                    ->insert([
                        'orden_trabajo_id' =>
                            $ordenTrabajo->id,

                        'repuesto_id' =>
                            $repuesto->id,

                        'cantidad' =>
                            $cantidad,

                        'precio_unitario' =>
                            $repuesto->precio_venta,

                        'subtotal' =>
                            $cantidad
                            * $repuesto->precio_venta,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            $repuesto->decrement(
                'stock',
                $cantidad
            );

            $ordenTrabajo->recalcularTotal();
        });

        if ($errorStock) {
            return back()->with(
                'error',
                $errorStock
            );
        }

        return back()->with(
            'success',
            'Repuesto agregado a la orden correctamente.'
        );
    }   

    public function quitarRepuesto(
        OrdenTrabajo $ordenTrabajo,
        Repuesto $repuesto
    ) {
        if (
            in_array(
                $ordenTrabajo->estado,
                ['Entregada', 'Cancelada'],
                true
            )
        ) {
            return back()->with(
                'error',
                'No se pueden modificar los repuestos de una orden entregada o cancelada.'
            );
        }

        $detalle = DB::table(
            'detalle_repuesto_orden'
        )
            ->where(
                'orden_trabajo_id',
                $ordenTrabajo->id
            )
            ->where(
                'repuesto_id',
                $repuesto->id
            )
            ->first();

        if (!$detalle) {
            return back()->with(
                'error',
                'El repuesto no pertenece a esta orden.'
            );
        }

        DB::transaction(function () use (
            $ordenTrabajo,
            $repuesto,
            $detalle
        ) {
            $repuestoBloqueado = Repuesto::lockForUpdate()
                ->findOrFail($repuesto->id);

            $repuestoBloqueado->increment(
                'stock',
                $detalle->cantidad
            );

            DB::table('detalle_repuesto_orden')
                ->where('id', $detalle->id)
                ->delete();

            $ordenTrabajo->recalcularTotal();
        });

        return back()->with(
            'success',
            'Repuesto retirado y stock restaurado correctamente.'
        );
    }
}