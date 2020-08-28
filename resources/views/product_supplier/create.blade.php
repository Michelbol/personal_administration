@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Fornecedor</h3>
        <form action="{{ routeTenant('product.supplier.store', ['id' => Route::current()->parameter('id')]) }}" method="POST">
            {{ csrf_field() }}
            @include('product_supplier._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('product.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
