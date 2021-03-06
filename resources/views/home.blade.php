@extends('layouts.app')

@section('content')
    <div class="container">
        <form class="form-inline" action="{{ routeTenant('home') }}">
            <div class="form-group mb-2 mx-sm-3">
                <label class="sr-only" for="year">Ano</label>
                <input type="text" id="year" name="year" class="form-control" value="{{ $year }}" placeholder="Ano">
            </div>
            <button class="btn btn-info mb-2" type="submit">Mudar o Ano</button>
        </form>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            @foreach($bankAccounts as $index => $bankAccount)
                <div class="col-md-3">
                    <div class="card " style="width: 18rem;">
                        <div class="card-title text-white"
                            {{ isset($bankAccount->bank->title_color) ? 'style=background-color:'.$bankAccount->bank->title_color."" : '' }}>
                            <p class="card-header">{{ $bankAccount->name }}</p>
                        </div>
                        <div class="card-body"
                            {{ isset($bankAccount->bank->body_color) ? 'style=background-color:'.$bankAccount->bank->body_color."" : '' }}>
                            <p class="card-text">Acumulou em Juros do ano: R$: {{ $service->calcAnnualInterest($bankAccount->id, $year)}}</p>
                            <a href="#balance-collapse-{{ $bankAccount->id }}" data-toggle="collapse">Mostrar Saldo</a>
                            <div class="collapse" id="balance-collapse-{{ $bankAccount->id }}">
                                <p class="card-text">Saldo: R$: {{ $service->lastBalance($bankAccount->id) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-title">
                        <p class="card-header">Carros Registrados {{ $total_cars }}</p>
                    </div>
                    <div class="card-body">
                        @foreach($cars as $car)
                            <a href="{{ routeTenant('car.profile', ['id' => $car->id]) }}">{{ $car->model.' - '.$car->license_plate }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
