$('#tabla_buscados').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    lengthChange: false,
    ajax:{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "masBuscadosList",
        type: 'POST',
    },
    columns:[
        {
        data: 'search',
        name: 'search'

        },
        {
        data: 'sum',
        name: 'sum'

        },
        {
        data: 'accion',
        name: 'accion'

        }
    ],
    drawCallback: function(settings) {
        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
        pagination.toggle(this.api().page.info().pages > 1);
    },
    language: {
        "url": "/js/es-cl.json"
    },
    order: [[1, 'desc']]
});

function verBusqueda(id){
    modal = cargando("Cargando");
    ajaxModal("modal-ver-busqueda", id, "ver_busqueda");
}
