@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
            @foreach($bankAccounts as $bankAccount)
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">{{ $bankAccount->name }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Acumulou em Juros: R$: {{ $bankAccount::calcAnnualInterest($bankAccount->id )}}</p>
                        <a href="#balance-collapse-{{ $bankAccount->id }}" data-toggle="collapse">Mostrar Saldo</a>
                        <div class="collapse" id="balance-collapse-{{ $bankAccount->id }}">
                            <p class="card-text">Saldo: R$: {{ $bankAccount::lastBalance($bankAccount->id) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
    </div>
</div>
@endsection
