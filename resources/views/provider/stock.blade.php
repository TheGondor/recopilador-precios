@extends('layouts.lubeck')
@section('title')
    <title>Stock</title>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>Stock {{ $provider->name }}</h2>
    <div>
        <table id="tabla_stock" class="table w-100 table-sm table-hover">
            <thead>
                <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio Menor</th>
                <th>Precio Proveedor</th>
                <th>Ofertar</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('js/provider/stock.js') }}"></script>
@endsection
