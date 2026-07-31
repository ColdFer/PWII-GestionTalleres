@extends('layouts.app')

@section('title', 'Marcas y modelos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Marcas y Modelos</h2>

            <p class="text-muted mb-0">
                Catálogo utilizado para registrar vehículos.
            </p>
        </div>

        <a
            href="{{ route('vehiculos.index') }}"
            class="btn btn-secondary">

            Volver a Vehículos

        </a>

    </div>

    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    Registrar marca
                </div>

                <div class="card-body">

                    <form
                        action="{{ route('marcas.store') }}"
                        method="POST">

                        @csrf

                        <div class="mb-3">

                            <label
                                for="nombre_marca"
                                class="form-label">

                                Nombre de la marca

                            </label>

                            <input
                                type="text"
                                name="nombre_marca"
                                id="nombre_marca"
                                maxlength="80"
                                placeholder="Ejemplo: Toyota"
                                value="{{ old('nombre_marca') }}"
                                class="form-control
                                    @error('nombre_marca')
                                        is-invalid
                                    @enderror"
                                required>

                            @error('nombre_marca')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success">

                            Guardar marca

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header">
                    Registrar modelo
                </div>

                <div class="card-body">

                    @if ($marcas->isEmpty())

                        <div class="alert alert-warning mb-0">
                            Primero debe registrar una marca.
                        </div>

                    @else

                        <form
                            action="{{ route(
                                'modelos-vehiculos.store'
                            ) }}"
                            method="POST">

                            @csrf

                            <div class="mb-3">

                                <label
                                    for="marca_id"
                                    class="form-label">

                                    Marca

                                </label>

                                <select
                                    name="marca_id"
                                    id="marca_id"
                                    class="form-select
                                        @error('marca_id')
                                            is-invalid
                                        @enderror"
                                    required>

                                    <option value="">
                                        Seleccione una marca
                                    </option>

                                    @foreach ($marcas as $marca)

                                        <option
                                            value="{{ $marca->id }}"
                                            @selected(
                                                old('marca_id')
                                                == $marca->id
                                            )>

                                            {{ $marca->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('marca_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label
                                    for="nombre_modelo"
                                    class="form-label">

                                    Modelo

                                </label>

                                <input
                                    type="text"
                                    name="nombre_modelo"
                                    id="nombre_modelo"
                                    maxlength="80"
                                    placeholder="Ejemplo: Corolla"
                                    value="{{ old('nombre_modelo') }}"
                                    class="form-control
                                        @error('nombre_modelo')
                                            is-invalid
                                        @enderror"
                                    required>

                                @error('nombre_modelo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success">

                                Guardar modelo

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            Catálogo registrado
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Marca</th>
                            <th>Modelos</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($marcas as $marca)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $marca->nombre }}
                                    </strong>
                                </td>

                                <td>

                                    @forelse ($marca->modelos as $modelo)

                                        <span
                                            class="badge text-bg-secondary me-1">

                                            {{ $modelo->nombre }}

                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            Sin modelos registrados
                                        </span>

                                    @endforelse

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="2"
                                    class="text-center">

                                    No existen marcas registradas.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection