@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Notas</h3>

{{--        <a href="{{ routeTenant('invoice.create') }}" class="btn btn-info">Nova Nota</a>--}}
        <a href="{{ routeTenant('invoice.create.qr_code') }}" class="btn btn-info">Incluir Por QrCode</a>
        <br><br>

        <table id="table_invoice" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Serie</th>
                <th>Data da Emissão</th>
                <th>CPF</th>
                <th>Impostos</th>
                <th>Desconto</th>
                <th>Total dos Produtos</th>
                <th>Total Pago</th>
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
    $('#table_invoice').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('invoice.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'number', name: 'number' },
            { data: 'series', name: 'series' },
            { data: 'emission_at', name: 'emission_at' },
            { data: 'document', name: 'document' },
            { data: 'taxes', name: 'taxes' },
            { data: 'discount', name: 'discount' },
            { data: 'total_products', name: 'total_products' },
            { data: 'total_paid', name: 'total_paid' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [9] }
        ]
    });
</script>
@endpush
