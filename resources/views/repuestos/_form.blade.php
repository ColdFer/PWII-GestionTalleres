<div class="row">

    <div class="col-md-4 mb-3">

        <label for="codigo" class="form-label">
            Código
        </label>

        <input
            type="text"
            name="codigo"
            id="codigo"
            maxlength="50"
            value="{{ old(
                'codigo',
                $repuesto->codigo ?? ''
            ) }}"
            class="form-control
                @error('codigo') is-invalid @enderror"
            required>

        @error('codigo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-8 mb-3">

        <label for="nombre" class="form-label">
            Nombre
        </label>

        <input
            type="text"
            name="nombre"
            id="nombre"
            maxlength="150"
            value="{{ old(
                'nombre',
                $repuesto->nombre ?? ''
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

</div>

<div class="mb-3">

    <label for="descripcion" class="form-label">
        Descripción
    </label>

    <textarea
        name="descripcion"
        id="descripcion"
        rows="3"
        maxlength="1000"
        class="form-control
            @error('descripcion') is-invalid @enderror">{{ old(
                'descripcion',
                $repuesto->descripcion ?? ''
            ) }}</textarea>

    @error('descripcion')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>

<div class="row">

    <div class="col-md-3 mb-3">

        <label
            for="precio_compra"
            class="form-label">

            Precio compra

        </label>

        <input
            type="number"
            name="precio_compra"
            id="precio_compra"
            min="0"
            step="0.01"
            value="{{ old(
                'precio_compra',
                $repuesto->precio_compra ?? 0
            ) }}"
            class="form-control
                @error('precio_compra')
                    is-invalid
                @enderror"
            required>

        @error('precio_compra')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-3 mb-3">

        <label
            for="precio_venta"
            class="form-label">

            Precio venta

        </label>

        <input
            type="number"
            name="precio_venta"
            id="precio_venta"
            min="0"
            step="0.01"
            value="{{ old(
                'precio_venta',
                $repuesto->precio_venta ?? 0
            ) }}"
            class="form-control
                @error('precio_venta')
                    is-invalid
                @enderror"
            required>

        @error('precio_venta')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-2 mb-3">

        <label for="stock" class="form-label">
            Stock
        </label>

        <input
            type="number"
            name="stock"
            id="stock"
            min="0"
            value="{{ old(
                'stock',
                $repuesto->stock ?? 0
            ) }}"
            class="form-control
                @error('stock') is-invalid @enderror"
            required>

        @error('stock')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-2 mb-3">

        <label
            for="stock_minimo"
            class="form-label">

            Stock mínimo

        </label>

        <input
            type="number"
            name="stock_minimo"
            id="stock_minimo"
            min="0"
            value="{{ old(
                'stock_minimo',
                $repuesto->stock_minimo ?? 0
            ) }}"
            class="form-control
                @error('stock_minimo')
                    is-invalid
                @enderror"
            required>

        @error('stock_minimo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-2 mb-3">

        <label for="estado" class="form-label">
            Estado
        </label>

        <select
            name="estado"
            id="estado"
            class="form-select"
            required>

            @foreach (
                ['Activo', 'Inactivo'] as $estado
            )

                <option
                    value="{{ $estado }}"
                    @selected(
                        old(
                            'estado',
                            $repuesto->estado ?? 'Activo'
                        ) === $estado
                    )>

                    {{ $estado }}

                </option>

            @endforeach

        </select>

    </div>

</div>