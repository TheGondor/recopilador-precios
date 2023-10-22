@extends('layouts.app')
@section('title')
    <title>Ingresar</title>
@endsection
@section('content')

<div class="container m-auto" style="opacity: 0.9">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white py-5 rounded">
            <h2 class="text-center" style="font-weight: 900">Convenio Ferreteria</h2>
            <div>
                <table id="ferreteria" class="table w-100 table-sm table-hover">
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
        <div class="col-md-8 bg-white mt-3 py-5 rounded">
            <a href='/excel/Aseo' target="_blank" class='btn btn-sm btn-outline-success m-1 px-3' title='Descargar'><i class="fas fa-file-excel"></i> Descargar Excel Aseo</a>
            <h2 class="text-center" style="font-weight: 900">Convenio Aseo</h2>
            <div>
                <table id="aseo" class="table w-100 table-sm table-hover">
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
        <div class="col-md-8 bg-white mt-3 py-5 rounded">
            <a href='/excel/Oficina' target="_blank" class='btn btn-sm btn-outline-success m-1 px-3' title='Descargar'><i class="fas fa-file-excel"></i> Descargar Excel Oficina</a>
            <h2 class="text-center" style="font-weight: 900">Convenio Oficina</h2>
            <div>
                <table id="oficina" class="table w-100 table-sm table-hover">
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
        $('#ferreteria').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            ajax:{
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "providers/ferreteria",
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
        $('#aseo').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            ajax:{
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "providers/aseo",
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
        $('#oficina').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            ajax:{
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "providers/oficina",
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
    })
</script>
@endsection
