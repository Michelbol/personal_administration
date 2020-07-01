@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Produtos</h3>

{{--        <a href="{{ routeTenant('product.create') }}" class="btn btn-info">Novo produto</a>--}}
        <br><br>

        <table id="table_product" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
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
    $('#table_product').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! routeTenant('product.get') !!}',
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
