@extends('layouts.cliente')

@section('title', 'Mis reparaciones')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3">
            Mis reparaciones
        </h1>

        <p class="text-muted mb-0">
            Consulte el estado de las órdenes registradas
            para sus vehículos.
        </p>

    </div>

    <a
        href="{{ route('cliente.dashboard') }}"
        class="btn btn-secondary">

        Volver

    </a>

</div>

<div class="row g-4">

    @forelse ($ordenes as $orden)

        @php
            $claseEstado = match ($orden->estado) {
                'Pendiente' => 'text-bg-secondary',

                'En diagnóstico' => 'text-bg-info',

                'En reparación' => 'text-bg-warning',

                'Finalizada' => 'text-bg-success',

                'Entregada' => 'text-bg-primary',

                'Cancelada' => 'text-bg-danger',

                default => 'text-bg-dark',
            };
        @endphp

        <div class="col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-header
                            d-flex justify-content-between
                            align-items-center">

                    <strong>
                        {{ $orden->codigo }}
                    </strong>

                    <span class="badge {{ $claseEstado }}">
                        {{ $orden->estado }}
                    </span>

                </div>

                <div class="card-body">

                    <p class="mb-2">

                        <strong>Vehículo:</strong>

                        {{ $orden->vehiculo->placa }}

                    </p>

                    <p class="mb-2">

                        <strong>Fecha de ingreso:</strong>

                        {{ $orden->fecha_ingreso
                            ->format('d/m/Y') }}

                    </p>

                    <p class="mb-2">

                        <strong>Salida estimada:</strong>

                        {{ $orden->fecha_salida_estimada
                            ? $orden->fecha_salida_estimada
                                ->format('d/m/Y')
                            : 'No definida' }}

                    </p>

                    <p class="mb-2">

                        <strong>Servicios:</strong>

                        {{ $orden->servicios->count() }}

                    </p>

                    <p class="mb-3">

                        <strong>Total:</strong>

                        Bs {{ number_format(
                            $orden->total,
                            2,
                            ',',
                            '.'
                        ) }}

                    </p>

                    <a
                        href="{{ route(
                            'cliente.ordenes.show',
                            $orden
                        ) }}"
                        class="btn btn-primary">

                        Ver detalle

                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">

                No tiene órdenes de trabajo registradas.

            </div>

        </div>

    @endforelse

</div>

@endsection