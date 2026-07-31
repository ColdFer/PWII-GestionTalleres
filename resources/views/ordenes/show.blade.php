@extends('layouts.app')

@section('title', 'Detalle de orden')

@section('content')

@php
    $subtotalServicios = $ordenTrabajo->servicios->sum(
        fn ($servicio) => (float) $servicio->pivot->precio
    );

    $subtotalRepuestos = $ordenTrabajo->repuestos->sum(
        fn ($repuesto) => (float) $repuesto->pivot->subtotal
    );
@endphp

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-3">

        <h2>
            Orden {{ $ordenTrabajo->codigo }}
        </h2>

        <a
            href="{{ route('ordenes.index') }}"
            class="btn btn-secondary">

            Volver

        </a>

    </div>

    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    {{-- Información general de la orden --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Información de la orden
        </div>

        <div class="card-body">

            <p>
                <strong>Cliente:</strong>

                {{ $ordenTrabajo->vehiculo->cliente->nombre }}
                {{ $ordenTrabajo->vehiculo->cliente->apellido }}
            </p>

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
                <strong>Estado:</strong>

                {{ $ordenTrabajo->estado }}
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
                <strong>Registrada por:</strong>

                {{ $ordenTrabajo->creadoPor?->name
                    ?? 'Usuario no disponible' }}
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
                    ?? 'Sin diagnóstico' }}
            </p>

            <p class="mb-0">
                <strong>Observaciones:</strong>

                {{ $ordenTrabajo->observaciones
                    ?? 'Sin observaciones' }}
            </p>

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
                                    colspan="2"
                                    class="text-center">

                                    No existen servicios registrados
                                    en esta orden.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>

                            <th>
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

            @if (
                !in_array(
                    $ordenTrabajo->estado,
                    ['Entregada', 'Cancelada'],
                    true
                )
            )

                <form
                    action="{{ route(
                        'ordenes.repuestos.store',
                        $ordenTrabajo
                    ) }}"
                    method="POST"
                    class="row g-3 mb-4">

                    @csrf

                    <div class="col-md-7">

                        <label
                            for="repuesto_id"
                            class="form-label">

                            Repuesto

                        </label>

                        <select
                            name="repuesto_id"
                            id="repuesto_id"
                            class="form-select
                                @error('repuesto_id')
                                    is-invalid
                                @enderror"
                            required>

                            <option value="">
                                Seleccione un repuesto
                            </option>

                            @foreach (
                                $repuestosDisponibles as $repuesto
                            )

                                <option
                                    value="{{ $repuesto->id }}"
                                    @selected(
                                        old('repuesto_id')
                                        == $repuesto->id
                                    )>

                                    {{ $repuesto->codigo }}
                                    —
                                    {{ $repuesto->nombre }}
                                    —
                                    Stock: {{ $repuesto->stock }}
                                    —
                                    Bs {{ number_format(
                                        $repuesto->precio_venta,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                        @error('repuesto_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="col-md-3">

                        <label
                            for="cantidad"
                            class="form-label">

                            Cantidad

                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            id="cantidad"
                            min="1"
                            value="{{ old('cantidad', 1) }}"
                            class="form-control
                                @error('cantidad')
                                    is-invalid
                                @enderror"
                            required>

                        @error('cantidad')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            Agregar

                        </button>

                    </div>

                </form>

            @else

                <div class="alert alert-info">

                    Esta orden está {{ strtolower(
                        $ordenTrabajo->estado
                    ) }} y sus repuestos ya no pueden modificarse.

                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Repuesto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
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

                                <td>

                                    @if (
                                        !in_array(
                                            $ordenTrabajo->estado,
                                            [
                                                'Entregada',
                                                'Cancelada',
                                            ],
                                            true
                                        )
                                    )

                                        <form
                                            action="{{ route(
                                                'ordenes.repuestos.destroy',
                                                [
                                                    $ordenTrabajo,
                                                    $repuesto,
                                                ]
                                            ) }}"
                                            method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger
                                                       btn-sm"
                                                onclick="return confirm(
                                                    '¿Retirar este repuesto de la orden?'
                                                )">

                                                Retirar

                                            </button>

                                        </form>

                                    @else

                                        <span class="text-muted">
                                            No disponible
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
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

                            <th colspan="2">
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

    {{-- Resumen total --}}
    <div class="card shadow-sm">

    {{-- Pagos --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            Pagos de la orden
        </div>

        <div class="card-body">

            @if (
                $ordenTrabajo->estado !== 'Cancelada'
                && $ordenTrabajo->saldo_pendiente > 0
            )

                <form
                    action="{{ route(
                        'ordenes.pagos.store',
                        $ordenTrabajo
                    ) }}"
                    method="POST"
                    class="row g-3 mb-4">

                    @csrf

                    <div class="col-md-3">

                        <label for="fecha" class="form-label">
                            Fecha
                        </label>

                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            max="{{ now()->format('Y-m-d') }}"
                            value="{{ old(
                                'fecha',
                                now()->format('Y-m-d')
                            ) }}"
                            class="form-control
                                @error('fecha')
                                    is-invalid
                                @enderror"
                            required>

                        @error('fecha')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-2">

                        <label for="monto" class="form-label">
                            Monto
                        </label>

                        <input
                            type="number"
                            name="monto"
                            id="monto"
                            min="0.01"
                            step="0.01"
                            max="{{ $ordenTrabajo
                                ->saldo_pendiente }}"
                            value="{{ old('monto') }}"
                            placeholder="Saldo: {{ number_format(
                                $ordenTrabajo->saldo_pendiente,
                                2,
                                ',',
                                '.'
                            ) }}"
                            class="form-control
                                @error('monto')
                                    is-invalid
                                @enderror"
                            required>

                        @error('monto')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-3">

                        <label for="metodo" class="form-label">
                            Método
                        </label>

                        <select
                            name="metodo"
                            id="metodo"
                            class="form-select
                                @error('metodo')
                                    is-invalid
                                @enderror"
                            required>

                            <option value="">
                                Seleccione
                            </option>

                            @foreach ([
                                'Efectivo',
                                'QR',
                                'Transferencia',
                                'Tarjeta',
                                'Otro',
                            ] as $metodo)

                                <option
                                    value="{{ $metodo }}"
                                    @selected(
                                        old('metodo')
                                        === $metodo
                                    )>

                                    {{ $metodo }}

                                </option>

                            @endforeach

                        </select>

                        @error('metodo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-4">

                        <label
                            for="referencia"
                            class="form-label">

                            Referencia

                        </label>

                        <input
                            type="text"
                            name="referencia"
                            id="referencia"
                            maxlength="100"
                            value="{{ old('referencia') }}"
                            placeholder="Opcional"
                            class="form-control">

                    </div>

                    <div class="col-12">

                        <label
                            for="observaciones_pago"
                            class="form-label">

                            Observaciones

                        </label>

                        <textarea
                            name="observaciones"
                            id="observaciones_pago"
                            rows="2"
                            maxlength="500"
                            class="form-control">{{ old(
                                'observaciones'
                            ) }}</textarea>

                    </div>

                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-success">

                            Registrar pago

                        </button>

                    </div>

                </form>

            @elseif ($ordenTrabajo->estado === 'Cancelada')

                <div class="alert alert-danger">
                    No se pueden registrar pagos en una orden cancelada.
                </div>

            @else

                <div class="alert alert-success">
                    La orden está completamente pagada.
                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Registrado por</th>
                            <th>Acción</th>
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

                                <td>
                                    {{ $pago
                                        ->registradoPor
                                        ?->name
                                        ?? 'Usuario no disponible' }}
                                </td>

                                <td>

                                    <form
                                        action="{{ route(
                                            'ordenes.pagos.destroy',
                                            [
                                                $ordenTrabajo,
                                                $pago,
                                            ]
                                        ) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm(
                                                '¿Eliminar este pago?'
                                            )">

                                            Eliminar

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center">

                                    No existen pagos registrados.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="row text-center">

                <div class="col-md-4">

                    <p class="text-muted mb-1">
                        Total de la orden
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
                        Total pagado
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

</div>

@endsection