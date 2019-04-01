@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Despesas</h3>

        <a href="{{ route('expense.create') }}" class="btn btn-info">Nova Despesa</a>
        <br><br>

        <table id="table_expense" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Fixa</th>
                <th>Valor</th>
                <th>Dia Vencimento</th>
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
    $('#table_expense').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('expense.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'isFixed', name: 'isFixed' },
            { data: 'amount', name: 'amount' },
            { data: 'due_date', name: 'due_date' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [2] }
        ]
    });
</script>
@endpush
