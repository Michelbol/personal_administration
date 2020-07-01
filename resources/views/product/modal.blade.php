<div class="modal" tabindex="-1" role="dialog" id="product_supplier_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Fornecedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="container-fluid">
                    <form action="{{ routeTenant('product_supplier.store') }}" method="post" id="form_product_supplier">
                        {{csrf_field()}}
                        {{ method_field('POST') }}
                        <div class="row">
                            <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" id="id" name="id" value="">
                            <div class="col-4 form-group">
                                <label for="code">Código</label>
                                <input type="text" class="form-control" id="code" name="code" maxlength="15-">
                            </div>
                            <div class="col-4 form-group">
                                <label for="un">Unidade</label>
                                <input type="text" class="form-control" id="un" name="un">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="supplier_id">Fornecedor</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">Informe um Fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->fantasy_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-4 form-group">
                                <label for="brand_id">Marca</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">Informe uma Marca</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="$('#form_product_supplier').submit()">Save changes</button>
            </div>
        </div>
    </div>
</div>
