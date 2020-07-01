@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Marcas</h3>

        <a href="{{ routeTenant('brand.create') }}" class="btn btn-info">Nova Marca</a>
        <br><br>

        <table id="table_brand" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
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
    $('#table_brand').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('brand.get') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions' }
        ],
        columnDefs:[
            { className: 'text-center', targets: [2] }
        ]
    });
</script>
@endpush
