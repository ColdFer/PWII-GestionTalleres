@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="container mt-4">

    <h2>Editar Cliente</h2>

    <hr>

    <form
        action="{{ route('clientes.update', $cliente) }}"
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
                maxlength="80"
                value="{{ old('nombre', $cliente->nombre) }}"
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
                value="{{ old('apellido', $cliente->apellido) }}"
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
                value="{{ old('ci', $cliente->ci) }}"
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
                value="{{ old('telefono', $cliente->telefono) }}"
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
                value="{{ old('correo', $cliente->correo) }}"
                class="form-control
                    @error('correo') is-invalid @enderror"
                required>

            @error('correo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="alert
            {{ $cliente->usuario
                ? 'alert-success'
                : 'alert-warning' }}">

            @if ($cliente->usuario)

                Este cliente tiene una cuenta activa.

                <br>

                <strong>Rol:</strong>

                {{ $cliente->usuario->rol?->nombre ?? 'Sin rol' }}

            @else

                Este cliente todavía no tiene una cuenta.
                Debe establecer una contraseña para crearla.

            @endif

        </div>

        <div class="mb-3">

            <label for="password" class="form-label">

                @if ($cliente->usuario)
                    Nueva contraseña
                @else
                    Contraseña de acceso
                @endif

            </label>

            <input
                type="password"
                name="password"
                id="password"
                minlength="8"
                placeholder="{{ $cliente->usuario
                    ? 'Vacío para conservar la contraseña'
                    : 'Mínimo 8 caracteres' }}"
                class="form-control
                    @error('password') is-invalid @enderror"
                @required(!$cliente->usuario)>

            @if ($cliente->usuario)
            @else

                <div class="form-text">
                    Debe tener al menos 8 caracteres.
                </div>

            @endif

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
                placeholder="Repita la nueva contraseña"
                class="form-control
                    @error('password') is-invalid @enderror"
                @required(!$cliente->usuario)>

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
                value="{{ old('direccion', $cliente->direccion) }}"
                class="form-control
                    @error('direccion') is-invalid @enderror">

            @error('direccion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button
            type="submit"
            class="btn btn-success">

            Actualizar

        </button>

        <a
            href="{{ route('clientes.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection