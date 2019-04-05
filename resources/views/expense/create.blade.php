@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Despesa</h3>

        <form action="{{ routeTenant('expense.store') }}" method="POST">
            {{ csrf_field() }}
            @include('expense._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('expense.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
