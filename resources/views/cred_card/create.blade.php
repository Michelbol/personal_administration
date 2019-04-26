@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Cartão de Crédito</h3>

        <form action="{{ routeTenant('cred_card.store') }}" method="POST">
            {{ csrf_field() }}
            @include('cred_card._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('cred_card.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
