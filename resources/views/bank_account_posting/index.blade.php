@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Lançamentos -  Conta Corrente: {{ $bankAccount->name }}</h3>
        <div class="form-group">
            <div id="filters" class="row">
                <div class="form-group col-3">
                    <label for="filter_type_bank_account_postings">Tipo de Lançamento</label>
                    <select name="filter_type_bank_account_postings" id="filter_type_bank_account_postings" class="form-control">
                        <option value="0">Informe para filtrar</option>
                        @foreach($filter_type_bank_account_postings as $type_bank_account_postings)
                            <option value="{{$type_bank_account_postings->id}}">{{$type_bank_account_postings->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-2">
                    <label for="filter_type">Tipo</label>
                    <select name="filter_type" id="filter_type" class="form-control">
                        <option value="0">Informe para filtrar</option>
                        <option value="C">Crédito</option>
                        <option value="D">Débito</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="dates">Data de Lançamento</label>
                    <input type="text" name="dates" id="dates" class="form-control">
                </div>
                <div class="col-4">
                    <button class="btn btn-info" id="search" style="margin-top: 8%">Pesquisar</button>
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#add_bank_account_posting" style="margin-top: 8%">Adicionar Lançamento</button>
                </div>
            </div>

        </div>

        <table id="table_bank_account_posting" class="table table-striped table-bordered" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Data Lançamento</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Tipo de Lançamento</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('bank_account_posting.modal_add_bank_account_posting')
@endsection

@push('scripts')
<script>
    var url_data_table = '{!! routeTenant('bank_account_posting.get', ['id' => $bankAccount->id]) !!}';
</script>
<script src="{{ asset('js/bank_account_posting/index.js') }}"></script>
@endpush
