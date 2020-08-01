@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Conta Corrente</h3>

        <form action="{{ routeTenant('bank_accounts.update', ['bank_account' => $id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('bank_account._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('bank_accounts.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
        <form action="{{ routeTenant('bank_accounts.edit', [$id]) }}" method="GET">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <label for="period_date">Período do filtro</label>
                        <input type="text" id="period_date" name="period_date" class="form-control"
                               value="{{ $startAt. ' - '. $endAt }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info">Pesquisar</button>
        </form>
        <div class="chart-container" style="position: relative; height:40vh; width:80vw">
            <canvas id="myChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let interest = [{!! $monthInterest !!}];
    let balance = [{!! $monthBalance !!}];
</script>
<script src="{{ asset('js/bank_account/edit.js') }}"></script>
@endpush
