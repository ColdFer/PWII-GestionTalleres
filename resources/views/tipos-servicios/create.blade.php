@extends('layouts.app')

@section('title', 'Registrar tipo de servicio')

@section('content')

<div class="container mt-4">

    <h2>Registrar Tipo de Servicio</h2>

    <hr>

    <form
        action="{{ route('tipos-servicios.store') }}"
        method="POST">

        @csrf

        <div class="mb-3">

            <label for="nombre" class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old('nombre') }}"
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
                    @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Guardar
        </button>

        <a href="{{ route('tipos-servicios.index') }}"
           class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection