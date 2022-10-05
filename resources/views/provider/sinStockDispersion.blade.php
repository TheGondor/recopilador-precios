@extends('layouts.lubeck')
@section('title')
    <title>Sin Stock fuera dispersión</title>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>No stock dispersión {{ $provider->name }}</h2>
    <div>
        <table id="tabla_stock" class="table w-100 table-sm table-hover">
            <thead>
                <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio Menor (Dispersión)</th>
                <th>Precio Proveedor</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('js/provider/sinStockDispersion.js') }}"></script>
@endsection
