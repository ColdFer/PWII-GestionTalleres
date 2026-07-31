<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">Nombre</label>

        <input
            type="text"
            name="nombre"
            value="{{ old(
                'nombre',
                $mecanico->nombre ?? ''
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

    <div class="col-md-6 mb-3">

        <label class="form-label">Apellido</label>

        <input
            type="text"
            name="apellido"
            value="{{ old(
                'apellido',
                $mecanico->apellido ?? ''
            ) }}"
            class="form-control
                @error('apellido') is-invalid @enderror"
            required>

        @error('apellido')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">CI</label>

        <input
            type="text"
            name="ci"
            value="{{ old('ci', $mecanico->ci ?? '') }}"
            class="form-control
                @error('ci') is-invalid @enderror">

        @error('ci')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">Teléfono</label>

        <input
            type="text"
            name="telefono"
            value="{{ old(
                'telefono',
                $mecanico->telefono ?? ''
            ) }}"
            class="form-control">

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">Correo</label>

        <input
            type="email"
            name="email"
            value="{{ old(
                'email',
                $mecanico->email ?? ''
            ) }}"
            class="form-control
                @error('email') is-invalid @enderror">

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">Especialidad</label>

        <select
            name="especialidad_id"
            class="form-select
                @error('especialidad_id') is-invalid @enderror"
            required>

            <option value="">
                Seleccione una especialidad
            </option>

            @foreach ($especialidades as $especialidad)

                <option
                    value="{{ $especialidad->id }}"
                    @selected(
                        old(
                            'especialidad_id',
                            $mecanico->especialidad_id ?? ''
                        ) == $especialidad->id
                    )>

                    {{ $especialidad->nombre }}

                </option>

            @endforeach

        </select>

        @error('especialidad_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">Estado</label>

        <select
            name="estado"
            class="form-select"
            required>

            @foreach (['Activo', 'Inactivo'] as $estado)

                <option
                    value="{{ $estado }}"
                    @selected(
                        old(
                            'estado',
                            $mecanico->estado ?? 'Activo'
                        ) === $estado
                    )>

                    {{ $estado }}

                </option>

            @endforeach

        </select>

    </div>

</div>