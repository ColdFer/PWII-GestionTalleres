<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Servicio;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Contadores generales
        |--------------------------------------------------------------------------
        */

        $totalClientes = Cliente::count();

        $totalVehiculos = Vehiculo::count();

        $totalServicios = Servicio::count();

        $totalOrdenes = OrdenTrabajo::count();

        /*
        |--------------------------------------------------------------------------
        | Órdenes activas
        |--------------------------------------------------------------------------
        |
        | Consideramos activas todas las órdenes que todavía no fueron
        | entregadas ni canceladas.
        |
        */

        $ordenesActivas = OrdenTrabajo::whereNotIn(
            'estado',
            [
                'Entregada',
                'Cancelada',
            ]
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Monto total registrado
        |--------------------------------------------------------------------------
        |
        | No sumamos órdenes canceladas.
        |
        | Este monto representa el valor de las órdenes registradas,
        | no necesariamente dinero ya pagado.
        |
        */

        $montoOrdenes = OrdenTrabajo::where(
            'estado',
            '!=',
            'Cancelada'
        )->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Cantidad de órdenes por estado
        |--------------------------------------------------------------------------
        */

        $ordenesPorEstado = OrdenTrabajo::select(
            'estado',
            DB::raw('COUNT(*) as cantidad')
        )
            ->groupBy('estado')
            ->pluck('cantidad', 'estado');

        /*
        |--------------------------------------------------------------------------
        | Últimas órdenes
        |--------------------------------------------------------------------------
        */

        $ordenesRecientes = OrdenTrabajo::with([
            'vehiculo.cliente',
            'creadoPor',
        ])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view(
            'dashboard.index',
            compact(
                'totalClientes',
                'totalVehiculos',
                'totalServicios',
                'totalOrdenes',
                'ordenesActivas',
                'montoOrdenes',
                'ordenesPorEstado',
                'ordenesRecientes'
            )
        );
    }
}