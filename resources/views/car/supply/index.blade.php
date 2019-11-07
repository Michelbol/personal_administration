@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Abastecimentos Carro - {{ $car->license_plate }}</h3>

        <a href="{{ routeTenant('car_supply.create', ['car_id' => $car->id]) }}" class="btn btn-info">Novo Abastecimento</a>
        <br><br>

        <table id="table_car_supplies" class="table table-bordered table-striped" style="width: 100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Km do Abastencimento</th>
                <th>Litros Abastecidos</th>
                <th>Total Pago</th>
                <th>Data do Abastencimento</th>
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
    $('#table_car_supplies').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('car_supply.get', ['car_id' => $car->id]) !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'kilometer', name: 'kilometer' },
            { data: 'liters', name: 'liters' },
            { data: 'total_paid', name: 'total_paid' },
            { data: 'date', name: 'date' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [2] }
        ]
    });
</script>
@endpush
