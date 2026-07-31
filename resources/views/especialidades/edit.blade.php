@extends('layouts.app')

@section('title', 'Editar especialidad')

@section('content')

<div class="container mt-4">

    <h2>Editar Especialidad</h2>

    <form
        action="{{ route(
            'especialidades.update',
            $especialidad
        ) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label class="form-label">Nombre</label>

            <input
                type="text"
                name="nombre"
                value="{{ old(
                    'nombre',
                    $especialidad->nombre
                ) }}"
                class="form-control
                    @error('nombre') is-invalid @enderror"
                required>

            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label class="form-label">Descripción</label>

            <input
                type="text"
                name="descripcion"
                value="{{ old(
                    'descripcion',
                    $especialidad->descripcion
                ) }}"
                class="form-control">

        </div>

        <button class="btn btn-success">
            Actualizar
        </button>

        <a
            href="{{ route('especialidades.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection