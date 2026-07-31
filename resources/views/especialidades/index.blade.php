@extends('layouts.app')

@section('title', 'Especialidades')

@section('content')

<div class="container mt-4">

    <h2>Especialidades</h2>

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

    <div class="card mb-4">

        <div class="card-header">
            Nueva especialidad
        </div>

        <div class="card-body">

            <form
                action="{{ route('especialidades.store') }}"
                method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <input
                            type="text"
                            name="nombre"
                            placeholder="Nombre"
                            value="{{ old('nombre') }}"
                            class="form-control
                                @error('nombre') is-invalid @enderror"
                            required>

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <input
                            type="text"
                            name="descripcion"
                            placeholder="Descripción"
                            value="{{ old('descripcion') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-success w-100">
                            Guardar
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <table class="table table-bordered align-middle">

        <thead class="table-dark">

            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Mecánicos</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            @forelse ($especialidades as $especialidad)

                <tr>

                    <td>{{ $especialidad->nombre }}</td>

                    <td>
                        {{ $especialidad->descripcion
                            ?? 'Sin descripción' }}
                    </td>

                    <td>{{ $especialidad->mecanicos_count }}</td>

                    <td>

                        <a
                            href="{{ route(
                                'especialidades.edit',
                                $especialidad
                            ) }}"
                            class="btn btn-warning btn-sm">

                            Editar

                        </a>

                        <form
                            action="{{ route(
                                'especialidades.destroy',
                                $especialidad
                            ) }}"
                            method="POST"
                            class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm(
                                    '¿Eliminar especialidad?'
                                )">

                                Eliminar

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No existen especialidades.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection