@extends('layouts.lubeck')
@section('title')
    <title>Mas buscados</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>Terminos mas buscados</h2>
    <div>
        <table id="tabla_buscados" class="table w-100 table-sm table-hover">
            <thead>
                <tr>
                <th>Termino</th>
                <th>Total busquedas</th>
                <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('js/provider/masBuscados.js') }}"></script>
@endsection
