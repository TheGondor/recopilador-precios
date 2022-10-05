$('#tabla_stock').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    lengthChange: false,
    ajax:{
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "stockList",
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
        }
        ,
        {
        data: 'ofertar',
        name: 'ofertar'
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


function modalPrice(id){
    modal = cargando("Cargando");
    ajaxModal("modal-view-prices", id, "modalPrice");
}
