$('.select_an_product').on('click', clickSelectAndProduct);

function clickSelectAndProduct(element){
    let id = $(element.target).data('id');
    $(`.product[data-id=${id}]`).removeClass('d-none');
}

function hideProductAndStartLoading($SELECT){
    $SELECT.closest('.select-product').addClass('d-none');
    startLoading($SELECT.closest('td'));
}
function showProductAndStartLoading($SELECT){
    $SELECT.closest('.select-product').removeClass('d-none');
    endLoading($SELECT.closest('td'));
}

function updateProduct(){
    let $SELECT = $(this);
    let productId = $SELECT.val();
    let invoiceProduct = $SELECT.data('id');
    hideProductAndStartLoading($SELECT);
    let request = $.ajax({
        url: URLBASE()+`/invoice_product/${invoiceProduct}`,
        method: "PUT",
        data: {
            'product_id': productId
        }
    });
    request.done(function () {
        showProductAndStartLoading($SELECT);
    });
}

$('.product').on('change', updateProduct)
