<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use App\Models\Repuesto;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validación del filtro
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'fecha_desde' => [
                    'nullable',
                    'date',
                ],
                'fecha_hasta' => [
                    'nullable',
                    'date',
                    'after_or_equal:fecha_desde',
                ],
            ],
            [
                'fecha_desde.date' =>
                    'La fecha inicial no es válida.',

                'fecha_hasta.date' =>
                    'La fecha final no es válida.',

                'fecha_hasta.after_or_equal' =>
                    'La fecha final no puede ser anterior a la fecha inicial.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Periodo del reporte
        |--------------------------------------------------------------------------
        |
        | Si no se envían fechas, se utiliza el mes actual.
        |
        */

        $fechaDesde = $request->filled('fecha_desde')
            ? $request->fecha_desde
            : now()->startOfMonth()->format('Y-m-d');

        $fechaHasta = $request->filled('fecha_hasta')
            ? $request->fecha_hasta
            : now()->format('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | Órdenes del periodo
        |--------------------------------------------------------------------------
        */

        $consultaOrdenes = OrdenTrabajo::whereBetween(
            'fecha_ingreso',
            [
                $fechaDesde,
                $fechaHasta,
            ]
        );

        $totalOrdenes = (clone $consultaOrdenes)->count();

        $valorOrdenes = (clone $consultaOrdenes)
            ->where(
                'estado',
                '!=',
                'Cancelada'
            )
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Pagos recibidos durante el periodo
        |--------------------------------------------------------------------------
        |
        | Se filtran por la fecha en que se registró el pago.
        |
        */

        $totalCobrado = Pago::whereBetween(
            'fecha',
            [
                $fechaDesde,
                $fechaHasta,
            ]
        )->sum('monto');

        /*
        |--------------------------------------------------------------------------
        | Saldo pendiente de las órdenes del periodo
        |--------------------------------------------------------------------------
        */

        $ordenesConPagos = OrdenTrabajo::withSum(
            'pagos as total_pagado_calculado',
            'monto'
        )
            ->whereBetween(
                'fecha_ingreso',
                [
                    $fechaDesde,
                    $fechaHasta,
                ]
            )
            ->where(
                'estado',
                '!=',
                'Cancelada'
            )
            ->get();

        $saldoPendiente = $ordenesConPagos->sum(
            function (OrdenTrabajo $orden) {
                $total = (float) $orden->total;

                $pagado = (float) (
                    $orden->total_pagado_calculado ?? 0
                );

                return max(
                    0,
                    $total - $pagado
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Órdenes por estado
        |--------------------------------------------------------------------------
        */

        $ordenesPorEstado = OrdenTrabajo::select(
            'estado',
            DB::raw('COUNT(*) as cantidad')
        )
            ->whereBetween(
                'fecha_ingreso',
                [
                    $fechaDesde,
                    $fechaHasta,
                ]
            )
            ->groupBy('estado')
            ->pluck(
                'cantidad',
                'estado'
            );

        /*
        |--------------------------------------------------------------------------
        | Pagos agrupados por método
        |--------------------------------------------------------------------------
        */

        $pagosPorMetodo = Pago::select(
            'metodo',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(monto) as total')
        )
            ->whereBetween(
                'fecha',
                [
                    $fechaDesde,
                    $fechaHasta,
                ]
            )
            ->groupBy('metodo')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Servicios más solicitados
        |--------------------------------------------------------------------------
        */

        $serviciosMasSolicitados = Servicio::select(
            'servicios.id',
            'servicios.nombre'
        )
            ->join(
                'detalle_orden',
                'servicios.id',
                '=',
                'detalle_orden.servicio_id'
            )
            ->join(
                'ordenes_trabajo',
                'detalle_orden.orden_trabajo_id',
                '=',
                'ordenes_trabajo.id'
            )
            ->whereBetween(
                'ordenes_trabajo.fecha_ingreso',
                [
                    $fechaDesde,
                    $fechaHasta,
                ]
            )
            ->selectRaw(
                'COUNT(detalle_orden.id) as veces_solicitado'
            )
            ->selectRaw(
                'SUM(detalle_orden.precio) as total_generado'
            )
            ->groupBy(
                'servicios.id',
                'servicios.nombre'
            )
            ->orderByDesc('veces_solicitado')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Productividad de mecánicos
        |--------------------------------------------------------------------------
        */

        $mecanicos = Mecanico::with('especialidad')
            ->withCount([
                'ordenesTrabajo as ordenes_periodo' =>
                    function ($consulta) use (
                        $fechaDesde,
                        $fechaHasta
                    ) {
                        $consulta->whereBetween(
                            'fecha_ingreso',
                            [
                                $fechaDesde,
                                $fechaHasta,
                            ]
                        );
                    },
            ])
            ->orderByDesc('ordenes_periodo')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Repuestos con stock bajo
        |--------------------------------------------------------------------------
        |
        | Este listado refleja el inventario actual, no el periodo.
        |
        */

        $repuestosStockBajo = Repuesto::whereColumn(
            'stock',
            '<=',
            'stock_minimo'
        )
            ->orderBy('stock')
            ->orderBy('nombre')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Últimas órdenes del periodo
        |--------------------------------------------------------------------------
        */

        $ordenesRecientes = OrdenTrabajo::with([
            'vehiculo.cliente',
            'mecanico',
        ])
            ->whereBetween(
                'fecha_ingreso',
                [
                    $fechaDesde,
                    $fechaHasta,
                ]
            )
            ->latest('fecha_ingreso')
            ->latest('id')
            ->take(10)
            ->get();

        return view(
            'reportes.index',
            compact(
                'fechaDesde',
                'fechaHasta',
                'totalOrdenes',
                'valorOrdenes',
                'totalCobrado',
                'saldoPendiente',
                'ordenesPorEstado',
                'pagosPorMetodo',
                'serviciosMasSolicitados',
                'mecanicos',
                'repuestosStockBajo',
                'ordenesRecientes'
            )
        );
    }
}