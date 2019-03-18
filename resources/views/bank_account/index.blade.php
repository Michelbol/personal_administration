@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Conta Corrente</h3>

        <a href="{{ route('bank_accounts.create') }}" class="btn btn-info">Nova Conta Corrente</a>
        <br><br>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Banco</th>
                <th>Ag</th>
                <th>Ação</th>
            </tr>
            </thead>

            <tbody>
            @foreach($bank_accounts as $bank_account)
                <tr>
                    <td>{{$bank_account->id}}</td>
                    <td>{{$bank_account->name}}</td>
                    <td>{{$bank_account->bank->name}}</td>
                    <td>{{$bank_account->agency}}</td>
                    <td>
                        <a href="{{route('bank_accounts.edit',['id'=>$bank_account->id])}}" class="btn btn-primary btn-sm">
                            Editar
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
