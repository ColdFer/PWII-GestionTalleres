<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;

class PortalClienteController extends Controller
{
    public function dashboard(Request $request)
    {
        $cliente = $request->user()->cliente;

        abort_if(
            !$cliente,
            403,
            'La cuenta no está vinculada a un cliente.'
        );

        /*
        |--------------------------------------------------------------------------
        | Cantidad de vehículos
        |--------------------------------------------------------------------------
        */

        $cliente->loadCount('vehiculos');

        /*
        |--------------------------------------------------------------------------
        | Consulta base de órdenes del cliente
        |--------------------------------------------------------------------------
        |
        | Se buscan solamente órdenes cuyo vehículo pertenezca
        | al cliente autenticado.
        |
        */

        $ordenesBase = OrdenTrabajo::whereHas(
            'vehiculo',
            function ($consulta) use ($cliente) {
                $consulta->where(
                    'cliente_id',
                    $cliente->id
                );
            }
        );

        $totalOrdenes = (clone $ordenesBase)->count();

        $ordenesActivas = (clone $ordenesBase)
            ->whereNotIn(
                'estado',
                [
                    'Entregada',
                    'Cancelada',
                ]
            )
            ->count();

        $ordenesRecientes = (clone $ordenesBase)
            ->with([
                'vehiculo',
                'servicios',
            ])
            ->latest('fecha_ingreso')
            ->latest('id')
            ->take(5)
            ->get();

        return view(
            'cliente.dashboard',
            compact(
                'cliente',
                'totalOrdenes',
                'ordenesActivas',
                'ordenesRecientes'
            )
        );
    }

    public function vehiculos(Request $request)
    {
        $cliente = $request->user()->cliente;

        abort_if(
            !$cliente,
            403,
            'La cuenta no está vinculada a un cliente.'
        );

        $vehiculos = $cliente
            ->vehiculos()
            ->with('modelo.marca')
            ->orderBy('placa')
            ->get();

        return view(
            'cliente.vehiculos',
            compact(
                'cliente',
                'vehiculos'
            )
        );
    }

    public function ordenes(Request $request)
    {
        $cliente = $request->user()->cliente;

        abort_if(
            !$cliente,
            403,
            'La cuenta no está vinculada a un cliente.'
        );

        $ordenes = OrdenTrabajo::with([
            'vehiculo',
            'servicios',
        ])
            ->whereHas(
                'vehiculo',
                function ($consulta) use ($cliente) {
                    $consulta->where(
                        'cliente_id',
                        $cliente->id
                    );
                }
            )
            ->latest('fecha_ingreso')
            ->latest('id')
            ->get();

        return view(
            'cliente.ordenes.index',
            compact(
                'cliente',
                'ordenes'
            )
        );
    }

    public function mostrarOrden(
        Request $request,
        OrdenTrabajo $ordenTrabajo
    ) {
        $cliente = $request->user()->cliente;

        abort_if(
            !$cliente,
            403,
            'La cuenta no está vinculada a un cliente.'
        );

        $ordenTrabajo->load([
            'vehiculo.modelo.marca',
            'servicios.tipoServicio',
            'repuestos',
            'pagos',
            'mecanico.especialidad',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Comprobar propietario
        |--------------------------------------------------------------------------
        |
        | La orden solamente se muestra cuando su vehículo pertenece
        | al cliente autenticado.
        |
        */

        abort_if(
            $ordenTrabajo->vehiculo->cliente_id !== $cliente->id,
            403,
            'No tiene permiso para consultar esta orden.'
        );

        return view(
            'cliente.ordenes.show',
            compact(
                'cliente',
                'ordenTrabajo'
            )
        );
    }
}