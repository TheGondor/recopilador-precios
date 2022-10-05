@extends('layouts.lubeck')
@section('title')
    <title>Buscador</title>
@endsection
@section('content')

<div class="container m-auto bg-light p-5 rounded" style="opacity: 0.9">
    <h2 class="text-center" style="font-weight: 900">Buscador Convenio Ferretería</h2>
    <form action="/provider/{{$provider->id}}/resultados" method="GET">
        <input type="text" name="search" class="control-input w-100 p-2" placeholder="Ingrese una ID o nombre">
        @if ($errors->has('search'))
            <span class="help-block">
                <strong class="text-danger">{{ $errors->first('search') }}</strong>
            </span>
        @endif
        <div class="d-flex w-100 justify-content-center ">
            <button type="submit" required class="btn btn-outline-primary mt-2 px-5 align text-center">Buscar</button>
        </div>
    </form>
</div>
<script src="{{ asset('js/provider/sinStock.js') }}"></script>
@endsection
