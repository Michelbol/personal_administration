@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Fornecedores</h3>

{{--        <a href="{{ routeTenant('supplier.create') }}" class="btn btn-info">Novo Fornecedor</a>--}}
        <br><br>

        <table id="table_supplier" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Razão Social</th>
                <th>Nome Fantasia</th>
                <th>CNPJ</th>
                <th>Endereço</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Cidade</th>
                <th>Estado</th>
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
    $('#table_supplier').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('supplier.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'company_name', name: 'company_name' },
            { data: 'fantasy_name', name: 'fantasy_name' },
            { data: 'cnpj', name: 'cnpj' },
            { data: 'address', name: 'address' },
            { data: 'address_number', name: 'address_number' },
            { data: 'neighborhood', name: 'neighborhood' },
            { data: 'city', name: 'city' },
            { data: 'state', name: 'state' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [9] }
        ]
    });
</script>
@endpush
