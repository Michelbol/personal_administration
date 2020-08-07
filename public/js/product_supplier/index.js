$(document).ready(function(){
    $('#supplier_id').select2();
    $('#brand_id').select2();
});
let datatable = $('#table_product_supplier').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
        url: url_data_table,
        data: function () {

        }
    },
    columns: [
        { data: 'fantasy_name', name: 'fantasy_name' },
        { data: 'brand_name', name: 'brand_name' },
        { data: 'code', name: 'code' },
        { data: 'un', name: 'un' },
        { data: 'actions', name: 'actions' }
    ],
});

$('html').on('click', '.modal-edit', function(){
    let request = $.ajax({
        url: URLBASE()+"/product_supplier/"+$(this).attr('data-id'),
        method: "GET"
    });
    request.done(function (data) {
        fillModal(data.productSupplier);
        $('#product_supplier_modal').modal('show');
    });
});
$('#product_supplier_modal').on('hide.bs.modal', function(){
    cleanModal();
});

function fillModal(productSupplier){
    $('#id').val(productSupplier.id);
    $('#supplier_id').val(productSupplier.supplier_id);
    $('#brand_id').val(productSupplier.brand_id);
    $('#product_id').val(productSupplier.product_id);
    $('#code').val(productSupplier.code);
    $('#un').val(productSupplier.un);
    $('#form_product_supplier').prop('action', URLBASE()+'/product_supplier/'+productSupplier.id);
    $('#form_product_supplier [name="_method"]').val('PUT');
}


function cleanModal(){
    $('#id').val('');
    $('#supplier_id').val('');
    $('#brand_id').val('');
    $('#code').val('');
    $('#product_id').val('');
    $('#un').val('');
    $('#form_product_supplier').prop('action', URLBASE()+'/product_supplier');
    $('#form_product_supplier [name="_method"]').val('POST');
}

