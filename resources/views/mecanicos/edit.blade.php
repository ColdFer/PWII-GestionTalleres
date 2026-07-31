@extends('layouts.app')

@section('title', 'Editar mecánico')

@section('content')

<div class="container mt-4">

    <h2>Editar Mecánico</h2>

    <form
        action="{{ route('mecanicos.update', $mecanico) }}"
        method="POST">

        @csrf
        @method('PUT')

        @include('mecanicos._form')

        <button class="btn btn-success">
            Actualizar
        </button>

        <a
            href="{{ route('mecanicos.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection