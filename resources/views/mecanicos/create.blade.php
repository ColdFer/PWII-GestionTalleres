@extends('layouts.app')

@section('title', 'Nuevo mecánico')

@section('content')

<div class="container mt-4">

    <h2>Registrar Mecánico</h2>

    <form
        action="{{ route('mecanicos.store') }}"
        method="POST">

        @csrf

        @include('mecanicos._form')

        <button class="btn btn-success">
            Guardar
        </button>

        <a
            href="{{ route('mecanicos.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection