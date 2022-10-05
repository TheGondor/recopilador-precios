$('#tabla_stock').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    lengthChange: false,
    ajax:{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "ofertaList",
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
        data: 'price',
        name: 'price'

        },
        {
        data: 'provider_price',
        name: 'provider_price'
        },
        {
        data: 'date',
        name: 'date',
        orderData: 5
        },
        {
        data: 'date_order',
        name: 'date_order',
        visible: false,
        searchable: false,
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
    }
});

function setFecha(id){
    modal = cargando("Cargando");
    ajaxModal("modal-form-fecha", id, "set_fecha");
}

$(document).on("submit","#form-fecha",function(e) {
    modal = cargando("Configurando Fecha");
    e.preventDefault();
    ajaxRequest("oferta-fecha", 0, "POST", "form-fecha", "tabla_stock","set_fecha");
});
