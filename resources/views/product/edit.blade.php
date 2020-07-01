@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Produto</h3>

        <form action="{{ routeTenant('product.update', ['product' => $product->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('product._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('product.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
        <br>
        <br>
        <table id="table_product_supplier" class="table table-striped table-bordered" width="100%">
            <thead>
            <tr>
                <th>Fornecedor</th>
                <th>Marca</th>
                <th>Código</th>
                <th>Unidade</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('product.modal')
@endsection
@push('scripts')
    <script>
        let url_data_table = '{!! routeTenant('product_supplier.get', [$product->id]) !!}';
    </script>
    <script src="{{ asset('js/product_supplier/index.js') }}"></script>
@endpush
