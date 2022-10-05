@extends('layouts.lubeck')
@section('title')
    <title>Mas visitas</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>Productos con mas visitas</h2>
    <div>
        <table id="tabla_visitas" class="table w-100 table-sm table-hover">
            <thead>
                <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Total Visitas</th>
                <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('js/provider/masVisitas.js') }}"></script>
@endsection
