@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('content')

<div class="container mt-4">

    <h2>Editar Vehículo</h2>

    <hr>

    <form
        action="{{ route('vehiculos.update', $vehiculo->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label for="cliente_id" class="form-label">
                Propietario
            </label>

            <select
                name="cliente_id"
                id="cliente_id"
                class="form-select @error('cliente_id') is-invalid @enderror">

                <option value="">
                    Seleccione un cliente
                </option>

                @foreach ($clientes as $cliente)

                    <option
                        value="{{ $cliente->id }}"
                        @selected(
                            old('cliente_id', $vehiculo->cliente_id)
                            == $cliente->id
                        )>

                        {{ $cliente->nombre }}
                        {{ $cliente->apellido }}
                        - CI: {{ $cliente->ci }}

                    </option>

                @endforeach

            </select>

            @error('cliente_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        <div class="mb-3">

            <label for="modelo_id" class="form-label">
                Marca y modelo
            </label>

            <select
                name="modelo_id"
                id="modelo_id"
                class="form-select
                    @error('modelo_id') is-invalid @enderror"
                required>

                <option value="">
                    Seleccione marca y modelo
                </option>

                @foreach ($modelos as $modelo)

                    <option
                        value="{{ $modelo->id }}"
                        @selected(
                            old(
                                'modelo_id',
                                $vehiculo->modelo_id
                            ) == $modelo->id
                        )>

                        {{ $modelo->marca->nombre }}
                        —
                        {{ $modelo->nombre }}

                    </option>

                @endforeach

            </select>

            @error('modelo_id')
                <div class="invalid-feedback">
                    
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="placa" class="form-label">
                Placa
            </label>

            <input
                type="text"
                name="placa"
                id="placa"
                class="form-control @error('placa') is-invalid @enderror"
                value="{{ old('placa', $vehiculo->placa) }}"
                maxlength="20">

            @error('placa')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="anio" class="form-label">
                Año
            </label>

            <input
                type="number"
                name="anio"
                id="anio"
                class="form-control @error('anio') is-invalid @enderror"
                value="{{ old('anio', $vehiculo->anio) }}"
                min="1901"
                max="{{ date('Y') }}">

            @error('anio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="color" class="form-label">
                Color
            </label>

            <input
                type="text"
                name="color"
                id="color"
                class="form-control @error('color') is-invalid @enderror"
                value="{{ old('color', $vehiculo->color) }}"
                maxlength="30">

            @error('color')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="kilometraje" class="form-label">
                Kilometraje
            </label>

            <input
                type="number"
                name="kilometraje"
                id="kilometraje"
                class="form-control @error('kilometraje') is-invalid @enderror"
                value="{{ old('kilometraje', $vehiculo->kilometraje) }}"
                min="0">

            @error('kilometraje')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a href="{{ route('vehiculos.index') }}"
           class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection