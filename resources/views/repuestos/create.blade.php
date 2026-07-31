@extends('layouts.app')

@section('title', 'Nuevo repuesto')

@section('content')

<div class="container mt-4">

    <h2>Registrar Repuesto</h2>

    <form
        action="{{ route('repuestos.store') }}"
        method="POST">

        @csrf

        @include('repuestos._form')

        <button
            type="submit"
            class="btn btn-success">

            Guardar

        </button>

        <a
            href="{{ route('repuestos.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection