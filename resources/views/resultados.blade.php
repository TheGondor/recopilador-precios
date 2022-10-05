<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buscador Mercado Publico</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style>
        body{
            font-family: "Open Sans", sans-serif;
            font-weight: 300;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container vh-100">
        <div class="d-flex h-100 py-3">
            <div class="row m-auto w-100">
                <div class="col-12 p-5 rounded bg-light">
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
                <div class="col-12 p-md-5 rounded bg-light mt-3">
                    <div class="d-flex justify-content-between">
                        <h2 class="text-center mx-4" style="font-weight: 900">Resultado de la busqueda:</h2>
                        <form action="resultados" method="GET" id="select_order">
                            <input type="hidden" name="search" class="control-input p-2" placeholder="Ingrese una ID o nombre" value="{{ $search }}">
                            <label for="exampleFormControlInput1" class="form-label">Ordenar Por</label>
                            <select class="form-select" id="order" name="order" aria-label="Default select example">
                                <option value="price_desc" {{ $order == 'price_desc' ? 'selected' : ''}}>Precio Desc</option>
                                <option value="price_asc" {{ $order == 'price_asc' ? 'selected' : ''}}>Precio Asc</option>
                                <option value="name_asc" {{ $order == 'name_asc' ? 'selected' : ''}}>Nombre Asc</option>
                                <option value="name_desc" {{ $order == 'name_desc' ? 'selected' : ''}}>Nombre Desc</option>
                              </select>
                              <label for="exampleFormControlInput1" class="form-label">Convenio</label>
                            <select class="form-select" id="convenio" name="convenio" aria-label="Default select example">
                                <option value="todos" {{ $convenio == 'todos' ? 'selected' : ''}}>Todos</option>
                                @foreach ($convenios as $item)
                                <option value="{{ $item->convenio }}" {{ $convenio == $item->convenio ? 'selected' : ''}}>{{ $item->convenio }}</option>
                                @endforeach
                              </select>
                            @if ($page != 0)
                                <input type="hidden" id="page" name="page" class="control-input p-2" placeholder="Ingrese una ID o nombre" value="{{ $page }}">
                            @endif
                        </form>
                   </div>

                    @foreach ($resultados as $resultado)
                    <div class="w-100 p-3 rounded mt-2 bg-white">
                        <div class="d-flex justify-content-between">
                            <p style="font-weight: 900">{{ $resultado->product_id }}</p>
                            <p style="font-weight: 900">{{ $resultado->convenio }}</p>
                       </div>
                       <div class="row">
                           <div class="col-md-2">
                                <img src="{{ $resultado->url_image }}" alt="" class="w-100">
                           </div>
                           <div class="col-md-8">
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
                                @if ($resultado->convenio == 'Ferretería' && $resultado->cataluna_price != 0 && $resultado->cataluna_status == 1)
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
                           </div>
                       </div>

                        <div class="d-flex justify-content-center mt-2">
                            <a href="{{ asset("/convenio/$resultado->id") }}" target="_blank" class="btn btn-outline-primary px-md-5">Ver en Convenio Marco</a>
                        </div>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-2">
                        {!! $resultados->appends(['search' => $search, 'order' => $order, 'convenio' => $convenio])->links('pagination::bootstrap-4') !!}
                    </div>
                    <a href="/" target="_self" class="btn btn-outline-primary px-3">Volver atras</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
$('#order').on('change', function () {
    $('#select_order').submit();
})

$('#convenio').on('change', function () {
    $('#page').val(1);
    $('#select_order').submit();
})
</script>
