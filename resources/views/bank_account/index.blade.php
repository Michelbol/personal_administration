@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Conta Corrente</h3>

        <a href="{{ routeTenant('bank_accounts.create') }}" class="btn btn-info">Nova Conta Corrente</a>
        <a href="{{ routeTenant('bank_account_posting.file') }}" class="btn btn-primary">Leitura de Arquivos</a>
        <br><br>

        <table id="table_bank_account" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Banco</th>
                <th>Ag</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    var url_data_table = '{!! routeTenant('bank_account.get') !!}';
</script>
<script src="{{ asset('js/bank_account/index.js') }}"></script>
@endpush
