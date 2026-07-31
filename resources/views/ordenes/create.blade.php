@extends('layouts.app')

@section('title', 'Nueva orden')

@section('content')

<div class="container mt-4">

    <h2>Nueva Orden de Trabajo</h2>

    <hr>

    <form action="{{ route('ordenes.store') }}" method="POST">

        @csrf

        <div class="mb-3">

            <label for="vehiculo_id" class="form-label">
                Vehículo y propietario
            </label>

            <select
                name="vehiculo_id"
                id="vehiculo_id"
                class="form-select
                    @error('vehiculo_id') is-invalid @enderror"
                required>

                <option value="">
                    Seleccione un vehículo
                </option>

                @foreach ($vehiculos as $vehiculo)

                    <option
                        value="{{ $vehiculo->id }}"
                        @selected(
                            old('vehiculo_id') == $vehiculo->id
                        )>

                        {{ $vehiculo->placa }}
                        —
                        {{ $vehiculo->cliente->nombre }}
                        {{ $vehiculo->cliente->apellido }}

                    </option>

                @endforeach

            </select>

            @error('vehiculo_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        <div class="mb-3">

            <label for="mecanico_id" class="form-label">
                Mecánico responsable
            </label>

            <select
                name="mecanico_id"
                id="mecanico_id"
                class="form-select
                    @error('mecanico_id') is-invalid @enderror">

                <option value="">
                    Sin asignar por el momento
                </option>

                @foreach ($mecanicos as $mecanico)

                    <option
                        value="{{ $mecanico->id }}"
                        @selected(
                            old('mecanico_id')
                            == $mecanico->id
                        )>

                        {{ $mecanico->nombre }}
                        {{ $mecanico->apellido }}
                        —
                        {{ $mecanico->especialidad->nombre }}

                    </option>

                @endforeach

            </select>

            @error('mecanico_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="row">

            <div class="col-md-6 mb-3">

                <label for="fecha_ingreso" class="form-label">
                    Fecha de ingreso
                </label>

                <input
                    type="date"
                    name="fecha_ingreso"
                    id="fecha_ingreso"
                    value="{{ old(
                        'fecha_ingreso',
                        now()->format('Y-m-d')
                    ) }}"
                    class="form-control
                        @error('fecha_ingreso') is-invalid @enderror"
                    required>

                @error('fecha_ingreso')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-6 mb-3">

                <label
                    for="fecha_salida_estimada"
                    class="form-label">

                    Salida estimada

                </label>

                <input
                    type="date"
                    name="fecha_salida_estimada"
                    id="fecha_salida_estimada"
                    value="{{ old('fecha_salida_estimada') }}"
                    class="form-control
                        @error('fecha_salida_estimada')
                            is-invalid
                        @enderror">

                @error('fecha_salida_estimada')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="mb-3">

            <label for="diagnostico" class="form-label">
                Diagnóstico inicial
            </label>

            <textarea
                name="diagnostico"
                id="diagnostico"
                rows="3"
                maxlength="1000"
                class="form-control
                    @error('diagnostico') is-invalid @enderror">{{ old('diagnostico') }}</textarea>

            @error('diagnostico')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label class="form-label">
                Servicios
            </label>

            <div class="border rounded p-3
                @error('servicios') border-danger @enderror">

                @forelse ($servicios as $servicio)

                    <div class="form-check mb-2">

                        <input
                            type="checkbox"
                            name="servicios[]"
                            value="{{ $servicio->id }}"
                            id="servicio{{ $servicio->id }}"
                            class="form-check-input servicio-checkbox"
                            data-precio="{{ $servicio->precio }}"
                            @checked(
                                in_array(
                                    $servicio->id,
                                    old('servicios', [])
                                )
                            )>

                        <label
                            for="servicio{{ $servicio->id }}"
                            class="form-check-label">

                            {{ $servicio->nombre }}

                            <span class="text-muted">
                                — {{ $servicio->tipoServicio->nombre }}
                                — Bs {{ number_format(
                                    $servicio->precio,
                                    2,
                                    ',',
                                    '.'
                                ) }}
                            </span>

                        </label>

                    </div>

                @empty

                    <div class="alert alert-warning mb-0">
                        No existen servicios registrados.
                    </div>

                @endforelse

            </div>

            @error('servicios')
                <div class="text-danger small mt-1">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="alert alert-info">

            <strong>Total estimado:</strong>

            Bs <span id="totalEstimado">0,00</span>

        </div>

        <div class="mb-3">

            <label for="observaciones" class="form-label">
                Observaciones
            </label>

            <textarea
                name="observaciones"
                id="observaciones"
                rows="3"
                maxlength="1000"
                class="form-control
                    @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>

            @error('observaciones')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Guardar orden
        </button>

        <a
            href="{{ route('ordenes.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const casillas = document.querySelectorAll(
            '.servicio-checkbox'
        );

        const totalElemento =
            document.getElementById('totalEstimado');

        function calcularTotal() {
            let total = 0;

            casillas.forEach(function (casilla) {
                if (casilla.checked) {
                    total += Number(casilla.dataset.precio);
                }
            });

            totalElemento.textContent =
                total.toLocaleString('es-BO', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        }

        casillas.forEach(function (casilla) {
            casilla.addEventListener(
                'change',
                calcularTotal
            );
        });

        calcularTotal();
    });
</script>

@endsection