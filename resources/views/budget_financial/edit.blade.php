@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Orçamento {{ $budgetFinancial->month($budgetFinancial->month).'/'.$budgetFinancial->year }}</h3>

        <br><br>

        <table id="table_expense" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Fixa</th>
                <th>Valor</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1</th>
                    <th>Salário</th>
                    <th>Receita</th>
                    <th><i class="fas fa-thumbs-up"></i></th>
                    <th>R$: 1.500,00</th>
                    <th>
                        <div class="btn-group"><a href="http://localhost:8000/income/4/edit" class="btn btn-primary btn-sm">
                                Editar
                            </a><a class="btn btn-dark btn-sm" href="#" onclick="event.preventDefault();
                                                        if(confirm('Deseja excluir este item?')){
                            document.getElementById('form-delete-4').submit();}">
                                Excluir
                            </a>
                            <form action="http://localhost:8000/income/4" method="post" id="form-delete-4">
                                <input type="hidden" name="_token" value="t1xaFcTrplMUuJd460VFLXRVS5yaU7ou75GhivV1">
                                <input type="text" hidden="" name="_method" value="DELETE">
                            </form></div>
                    </th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>Tim</th>
                    <th>Despesa</th>
                    <th><i class="fas fa-thumbs-up"></i></th>
                    <th>R$: 44,99</th>
                    <th>
                        <div class="btn-group"><a href="http://localhost:8000/income/4/edit" class="btn btn-primary btn-sm">
                                Editar
                            </a><a class="btn btn-dark btn-sm" href="#" onclick="event.preventDefault();
                                                        if(confirm('Deseja excluir este item?')){
                            document.getElementById('form-delete-4').submit();}">
                                Excluir
                            </a>
                            <form action="http://localhost:8000/income/4" method="post" id="form-delete-4">
                                <input type="hidden" name="_token" value="t1xaFcTrplMUuJd460VFLXRVS5yaU7ou75GhivV1">
                                <input type="text" hidden="" name="_method" value="DELETE">
                            </form></div>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    {{--$('#table_expense').DataTable({--}}
        {{--processing: true,--}}
        {{--serverSide: true,--}}
        {{--ajax: '{!! route('expense.get') !!}',--}}
        {{--columns: [--}}
            {{--{ data: 'id', name: 'id' },--}}
            {{--{ data: 'name', name: 'name' },--}}
            {{--{ data: 'isFixed', name: 'isFixed' },--}}
            {{--{ data: 'amount', name: 'amount' },--}}
            {{--{ data: 'actions', name: 'actions' }--}}
        {{--],--}}
        {{--columnDefs:[--}}
            {{--{ className: 'text-center', targets: [2] }--}}
        {{--]--}}
    {{--});--}}
</script>
@endpush
