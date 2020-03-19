@extends('layouts.app')

@section('content')
    <div class="container">
        <form class="form-inline" action="{{ routeTenant('cars.profile', [$id]) }}">
            <div class="form-group mb-2 mx-sm-3">
                <label class="sr-only" for="year">Ano</label>
                <input type="text" id="year" name="year" class="form-control" value="{{ $year }}" placeholder="Ano">
            </div>
            <button class="btn btn-info mb-2" type="submit">Mudar o Ano</button>
        </form>
    </div>
<div class="container">
    <div class="chart-container" style="position: relative; height:20vh; width:40vw">
        <canvas id="car-supply"></canvas>
        <canvas id="car-litters"></canvas>
        <canvas id="car-traveled-kilometers"></canvas>
        <canvas id="car-averages"></canvas>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        let carSupply = [{!! $totalPaid !!}];
        let liters = [{!! $liters !!}];
        let traveledKilometers = [{!! $traveledKilometers !!}];
        let averages = [{!! $averages !!}];
    </script>
    <script src="{{ asset('js/car/profile.js') }}"></script>
@endpush
