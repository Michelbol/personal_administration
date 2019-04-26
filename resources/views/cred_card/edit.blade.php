@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Cartão de Crédito</h3>

        <form action="{{ routeTenant('cred_card.update', ['id' => $cred_card->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('cred_card._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('cred_card.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
{{--        <form action="{{ routeTenant('cred_card.edit', [$bank_account->id]) }}">--}}
{{--            <div class="col-md-2">--}}
{{--                <label for="year">Ano do Orçamento</label>--}}
{{--                <input type="text" id="year" name="year" class="form-control"--}}
{{--                       value="{{ $year_search }}">--}}
{{--            </div>--}}
{{--        </form>--}}
{{--        <div class="chart-container" style="position: relative; height:40vh; width:80vw">--}}
{{--            <canvas id="myChart"></canvas>--}}
{{--        </div>--}}
    </div>
@endsection

@push('scripts')
{{--<script>--}}
{{--    var interest = [{!! $monthInterest !!}];--}}
{{--    var balance = [{!! $monthBalance !!}];--}}
{{-- </script>--}}
{{--<script src="{{ asset('js/cred_card/edit.js') }}"></script>--}}
@endpush
