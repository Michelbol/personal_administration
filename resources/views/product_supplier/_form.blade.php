<div class="row">
    <input type="hidden" id="product_id" name="product_id" value="{{ Route::current()->parameter('id') }}">
    <input type="hidden" id="id" name="id" value="">
    <div class="col-2 form-group">
        <label for="code">Código</label>
        <input type="text" class="form-control" id="code" name="code" maxlength="15-">
    </div>
    <div class="col-2 form-group">
        <label for="un">Unidade</label>
        <input type="text" class="form-control" id="un" name="un">
    </div>
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
        <br>
        <select name="brand_id" id="brand_id" class="form-control">
            <option value="">Informe uma Marca</option>
            @foreach($brands as $brand)
                <option value="{{$brand->id}}">{{$brand->name}}</option>
            @endforeach
        </select>
    </div>
</div>
