@extends('layouts.app')

@section('title', 'Editar repuesto')

@section('content')

<div class="container mt-4">

    <h2>Editar Repuesto</h2>

    <form
        action="{{ route(
            'repuestos.update',
            $repuesto
        ) }}"
        method="POST">

        @csrf
        @method('PUT')

        @include('repuestos._form')

        <button
            type="submit"
            class="btn btn-success">

            Actualizar

        </button>

        <a
            href="{{ route('repuestos.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection