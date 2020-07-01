
<div class="form-group">
    <h4>Informações Gerais</h4>
    <div class="row">
        <div class="col-2">
            <label for="number">Número</label>
            <input readonly type="text" name="number" id="number" class="form-control"
                   value="{{ isset($invoice) ?  $invoice->number : ''}}">
        </div>
        <div class="col-1">
            <label for="series">Série</label>
            <input readonly type="text" name="series" id="series" class="form-control"
                   value="{{ isset($invoice) ?  $invoice->series : ''}}">
        </div>
        <div class="col-3">
            <label for="emission_at">Data da Emissão</label>
            <input readonly type="text" name="emission_at" id="emission_at" class="form-control"
                   value="{{ isset($invoice) ?  $invoice->emission_at->format('d/m/Y H:i:s') : ''}}">
        </div>
        <div class="col-2">
            <label for="document">Cpf</label>
            <input readonly type="text" name="document" id="document" class="form-control cpf"
                   value="{{ isset($invoice) ?  $invoice->document : ''}}">
        </div>
        <div class="col-4 form-group">
            <label for="supplier_id">Fornecedor</label>
            <select readonly name="supplier_id" id="supplier_id" class="form-control">
                <option value="">Informe um Fornecedor</option>
                @foreach($suppliers as $supplier)
                    <option
                        value="{{$supplier->id}}"
                        {{
                            isset($invoice)
                            ? ($invoice->supplier_id === $supplier->id ? 'selected' : '')
                            : ''
                        }}
                    >{{$supplier->fantasy_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label for="qr_code">Qr Code</label>
            <input readonly type="text" name="qr_code" id="qr_code" class="form-control"
                   value="{{ isset($invoice) ?  $invoice->qr_code : ''}}">
        </div>
    </div>
    <hr>
    <h4>Produtos</h4>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Produto No Sistema</th>
            <th>Nome</th>
            <th>Un</th>
            <th>Código</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Valor Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoiceProducts as $invoiceProduct)
            <tr>
                <td>{{ $invoiceProduct->product_name }}</td>
                <td>{{ $invoiceProduct->name }}</td>
                <td>{{ $invoiceProduct->un }}</td>
                <td>{{ $invoiceProduct->code }}</td>
                <td>{{ $invoiceProduct->quantity }}</td>
                <td>{{ $invoiceProduct->unitary_value }}</td>
                <td>{{ $invoiceProduct->total_value }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="col-2 offset-10">
        <label for="total_products">Total dos Produtos</label>
        <input readonly type="text" name="total_products" id="total_products" class="form-control money"
               value="{{ isset($invoice) ?  $invoice->total_products : ''}}">
    </div>
    <hr>
    <h4>Totais e Impostos</h4>
    <div class="row">
        <div class="col-2">
            <label for="taxes">Lei da Transparência</label>
            <input readonly type="text" name="taxes" id="taxes" class="form-control money"
                   value="{{ isset($invoice) ?  $invoice->taxes : ''}}">
        </div>
        <div class="col-2">
            <label for="discount">Desconto</label>
            <input readonly type="text" name="discount" id="discount" class="form-control money"
                   value="{{ isset($invoice) ?  $invoice->discount : ''}}">
        </div>
        <div class="col-2">
            <label for="total_paid">Total Pago</label>
            <input readonly type="text" name="total_paid" id="total_paid" class="form-control money"
                   value="{{ isset($invoice) ?  $invoice->total_paid : ''}}">
        </div>
    </div>
</div>
