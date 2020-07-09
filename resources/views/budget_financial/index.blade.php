@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Orçamento Financeiro</h3>
        </div>
        <form action="">
            <div class="row">
                <div class="col-md-4">
                    <label for="year">Ano do Orçamento</label>
                    <input type="text" id="year" name="year" class="form-control"
                           value="{{ isset($budgedFinancialYear) ? $budgedFinancialYear : 0 }}">
                </div>
                <div class="col-md-4">
                    <label for="user_id">Usuário</label>
                    <select class="form-control" name="user_id" id="user_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (isset($selected_user) && $selected_user->id === $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="visualization_view">Visualização</label>
                    <select class="form-control" name="visualization_view" id="visualization_view">
                        @foreach(\App\Models\Enum\VisualizationBudgetFinancial::getConstants() as $key => $visualization)
                        <option value="{{$visualization}}" {{ (isset($visualizationView) && $visualizationView === $visualization) ? 'selected' : '' }}>{{$visualization}}</option>
                        @endforeach
                    </select>
                </div>
                <button style="margin-top: 31px" class="form-group btn btn-info">Alterar informações</button>
            </div>
        </form>

        <div class="row" style="margin-top: 10px">

        @if(isset($budgetsFinancial))
            @if(!isset($visualizationView) || isset($visualizationView) && $visualizationView === \App\Models\Enum\VisualizationBudgetFinancial::TABLE)
                @include('budget_financial.table')
            @else
                @include('budget_financial.graphic')
            @endif
        @else
            <h3>Sem orçamentos!</h3>
       @endif
        </div>


    </div>
@endsection
