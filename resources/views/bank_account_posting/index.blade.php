@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Lançamentos -  Conta Corrente: {{ $bankAccount->name }}</h3>

        <table class="table table-bordered">
                <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Data Lançamento</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Tipo de Lançamento</th>
            </tr>
            </thead>

            <tbody>
            @foreach($bankAccountPostings as $bankAccountPosting)
                <tr style="background: {{$bankAccountPosting->type === 'C' ? 'darkseagreen' : 'indianred'}}">
                    <td>{{$bankAccountPosting->id}}</td>
                    <td>{{$bankAccountPosting->document}}</td>
                    <td>{{$bankAccountPosting->posting_date}}</td>
                    <td>{{$bankAccountPosting->amount}}</td>
                    <td>{{$bankAccountPosting->type}}</td>
                    <td>{{$bankAccountPosting->typeBankAccountPosting->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <table id="table_bank_account" class="table table-striped table-bordered" width="100%">
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

            </tbody>
        </table>
    </div>
@endsection
