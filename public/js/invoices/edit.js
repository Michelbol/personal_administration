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
    let productSupplierId = $SELECT.val();
    let invoiceProduct = $SELECT.data('id');
    if(!isValid(productSupplierId)){
        return;
    }
    hideProductAndStartLoading($SELECT);
    let request = $.ajax({
        url: URLBASE()+`/invoice_product/${invoiceProduct}`,
        method: "PUT",
        data: {
            'product_supplier_id': productSupplierId
        }
    });
    request.done(function () {
        showProductAndStartLoading($SELECT);
    });
}

function isValid(value){
    return value !== '' && value !== null && typeof value !== "undefined";
}

$('.product').on('change', updateProduct)
