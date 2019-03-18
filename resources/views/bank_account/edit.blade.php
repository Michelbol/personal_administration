@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Conta Corrente</h3>

        <form action="{{ route('bank_accounts.update', ['id' => $bank_account->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('bank_account._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ route('bank_accounts.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
