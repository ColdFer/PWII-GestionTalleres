@extends('layouts.cliente')

@section('title', 'Mis vehículos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3">
            Mis vehículos
        </h1>

        <p class="text-muted mb-0">
            Vehículos registrados a nombre de
            {{ $cliente->nombre }}
            {{ $cliente->apellido }}.
        </p>

    </div>

    <a
        href="{{ route('cliente.dashboard') }}"
        class="btn btn-secondary">

        Volver

    </a>

</div>

<div class="row g-4">

    @forelse ($vehiculos as $vehiculo)

        <div class="col-md-6 col-lg-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h2 class="h5">
                        🚗 {{ $vehiculo->placa }}
                    </h2>

                    <hr>

                    <p class="mb-2">

                        <strong>Año:</strong>

                        {{ $vehiculo->anio }}

                    </p>

                    <p class="mb-2">

                        <strong>Color:</strong>

                        {{ $vehiculo->color }}

                    </p>

                    <p class="mb-0">

                        <strong>Kilometraje:</strong>

                        {{ number_format(
                            $vehiculo->kilometraje,
                            0,
                            ',',
                            '.'
                        ) }} km

                    </p>
                    <p class="mb-2">

                        <strong>Marca:</strong>

                        {{ $vehiculo->modelo?->marca?->nombre
                            ?? 'Sin asignar' }}

                    </p>

                    <p class="mb-2">

                        <strong>Modelo:</strong>

                        {{ $vehiculo->modelo?->nombre
                            ?? 'Sin asignar' }}

                    </p>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">

                No tiene vehículos registrados actualmente.

            </div>

        </div>

    @endforelse

</div>

@endsection