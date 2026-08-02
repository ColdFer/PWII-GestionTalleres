@extends('layouts.app')

@section('title', 'Editar servicio')

@section('content')

<div class="container mt-4">

    <h2>Editar Servicio</h2>

    <hr>

    <form
        action="{{ route('servicios.update', $servicio) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label for="tipo_servicio_id" class="form-label">
                Tipo de servicio
            </label>

            <select
                name="tipo_servicio_id"
                id="tipo_servicio_id"
                class="form-select
                    @error('tipo_servicio_id') is-invalid @enderror">

                <option value="">
                    Seleccione un tipo
                </option>

                @foreach ($tiposServicios as $tipoServicio)

                    <option
                        value="{{ $tipoServicio->id }}"
                        @selected(
                            old(
                                'tipo_servicio_id',
                                $servicio->tipo_servicio_id
                            ) == $tipoServicio->id
                        )>

                        {{ $tipoServicio->nombre }}

                    </option>

                @endforeach

            </select>

            @error('tipo_servicio_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="nombre" class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                maxlength="100"
                value="{{ old('nombre', $servicio->nombre) }}"
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
                maxlength="200"
                class="form-control
                    @error('descripcion') is-invalid @enderror">{{ old(
                        'descripcion',
                        $servicio->descripcion
                    ) }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="precio" class="form-label">
                Precio en bolivianos
            </label>

            <input
                type="number"
                name="precio"
                id="precio"
                min="0"
                step="0.01"
                value="{{ old('precio', $servicio->precio) }}"
                class="form-control
                    @error('precio') is-invalid @enderror">

            @error('precio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a href="{{ route('servicios.index') }}"
           class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection