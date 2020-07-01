@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Fornecedor</h3>

        <form action="{{ routeTenant('supplier.update', ['supplier' => $supplier->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('supplier._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('supplier.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
