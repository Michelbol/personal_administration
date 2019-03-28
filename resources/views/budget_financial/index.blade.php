@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Orçamento Financeiro</h3>
            <div class="col-md-2">
                <label for="year">Ano do Orçamento</label>
                <input type="text" id="year" name="year" class="form-control">
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Janeiro - Concluido <i class="text-success fas fa-check-circle"></i></p>
                    </div>
                    <div class="card-body">
                        <p class="card-text" style="">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary disabled">Finalizado</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Fevereiro - Concluido <i class="text-success fas fa-check-circle"></i></p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary disabled">Finalizado</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Março</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Abril</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Maio</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Junho</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Julho</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Agosto</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Setembro</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Outubro</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card " style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Novembro</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <div class="card-title">
                        <p class="card-header">Dezembro</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Receitas:</p>
                        <p class="card-text">Total Despesas:</p>
                        <a href="#" class="btn btn-primary">Planejar</a>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
<script>

</script>
@endpush
