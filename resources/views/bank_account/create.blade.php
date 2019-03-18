@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Conta Corrente</h3>

        <form action="{{ route('bank_accounts.store') }}" method="POST">
            {{ csrf_field() }}
            @include('bank_account._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ route('bank_accounts.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
