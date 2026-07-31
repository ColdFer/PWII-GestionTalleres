@extends('layouts.app')

@section('title', 'Editar tipo de servicio')

@section('content')

<div class="container mt-4">

    <h2>Editar Tipo de Servicio</h2>

    <hr>

    <form
        action="{{ route(
            'tipos-servicios.update',
            $tipoServicio
        ) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label for="nombre" class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old(
                    'nombre',
                    $tipoServicio->nombre
                ) }}"
                maxlength="80"
                class="form-control
                    @error('nombre') is-invalid @enderror">

            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <textarea
                name="descripcion"
                id="descripcion"
                rows="3"
                maxlength="150"
                class="form-control
                    @error('descripcion') is-invalid @enderror">{{ old(
                        'descripcion',
                        $tipoServicio->descripcion
                    ) }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a href="{{ route('tipos-servicios.index') }}"
           class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection