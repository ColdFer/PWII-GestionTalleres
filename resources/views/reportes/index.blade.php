@extends('layouts.app')

@section('title', 'Reportes')

@section('content')

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #reporte,
        #reporte * {
            visibility: visible;
        }

        #reporte {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        .card {
            break-inside: avoid;
            box-shadow: none !important;
        }

        a {
            color: black !important;
            text-decoration: none !important;
        }
    }
</style>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-4 no-print">

        <div>

            <h2>Reportes del Taller</h2>

            <p class="text-muted mb-0">
                Consulte los resultados del taller por periodo.
            </p>

        </div>

        <button
            type="button"
            class="btn btn-dark"
            onclick="window.print()">

            🖨️ Imprimir reporte

        </button>

    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4 no-print">

        <div class="card-header">
            Filtros
        </div>

        <div class="card-body">

            <form
                action="{{ route('reportes.index') }}"
                method="GET"
                class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label
                        for="fecha_desde"
                        class="form-label">

                        Fecha desde

                    </label>

                    <input
                        type="date"
                        name="fecha_desde"
                        id="fecha_desde"
                        value="{{ old(
                            'fecha_desde',
                            $fechaDesde
                        ) }}"
                        class="form-control
                            @error('fecha_desde')
                                is-invalid
                            @enderror">

                    @error('fecha_desde')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                <div class="col-md-4">

                    <label
                        for="fecha_hasta"
                        class="form-label">

                        Fecha hasta

                    </label>

                    <input
                        type="date"
                        name="fecha_hasta"
                        id="fecha_hasta"
                        value="{{ old(
                            'fecha_hasta',
                            $fechaHasta
                        ) }}"
                        class="form-control
                            @error('fecha_hasta')
                                is-invalid
                            @enderror">

                    @error('fecha_hasta')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                <div class="col-md-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Generar reporte

                    </button>

                    <a
                        href="{{ route('reportes.index') }}"
                        class="btn btn-secondary">

                        Limpiar

                    </a>

                </div>

            </form>

        </div>

    </div>

    <div id="reporte">

        {{-- Encabezado para impresión --}}
        <div class="text-center mb-4">

            <h2>Reporte General del Taller</h2>

            <p class="mb-1">

                Periodo:

                <strong>
                    {{ \Carbon\Carbon::parse(
                        $fechaDesde
                    )->format('d/m/Y') }}

                    al

                    {{ \Carbon\Carbon::parse(
                        $fechaHasta
                    )->format('d/m/Y') }}
                </strong>

            </p>

            <small class="text-muted">

                Generado el
                {{ now()->format('d/m/Y H:i') }}

                por

                {{ auth()->user()->name }}

            </small>

        </div>

        {{-- Indicadores principales --}}
        <div class="row g-4 mb-4">

            <div class="col-md-6 col-xl-3">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Órdenes registradas
                        </p>

                        <h3 class="mb-0">
                            {{ $totalOrdenes }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-xl-3">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Valor de órdenes
                        </p>

                        <h3 class="mb-0">

                            Bs {{ number_format(
                                $valorOrdenes,
                                2,
                                ',',
                                '.'
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-xl-3">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Pagos recibidos
                        </p>

                        <h3 class="mb-0">

                            Bs {{ number_format(
                                $totalCobrado,
                                2,
                                ',',
                                '.'
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-xl-3">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Saldo pendiente
                        </p>

                        <h3 class="mb-0">

                            Bs {{ number_format(
                                $saldoPendiente,
                                2,
                                ',',
                                '.'
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>

        <div class="row g-4 mb-4">

            {{-- Órdenes por estado --}}
            <div class="col-lg-6">

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

                            $clasesEstado = [
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

                                <li
                                    class="list-group-item
                                           d-flex
                                           justify-content-between
                                           align-items-center">

                                    {{ $estado }}

                                    <span
                                        class="badge
                                               {{ $clasesEstado[$estado] }}
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

            {{-- Pagos por método --}}
            <div class="col-lg-6">

                <div class="card h-100 shadow-sm">

                    <div class="card-header">
                        Pagos por método
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead>

                                    <tr>
                                        <th>Método</th>
                                        <th>Operaciones</th>
                                        <th>Total</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse (
                                        $pagosPorMetodo as $pagoMetodo
                                    )

                                        <tr>

                                            <td>
                                                {{ $pagoMetodo->metodo }}
                                            </td>

                                            <td>
                                                {{ $pagoMetodo->cantidad }}
                                            </td>

                                            <td>
                                                Bs {{ number_format(
                                                    $pagoMetodo->total,
                                                    2,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="text-center">

                                                No existen pagos
                                                en este periodo.

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

        {{-- Servicios más solicitados --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                Servicios más solicitados
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered
                                  table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Posición</th>
                                <th>Servicio</th>
                                <th>Veces solicitado</th>
                                <th>Total generado</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse (
                                $serviciosMasSolicitados
                                as $servicio
                            )

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $servicio->nombre }}
                                    </td>

                                    <td>
                                        {{ $servicio
                                            ->veces_solicitado }}
                                    </td>

                                    <td>
                                        Bs {{ number_format(
                                            $servicio
                                                ->total_generado,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center">

                                        No existen servicios registrados
                                        en el periodo.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="row g-4 mb-4">

            {{-- Mecánicos --}}
            <div class="col-lg-7">

                <div class="card h-100 shadow-sm">

                    <div class="card-header">
                        Órdenes asignadas por mecánico
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered
                                          align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Mecánico</th>
                                        <th>Especialidad</th>
                                        <th>Órdenes</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse ($mecanicos as $mecanico)

                                        <tr>

                                            <td>
                                                {{ $mecanico->nombre }}
                                                {{ $mecanico->apellido }}
                                            </td>

                                            <td>
                                                {{ $mecanico
                                                    ->especialidad
                                                    ?->nombre
                                                    ?? 'Sin especialidad' }}
                                            </td>

                                            <td>
                                                {{ $mecanico
                                                    ->ordenes_periodo }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="text-center">

                                                No existen mecánicos
                                                registrados.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Stock bajo --}}
            <div class="col-lg-5">

                <div class="card h-100 shadow-sm">

                    <div class="card-header">
                        Repuestos con stock bajo
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered
                                          align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Repuesto</th>
                                        <th>Stock</th>
                                        <th>Mínimo</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse (
                                        $repuestosStockBajo
                                        as $repuesto
                                    )

                                        <tr class="table-warning">

                                            <td>
                                                {{ $repuesto->codigo }}
                                                —
                                                {{ $repuesto->nombre }}
                                            </td>

                                            <td>
                                                {{ $repuesto->stock }}
                                            </td>

                                            <td>
                                                {{ $repuesto
                                                    ->stock_minimo }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="text-center">

                                                No existen repuestos
                                                con stock bajo.

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

        {{-- Órdenes recientes --}}
        <div class="card shadow-sm">

            <div class="card-header">
                Últimas órdenes del periodo
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered
                                  table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Código</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Mecánico</th>
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse (
                                $ordenesRecientes as $orden
                            )

                                <tr>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'ordenes.show',
                                                $orden
                                            ) }}"
                                            class="no-print">

                                            {{ $orden->codigo }}

                                        </a>

                                        <span class="d-none d-print-inline">
                                            {{ $orden->codigo }}
                                        </span>

                                    </td>

                                    <td>
                                        {{ $orden->fecha_ingreso
                                            ->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        {{ $orden->vehiculo
                                            ->cliente
                                            ->nombre }}

                                        {{ $orden->vehiculo
                                            ->cliente
                                            ->apellido }}
                                    </td>

                                    <td>
                                        {{ $orden->vehiculo->placa }}
                                    </td>

                                    <td>
                                        {{ $orden->mecanico
                                            ? $orden->mecanico->nombre
                                                .' '
                                                .$orden->mecanico->apellido
                                            : 'Sin asignar' }}
                                    </td>

                                    <td>
                                        {{ $orden->estado }}
                                    </td>

                                    <td>
                                        Bs {{ number_format(
                                            $orden->total,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center">

                                        No existen órdenes
                                        en este periodo.

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

@endsection