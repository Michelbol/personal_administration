
<div class="form-group">
    <input type="hidden" id="id" name="id" value="{{ isset($product) ?  $product->id : ''}}">
    <div class="row">
        <div class="col-4">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="100" required
                   value="{{ isset($product) ?  $product->name : ''}}">
        </div>
    </div>
</div>
