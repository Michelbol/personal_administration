@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Despesa</h3>

        <form action="{{ routeTenant('expense.update', ['expense' => $expense->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('expense._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('expense.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
        <h3>Média Geral da Despesa R$: {{ $averageExpense ? formatReal($averageExpense) : formatReal(0) }}</h3>
    </div>
@endsection
