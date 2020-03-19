@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Carros</h3>

        <a href="{{ routeTenant('cars.create') }}" class="btn btn-info">Novo Carro</a>
        <br><br>

        <table id="table_car" class="table table-bordered table-striped" style="width: 100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Vencimento Licensiamento Anual</th>
                <th>Seguro Obrigatório Anual</th>
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
    $('#table_car').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('cars.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'model', name: 'model' },
            { data: 'license_plate', name: 'license_plate' },
            { data: 'annual_licensing', name: 'annual_licensing' },
            { data: 'annual_insurance', name: 'annual_insurance' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [2] }
        ]
    });
</script>
@endpush
