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
        <div class="d-flex h-100">
            <div class="row m-auto w-100 rounded bg-light">
                <div class="col-12 p-5">
                    <h2 class="text-center" style="font-weight: 900">Buscador</h2>
                    <form action="resultados" method="GET">
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
            </div>
        </div>
    </div>
</body>
</html>
