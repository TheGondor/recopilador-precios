$('#tabla_visitas').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    lengthChange: false,
    ajax:{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "masVisitasList",
        type: 'POST',
    },
    columns:[
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'name',
            name: 'name'

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
    order: [[2, 'desc']]
});

function verVisita(id){
    modal = cargando("Cargando");
    ajaxModal("modal-ver-visita", id, "ver_visita");
}
