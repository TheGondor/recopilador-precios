@extends('layouts.lubeck')
@section('title')
    <title>{{ $search }}</title>
@endsection
@section('content')

<div class="container m-auto bg-light p-5 rounded" style="opacity: 0.9">
    <div class="col-12 p-5 rounded bg-white">
        <h2 class="text-center" style="font-weight: 900">Buscador</h2>
        <form action="resultados" method="GET">
            <input type="text" name="search" class="control-input w-100 p-2" placeholder="Ingrese una ID o nombre" value="{{ $search }}">
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
    <div class="col-12 p-md-5 rounded bg-white mt-3">
        <h2 class="text-center mx-4" style="font-weight: 900">Resultado de la busqueda:</h2>
        @foreach ($resultados as $resultado)
        <div class="w-100 p-3 rounded mt-2 bg-light">
            <div class="d-flex justify-content-between">
                <p style="font-weight: 900">{{ $resultado->product_id }}</p>
                <p style="font-weight: 900">{{ $resultado->convenio }}</p>
           </div>

            <p>Nombre: {{ $resultado->name }}</p>
            @if ($resultado->special != 0)
                @if ($resultado->special <= $resultado->price)
                    <p>Precio: ${{ number_format( $resultado->price , 0 , ',' , '.' ); }}</p>
                    <p class="text-danger">Precio oferta: ${{ number_format( $resultado->special , 0 , ',' , '.' ); }}</p>
                @else
                    <p>Precio: ${{ number_format( $resultado->price , 0 , ',' , '.' ) }}</p>
                @endif

            @else
                <p>Precio: ${{ number_format( $resultado->price , 0 , ',' , '.' ) }}</p>
            @endif
            @if ($resultado->convenio == 'Ferretería' && $resultado->lubeck_price != 0 && $resultado->lubeck_status == 1)
                @if ($resultado->lubeck_special != 0)
                    @if ($resultado->lubeck_special <= $resultado->lubeck_price)
                        <p class="text-danger" style="font-weight: 900">Precio oferta Lubeck: ${{ number_format( $resultado->lubeck_special, 0 , ',' , '.' ); }}</p>
                    @else
                        <p style="font-weight: 900">Precio Lubeck: ${{ number_format( $resultado->lubeck_price , 0 , ',' , '.' ); }}</p>
                    @endif

                @else
                    <p style="font-weight: 900">Precio Lubeck: ${{ number_format( $resultado->lubeck_price , 0 , ',' , '.' );}}</p>
                @endif
            @endif
            @if ($resultado->convenio == 'Ferretería' && $resultado->lubeck_price != 0 && $resultado->lubeck_status == 1)
                @if ($resultado->cataluna_special != 0)
                    @if ($resultado->cataluna_special <= $resultado->cataluna_price)
                        <p class="text-danger" style="font-weight: 900">Precio oferta Cataluña Inversiones: ${{ number_format( $resultado->cataluna_special, 0 , ',' , '.' ); }}</p>
                    @else
                        <p style="font-weight: 900">Precio Cataluña Inversiones: ${{ number_format( $resultado->cataluna_price , 0 , ',' , '.' ); }}</p>
                    @endif

                @else
                    <p style="font-weight: 900">Precio Cataluña Inversiones: ${{ number_format( $resultado->cataluna_price , 0 , ',' , '.' );}}</p>
                @endif
            @endif
            <div class="d-flex justify-content-center mt-2">
                <a href="{{ asset("/convenio/$resultado->id") }}" target="_blank" class="btn btn-outline-primary px-md-5">Ver en Convenio Marco</a>
            </div>
        </div>
        @endforeach
        <div class="d-flex justify-content-center mt-2">
            {!! $resultados->appends(['search' => $search])->links('pagination::bootstrap-4') !!}
        </div>
        <a href="/" target="_self" class="btn btn-outline-primary px-3">Volver atras</a>
    </div>
</div>
<script src="{{ asset('js/lubeck/sinStock.js') }}"></script>
@endsection
