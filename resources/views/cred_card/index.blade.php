@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Cartões de Crédito</h3>

        <a href="{{ routeTenant('cred_card.create') }}" class="btn btn-info">Novo Cartão de Crédito</a>
{{--        <a href="{{ routeTenant('cred_card.file') }}" class="btn btn-primary">Leitura de Arquivos</a>--}}
        <br><br>

        <table id="table_cred_card" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Limite</th>
                <th>Dt Fechamento Fatura</th>
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
    var url_data_table = '{!! routeTenant('cred_card.get') !!}';
</script>
<script src="{{ asset('js/cred_card/scripts.js') }}"></script>
@endpush
