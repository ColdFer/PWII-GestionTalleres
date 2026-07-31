@extends('layouts.app')

@section('title', 'Registrar Cliente')

@section('content')

<div class="container mt-4">

    <h2>Registrar Cliente</h2>

    <hr>

    <form action="{{ route('clientes.store') }}" method="POST">

        @csrf

        <div class="mb-3">

            <label for="nombre" class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                maxlength="80"
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

        <div class="mb-3">

            <label for="apellido" class="form-label">
                Apellido
            </label>

            <input
                type="text"
                name="apellido"
                id="apellido"
                maxlength="80"
                value="{{ old('apellido') }}"
                class="form-control
                    @error('apellido') is-invalid @enderror"
                required>

            @error('apellido')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="ci" class="form-label">
                Cédula
            </label>

            <input
                type="text"
                name="ci"
                id="ci"
                maxlength="20"
                value="{{ old('ci') }}"
                class="form-control
                    @error('ci') is-invalid @enderror"
                required>

            @error('ci')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="telefono" class="form-label">
                Teléfono
            </label>

            <input
                type="text"
                name="telefono"
                id="telefono"
                maxlength="20"
                value="{{ old('telefono') }}"
                class="form-control
                    @error('telefono') is-invalid @enderror"
                required>

            @error('telefono')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="correo" class="form-label">
                Correo de acceso
            </label>

            <input
                type="email"
                name="correo"
                id="correo"
                maxlength="100"
                placeholder="Correo para iniciar sesión"
                value="{{ old('correo') }}"
                class="form-control
                    @error('correo') is-invalid @enderror"
                required>

            @error('correo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="password" class="form-label">
                Contraseña temporal
            </label>

            <input
                type="password"
                name="password"
                id="password"
                minlength="8"
                placeholder="Mínimo 8 caracteres"
                class="form-control
                    @error('password') is-invalid @enderror"
                required>


            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label
                for="password_confirmation"
                class="form-label">

                Confirmar contraseña

            </label>

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                minlength="8"
                placeholder="Repita la contraseña"
                class="form-control
                    @error('password') is-invalid @enderror"
                required>

        </div>

        <div class="mb-3">

            <label for="direccion" class="form-label">
                Dirección
            </label>

            <input
                type="text"
                name="direccion"
                id="direccion"
                maxlength="150"
                value="{{ old('direccion') }}"
                class="form-control
                    @error('direccion') is-invalid @enderror">

            @error('direccion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Guardar
        </button>

        <a
            href="{{ route('clientes.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection