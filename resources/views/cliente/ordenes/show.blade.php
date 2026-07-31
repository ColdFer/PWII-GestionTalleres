@extends('layouts.cliente')

@section('title', 'Detalle de reparación')

@section('content')

@php
    $etapas = [
        'Pendiente',
        'En diagnóstico',
        'En reparación',
        'Finalizada',
        'Entregada',
    ];

    $indiceActual = array_search(
        $ordenTrabajo->estado,
        $etapas,
        true
    );

    $porcentaje = $indiceActual === false
        ? 0
        : (($indiceActual + 1) / count($etapas)) * 100;

    $claseEstado = match ($ordenTrabajo->estado) {
        'Pendiente' => 'text-bg-secondary',
        'En diagnóstico' => 'text-bg-info',
        'En reparación' => 'text-bg-warning',
        'Finalizada' => 'text-bg-success',
        'Entregada' => 'text-bg-primary',
        'Cancelada' => 'text-bg-danger',
        default => 'text-bg-dark',
    };

    $subtotalServicios = $ordenTrabajo->servicios->sum(
        fn ($servicio) => (float) $servicio->pivot->precio
    );

    $subtotalRepuestos = $ordenTrabajo->repuestos->sum(
        fn ($repuesto) => (float) $repuesto->pivot->subtotal
    );
@endphp

<div class="d-flex justify-content-between
            align-items-center mb-4">

    <div>

        <h1 class="h3">
            Orden {{ $ordenTrabajo->codigo }}
        </h1>

        <span class="badge {{ $claseEstado }}">
            {{ $ordenTrabajo->estado }}
        </span>

    </div>

    <a
        href="{{ route('cliente.ordenes.index') }}"
        class="btn btn-secondary">

        Volver

    </a>

</div>

@if ($ordenTrabajo->estado === 'Cancelada')

    <div class="alert alert-danger">

        Esta orden de trabajo fue cancelada.

    </div>

@else

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h2 class="h5">
                Progreso de la reparación
            </h2>

            <div
                class="progress mb-3"
                role="progressbar"
                aria-label="Progreso de reparación"
                aria-valuenow="{{ $porcentaje }}"
                aria-valuemin="0"
                aria-valuemax="100">

                <div
                    class="progress-bar"
                    style="width: {{ $porcentaje }}%">

                    {{ number_format(
                        $porcentaje,
                        0
                    ) }}%

                </div>

            </div>

            <div class="row text-center g-2">

                @foreach ($etapas as $indice => $etapa)

                    <div class="col">

                        <span
                            class="badge
                            {{ $indiceActual !== false
                                && $indice <= $indiceActual
                                    ? 'text-bg-success'
                                    : 'text-bg-secondary' }}">

                            {{ $etapa }}

                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

@endif

<div class="row g-4 mb-4">

    <div class="col-md-6">

        <div class="card h-100 shadow-sm">

            <div class="card-header">
                Información de la orden
            </div>

            <div class="card-body">

                <p>
                    <strong>Vehículo:</strong>

                    {{ $ordenTrabajo->vehiculo->placa }}

                    @if ($ordenTrabajo->vehiculo->modelo)

                        —

                        {{ $ordenTrabajo->vehiculo
                            ->modelo
                            ->marca
                            ->nombre }}

                        {{ $ordenTrabajo->vehiculo
                            ->modelo
                            ->nombre }}

                    @endif
                </p>

                <p>
                    <strong>Fecha de ingreso:</strong>

                    {{ $ordenTrabajo->fecha_ingreso
                        ->format('d/m/Y') }}
                </p>

                <p>
                    <strong>Salida estimada:</strong>

                    {{ $ordenTrabajo->fecha_salida_estimada
                        ? $ordenTrabajo
                            ->fecha_salida_estimada
                            ->format('d/m/Y')
                        : 'No definida' }}
                </p>

                <p>
                    <strong>Mecánico responsable:</strong>

                    @if ($ordenTrabajo->mecanico)

                        {{ $ordenTrabajo->mecanico->nombre }}
                        {{ $ordenTrabajo->mecanico->apellido }}

                        —

                        {{ $ordenTrabajo->mecanico
                            ->especialidad
                            ->nombre }}

                    @else

                        Sin asignar

                    @endif
                </p>

                <p>
                    <strong>Diagnóstico:</strong>

                    {{ $ordenTrabajo->diagnostico
                        ?? 'Pendiente de diagnóstico' }}
                </p>

                <p class="mb-0">

                    <strong>Observaciones:</strong>

                    {{ $ordenTrabajo->observaciones
                        ?? 'Sin observaciones' }}

                </p>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card h-100 shadow-sm">

            <div class="card-header">
                Resumen
            </div>

            <div class="card-body">

                <p>
                    <strong>Código:</strong>

                    {{ $ordenTrabajo->codigo }}
                </p>

                <p>
                    <strong>Estado actual:</strong>

                    {{ $ordenTrabajo->estado }}
                </p>

                <p>
                    <strong>Cantidad de servicios:</strong>

                    {{ $ordenTrabajo->servicios->count() }}
                </p>

                <p>
                    <strong>Cantidad de repuestos:</strong>

                    {{ $ordenTrabajo->repuestos->sum(
                        fn ($repuesto) =>
                            (int) $repuesto->pivot->cantidad
                    ) }}
                </p>

                <p class="mb-0">

                    <strong>Total estimado:</strong>

                    Bs {{ number_format(
                        $ordenTrabajo->total,
                        2,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>

        </div>

    </div>

</div>

{{-- Servicios --}}
<div class="card shadow-sm mb-4">

    <div class="card-header">
        Servicios de la orden
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse (
                        $ordenTrabajo->servicios as $servicio
                    )

                        <tr>

                            <td>
                                {{ $servicio->nombre }}
                            </td>

                            <td>
                                {{ $servicio
                                    ->tipoServicio
                                    ->nombre }}
                            </td>

                            <td>
                                Bs {{ number_format(
                                    $servicio->pivot->precio,
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

                                No existen servicios registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="2">
                            Subtotal servicios
                        </th>

                        <th>
                            Bs {{ number_format(
                                $subtotalServicios,
                                2,
                                ',',
                                '.'
                            ) }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>

{{-- Repuestos --}}
<div class="card shadow-sm mb-4">

    <div class="card-header">
        Repuestos utilizados
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Repuesto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse (
                        $ordenTrabajo->repuestos as $repuesto
                    )

                        <tr>

                            <td>

                                {{ $repuesto->codigo }}
                                —
                                {{ $repuesto->nombre }}

                            </td>

                            <td>
                                {{ $repuesto->pivot->cantidad }}
                            </td>

                            <td>
                                Bs {{ number_format(
                                    $repuesto
                                        ->pivot
                                        ->precio_unitario,
                                    2,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>
                                Bs {{ number_format(
                                    $repuesto
                                        ->pivot
                                        ->subtotal,
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

                                No se utilizaron repuestos.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="3">
                            Subtotal repuestos
                        </th>

                        <th>
                            Bs {{ number_format(
                                $subtotalRepuestos,
                                2,
                                ',',
                                '.'
                            ) }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>

{{-- Total general --}}
<div class="card shadow-sm">
    {{-- Estado de pagos --}}
<div class="card shadow-sm mb-4">

    <div class="card-header">
        Estado de pagos
    </div>

    <div class="card-body">

        <div class="row text-center mb-4">

            <div class="col-md-4">

                <p class="text-muted mb-1">
                    Total
                </p>

                <h5>
                    Bs {{ number_format(
                        $ordenTrabajo->total,
                        2,
                        ',',
                        '.'
                    ) }}
                </h5>

            </div>

            <div class="col-md-4">

                <p class="text-muted mb-1">
                    Pagado
                </p>

                <h5>
                    Bs {{ number_format(
                        $ordenTrabajo->total_pagado,
                        2,
                        ',',
                        '.'
                    ) }}
                </h5>

            </div>

            <div class="col-md-4">

                <p class="text-muted mb-1">
                    Saldo pendiente
                </p>

                <h5>
                    Bs {{ number_format(
                        $ordenTrabajo->saldo_pendiente,
                        2,
                        ',',
                        '.'
                    ) }}
                </h5>

                <span class="badge
                    {{
                        $ordenTrabajo->estado_pago === 'Pagado'
                            ? 'text-bg-success'
                            : (
                                $ordenTrabajo->estado_pago === 'Parcial'
                                    ? 'text-bg-warning'
                                    : 'text-bg-danger'
                            )
                    }}">

                    {{ $ordenTrabajo->estado_pago }}

                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead class="table-light">

                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th>Monto</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($ordenTrabajo->pagos as $pago)

                        <tr>

                            <td>
                                {{ $pago->fecha
                                    ->format('d/m/Y') }}
                            </td>

                            <td>{{ $pago->metodo }}</td>

                            <td>
                                {{ $pago->referencia
                                    ?? 'Sin referencia' }}
                            </td>

                            <td>
                                Bs {{ number_format(
                                    $pago->monto,
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

                                No existen pagos registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
    <div class="card-header">
        Resumen económico
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3 mb-md-0">

                <p class="text-muted mb-1">
                    Servicios
                </p>

                <h5 class="mb-0">

                    Bs {{ number_format(
                        $subtotalServicios,
                        2,
                        ',',
                        '.'
                    ) }}

                </h5>

            </div>

            <div class="col-md-4 mb-3 mb-md-0">

                <p class="text-muted mb-1">
                    Repuestos
                </p>

                <h5 class="mb-0">

                    Bs {{ number_format(
                        $subtotalRepuestos,
                        2,
                        ',',
                        '.'
                    ) }}

                </h5>

            </div>

            <div class="col-md-4">

                <p class="text-muted mb-1">
                    Total de la orden
                </p>

                <h4 class="mb-0">

                    Bs {{ number_format(
                        $ordenTrabajo->total,
                        2,
                        ',',
                        '.'
                    ) }}

                </h4>

            </div>

        </div>

    </div>

</div>

@endsection