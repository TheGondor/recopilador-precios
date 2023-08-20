@extends('layouts.lubeck')
@section('title')
    <title>Ofertas</title>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>Ofertas {{ $provider->name }}</h2>
    <div>
        <table id="tabla_stock" class="table w-100 table-sm table-hover">
            <thead>
                <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio Menor</th>
                <th>Precio Proveedor</th>
                <th>Fin Oferta</th>
                <th>Orden</th>
                <th>Agregar Fecha</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <a href='/provider/{{ $provider->id}}/{{ $convenio }}/offers' target="_blank" class='btn btn-sm btn-outline-success m-1 px-3' title='Descargar'><i class="fas fa-file-excel"></i> {{ $provider->name}}</a>
        <a href='/provider/{{ $provider->id}}/{{ $convenio }}/offersProviders' target="_blank" class='btn btn-sm btn-outline-success m-1 px-3' title='Descargar'><i class="fas fa-file-excel"></i> Todos Proveedores</a>
    </div>
</div>
<script src="{{ asset('js/provider/oferta.js') }}"></script>
@endsection
