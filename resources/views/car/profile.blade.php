@extends('layouts.app')

@section('content')
<div class="container">
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="car-supply"></canvas>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        var carSupply = [{!! $carSupply !!}];
    </script>
    <script src="{{ asset('js/car/profile.js') }}"></script>
@endpush