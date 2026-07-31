<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with([
            'ordenTrabajo.vehiculo.cliente',
            'registradoPor',
        ])
            ->latest('fecha')
            ->latest('id')
            ->get();

        $totalCobrado = $pagos->sum(
            fn ($pago) => (float) $pago->monto
        );

        return view(
            'pagos.index',
            compact(
                'pagos',
                'totalCobrado'
            )
        );
    }

    public function store(
        Request $request,
        OrdenTrabajo $ordenTrabajo
    ) {
        $datos = $request->validate(
            [
                'fecha' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],
                'monto' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'metodo' => [
                    'required',
                    Rule::in([
                        'Efectivo',
                        'QR',
                        'Transferencia',
                        'Tarjeta',
                        'Otro',
                    ]),
                ],
                'referencia' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
                'observaciones' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ],
            [
                'fecha.required' =>
                    'La fecha del pago es obligatoria.',

                'fecha.before_or_equal' =>
                    'La fecha del pago no puede ser futura.',

                'monto.required' =>
                    'El monto es obligatorio.',

                'monto.gt' =>
                    'El monto debe ser mayor que cero.',

                'metodo.required' =>
                    'Debe seleccionar un método de pago.',

                'metodo.in' =>
                    'El método de pago no es válido.',
            ]
        );

        DB::transaction(function () use (
            $datos,
            $request,
            $ordenTrabajo
        ) {
            $ordenBloqueada = OrdenTrabajo::whereKey(
                $ordenTrabajo->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($ordenBloqueada->estado === 'Cancelada') {
                throw ValidationException::withMessages([
                    'monto' =>
                        'No se pueden registrar pagos en una orden cancelada.',
                ]);
            }

            $totalPagado = round(
                (float) $ordenBloqueada
                    ->pagos()
                    ->sum('monto'),
                2
            );

            $saldo = round(
                (float) $ordenBloqueada->total
                - $totalPagado,
                2
            );

            $monto = round(
                (float) $datos['monto'],
                2
            );

            if ($saldo <= 0) {
                throw ValidationException::withMessages([
                    'monto' =>
                        'La orden ya está completamente pagada.',
                ]);
            }

            if ($monto > $saldo) {
                throw ValidationException::withMessages([
                    'monto' =>
                        'El pago no puede superar el saldo pendiente de Bs '
                        .number_format(
                            $saldo,
                            2,
                            ',',
                            '.'
                        ).'.',
                ]);
            }

            Pago::create([
                'orden_trabajo_id' =>
                    $ordenBloqueada->id,

                'user_id' =>
                    $request->user()->id,

                'fecha' =>
                    $datos['fecha'],

                'monto' =>
                    $monto,

                'metodo' =>
                    $datos['metodo'],

                'referencia' =>
                    $datos['referencia'] ?? null,

                'observaciones' =>
                    $datos['observaciones'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'Pago registrado correctamente.'
        );
    }

    public function destroy(
        OrdenTrabajo $ordenTrabajo,
        Pago $pago
    ) {
        if (
            $pago->orden_trabajo_id
            !== $ordenTrabajo->id
        ) {
            abort(
                404,
                'El pago no pertenece a esta orden.'
            );
        }

        $pago->delete();

        return back()->with(
            'success',
            'Pago eliminado correctamente.'
        );
    }
}