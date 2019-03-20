@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Conta Corrente</h3>

        <a href="{{ route('bank_accounts.create') }}" class="btn btn-info">Nova Conta Corrente</a>
        <a href="{{ route('bank_account_posting.file') }}" class="btn btn-primary">Leitura de Arquivos</a>
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
    $('#table_bank_account').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('bank_account.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'name_bank', name: 'banks.name' },
            { data: 'agency', name: 'agency' },
            { data: 'actions', name: 'actions' }
        ]
    });
</script>
@endpush
