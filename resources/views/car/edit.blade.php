@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Carro</h3>

        <form action="{{ routeTenant('car.update', ['id' => $car->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('car._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('car.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
        <div class="chart-container" style="position: relative; height:40vh; width:80vw">
            <canvas id="myChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let dates = {!! $histories['dates'] !!};
        let values = {!! $histories['values'] !!};
    </script>
    <script src="{{ asset('js/car/edit.js') }}"></script>
@endpush
