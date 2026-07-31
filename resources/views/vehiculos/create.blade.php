@extends('layouts.app')

@section('title', 'Registrar Vehículo')

@section('content')

<div class="container mt-4">

    <h2>Registrar Vehículo</h2>

    <hr>

    @if ($clientes->isEmpty())

        <div class="alert alert-warning">

            No existen clientes registrados. Debes registrar un cliente antes de crear un vehículo.

        </div>

        <a href="{{ route('clientes.create') }}"
           class="btn btn-primary">

            Registrar Cliente

        </a>

        <a href="{{ route('vehiculos.index') }}"
           class="btn btn-secondary">

            Volver

        </a>

    @else

        <form action="{{ route('vehiculos.store') }}"
              method="POST">

            @csrf

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
                            @selected(old('cliente_id') == $cliente->id)>

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
                                old('modelo_id') == $modelo->id
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
                    value="{{ old('placa') }}"
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
                    value="{{ old('anio') }}"
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
                    value="{{ old('color') }}"
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
                    value="{{ old('kilometraje', 0) }}"
                    min="0">

                @error('kilometraje')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <button type="submit"
                    class="btn btn-success">

                Guardar

            </button>

            <a href="{{ route('vehiculos.index') }}"
               class="btn btn-secondary">

                Cancelar

            </a>

        </form>

    @endif

</div>

@endsection