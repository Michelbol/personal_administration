@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="">
            <div class="form-group">
                <h4>Informações Gerais</h4>
                <div class="row">
                    <div class="col-2">
                        <label for="number">Número</label>
                        <input readonly type="text" name="number" id="number" class="form-control"
                               value="{{ isset($invoice) ?  $invoice->number : ''}}">
                    </div>
                    <div class="col-5 form-group">
                        <label for="supplier">Fornecedor</label>
                        <input readonly type="text" name="supplier" id="supplier" class="form-control"
                               value="{{ isset($invoice) ?  $invoice->supplier->fantasy_name : ''}}">
                    </div>
                    <div class="col-2">
                        <label for="document">Cpf</label>
                        <input readonly type="text" name="document" id="document" class="form-control cpf"
                               value="{{ isset($invoice) ?  $invoice->document : ''}}">
                    </div>
                    <div class="col-3">
                        <label for="emission_at">Data da Emissão</label>
                        <input readonly type="text" name="emission_at" id="emission_at" class="form-control"
                               value="{{ isset($invoice) ?  $invoice->emission_at->format('d/m/Y H:i:s') : ''}}">
                    </div>
                </div>
            </div>
            <hr>
            <h4>Produtos</h4>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Produto no Sistema</th>
                    <th>Nome na Nota</th>
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
                        <td>
                            <div class="select-product">
                                <label for="product_id" class="d-none"></label>
                                <select
                                    name="product_id"
                                    data-id="{{ $invoiceProduct->id }}"
                                    class="form-control product {{ !isset($invoiceProduct->product_id) ? 'd-none' : '' }}"
                                >
                                    @foreach($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            {{ $invoiceProduct->product_id === $product->id ? 'selected' : '' }}
                                        >{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @if(!isset($invoiceProduct->product_id))
                                    <p class="text-center mb-0">
                                        <a href="{{ routeTenant('invoice_product.create.product', [$invoiceProduct->id]) }}" class="add_new_product">Cadastrar</a>
                                        -
                                        <a href="#" data-id="{{ $invoiceProduct->id }}" class="select_an_product">Selecionar Existente</a>
                                    </p>
                                @endif
                            </div>
                        </td>
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
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/invoices/edit.js') }}"></script>
@endpush
