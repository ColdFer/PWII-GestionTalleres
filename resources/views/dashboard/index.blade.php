@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid mt-4">

    <div class="mb-4">

        <h2>Panel Administrativo</h2>

        <p class="text-muted mb-0">
            Resumen general de la actividad del taller.
        </p>

    </div>

    {{-- Primera fila de indicadores --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Clientes
                            </p>

                            <h3 class="mb-0">
                                {{ $totalClientes }}
                            </h3>

                        </div>

                        <div class="fs-1">
                            👥
                        </div>

                    </div>

                    @if (
                        auth()->user()
                            ->tienePermiso('clientes.gestionar')
                    )

                        <a
                            href="{{ route('clientes.index') }}"
                            class="btn btn-outline-primary
                                   btn-sm mt-3">

                            Ver clientes

                        </a>

                    @endif

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Vehículos
                            </p>

                            <h3 class="mb-0">
                                {{ $totalVehiculos }}
                            </h3>

                        </div>

                        <div class="fs-1">
                            🚗
                        </div>

                    </div>

                    @if (
                        auth()->user()
                            ->tienePermiso('vehiculos.gestionar')
                    )

                        <a
                            href="{{ route('vehiculos.index') }}"
                            class="btn btn-outline-primary
                                   btn-sm mt-3">

                            Ver vehículos

                        </a>

                    @endif

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Servicios disponibles
                            </p>

                            <h3 class="mb-0">
                                {{ $totalServicios }}
                            </h3>

                        </div>

                        <div class="fs-1">
                            🛠️
                        </div>

                    </div>

                    @if (
                        auth()->user()
                            ->tienePermiso('servicios.gestionar')
                    )

                        <a
                            href="{{ route('servicios.index') }}"
                            class="btn btn-outline-primary
                                   btn-sm mt-3">

                            Ver servicios

                        </a>

                    @endif

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Órdenes totales
                            </p>

                            <h3 class="mb-0">
                                {{ $totalOrdenes }}
                            </h3>

                        </div>

                        <div class="fs-1">
                            📋
                        </div>

                    </div>

                    @if (
                        auth()->user()
                            ->tienePermiso('ordenes.gestionar')
                    )

                        <a
                            href="{{ route('ordenes.index') }}"
                            class="btn btn-outline-primary
                                   btn-sm mt-3">

                            Ver órdenes

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Segunda fila --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Órdenes activas
                            </p>

                            <h3 class="mb-0">
                                {{ $ordenesActivas }}
                            </h3>

                            <small class="text-muted">
                                Pendientes, en diagnóstico,
                                reparación o finalizadas.
                            </small>

                        </div>

                        <div class="fs-1">
                            🔧
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Valor de órdenes no canceladas
                            </p>

                            <h3 class="mb-0">

                                Bs {{ number_format(
                                    $montoOrdenes,
                                    2,
                                    ',',
                                    '.'
                                ) }}

                            </h3>

                            <small class="text-muted">
                                Este valor no representa
                                necesariamente pagos recibidos.
                            </small>

                        </div>

                        <div class="fs-1">
                            💰
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        {{-- Resumen por estado --}}
        <div class="col-lg-4">

            <div class="card h-100 shadow-sm">

                <div class="card-header">
                    Órdenes por estado
                </div>

                <div class="card-body">

                    @php
                        $estados = [
                            'Pendiente',
                            'En diagnóstico',
                            'En reparación',
                            'Finalizada',
                            'Entregada',
                            'Cancelada',
                        ];

                        $clasesEstados = [
                            'Pendiente' =>
                                'text-bg-secondary',

                            'En diagnóstico' =>
                                'text-bg-info',

                            'En reparación' =>
                                'text-bg-warning',

                            'Finalizada' =>
                                'text-bg-success',

                            'Entregada' =>
                                'text-bg-primary',

                            'Cancelada' =>
                                'text-bg-danger',
                        ];
                    @endphp

                    <ul class="list-group list-group-flush">

                        @foreach ($estados as $estado)

                            <li class="list-group-item
                                       d-flex
                                       justify-content-between
                                       align-items-center">

                                <span>
                                    {{ $estado }}
                                </span>

                                <span
                                    class="badge
                                    {{ $clasesEstados[$estado] }}
                                    rounded-pill">

                                    {{ $ordenesPorEstado[$estado]
                                        ?? 0 }}

                                </span>

                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

        {{-- Órdenes recientes --}}
        <div class="col-lg-8">

            <div class="card h-100 shadow-sm">

                <div class="card-header
                            d-flex
                            justify-content-between
                            align-items-center">

                    <span>
                        Últimas órdenes registradas
                    </span>

                    @if (
                        auth()->user()
                            ->tienePermiso('ordenes.gestionar')
                    )

                        <a
                            href="{{ route('ordenes.index') }}"
                            class="btn btn-primary btn-sm">

                            Ver todas

                        </a>

                    @endif

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table
                                      table-hover
                                      align-middle mb-0">

                            <thead>

                                <tr>
                                    <th>Código</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Registrada por</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse (
                                    $ordenesRecientes as $orden
                                )

                                    @php
                                        $claseEstado = match (
                                            $orden->estado
                                        ) {
                                            'Pendiente' =>
                                                'text-bg-secondary',

                                            'En diagnóstico' =>
                                                'text-bg-info',

                                            'En reparación' =>
                                                'text-bg-warning',

                                            'Finalizada' =>
                                                'text-bg-success',

                                            'Entregada' =>
                                                'text-bg-primary',

                                            'Cancelada' =>
                                                'text-bg-danger',

                                            default =>
                                                'text-bg-dark',
                                        };
                                    @endphp

                                    <tr>

                                        <td>

                                            @if (
                                                auth()->user()
                                                    ->tienePermiso(
                                                        'ordenes.gestionar'
                                                    )
                                            )

                                                <a
                                                    href="{{ route(
                                                        'ordenes.show',
                                                        $orden
                                                    ) }}">

                                                    {{ $orden->codigo }}

                                                </a>

                                            @else

                                                {{ $orden->codigo }}

                                            @endif

                                        </td>

                                        <td>
                                            {{ $orden
                                                ->vehiculo
                                                ->cliente
                                                ->nombre }}

                                            {{ $orden
                                                ->vehiculo
                                                ->cliente
                                                ->apellido }}
                                        </td>

                                        <td>
                                            {{ $orden
                                                ->vehiculo
                                                ->placa }}
                                        </td>

                                        <td>

                                            <span
                                                class="badge
                                                       {{ $claseEstado }}">

                                                {{ $orden->estado }}

                                            </span>

                                        </td>

                                        <td>
                                            Bs {{ number_format(
                                                $orden->total,
                                                2,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                        <td>
                                            {{ $orden
                                                ->creadoPor
                                                ?->name
                                                ?? 'Usuario no disponible' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center
                                                   text-muted">

                                            No existen órdenes
                                            registradas.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection