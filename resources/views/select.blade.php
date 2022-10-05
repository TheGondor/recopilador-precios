@extends('layouts.app')
@section('title')
    <title>Ingresar</title>
@endsection
@section('content')

<div class="container m-auto" style="opacity: 0.9">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white py-5 rounded">
            <h2 class="text-center" style="font-weight: 900">Seleccionar Proveedor</h2>
            <div>
                <table id="proveedores" class="table w-100 table-sm table-hover">
                    <thead>
                        <tr>
                        <th>Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function(){
        $('#proveedores').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            ajax:{
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "providers",
                type: 'POST',
            },
            columns:[
                {
                    data: 'name',
                    name: 'name',
                    sortable: false
                }
            ],
            drawCallback: function(settings) {
                var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                pagination.toggle(this.api().page.info().pages > 1);
            },
            language: {
                "url": "/js/es-cl.json"
            }
        });
        console.log('aqui');
    })
</script>
@endsection
