@extends('tribal.layouts.app')

@section('content')
    <div class="container">
        <h3>Serviços Para o jogo Tribal Wars</h3>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Coleta</h5>
                <p class="card-text">Auxilia na otimização da Coleta</p>
                <a href="{{ route('tribal.collect') }}" class="btn btn-primary">Coleta</a>
            </div>
        </div>
    </div>
@endsection

