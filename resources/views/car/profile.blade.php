@extends('layouts.app')

@section('content')
<div class="container">
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
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