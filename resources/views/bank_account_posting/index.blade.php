@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Lançamentos -  Conta Corrente: {{ $bankAccount->name }}</h3>
        <div class="form-group">
            <div id="filters" class="row">
                <div class="form-group col-4">
                    <label for="filter_type_bank_account_postings">Tipo de Lançamento</label>
                    <select name="filter_type_bank_account_postings" id="filter_type_bank_account_postings" class="form-control">
                        <option value="0">Informe para filtrar</option>
                        @foreach($filter_type_bank_account_postings as $type_bank_account_postings)
                            <option value="{{$type_bank_account_postings->id}}">{{$type_bank_account_postings->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-2">
                    <label for="filter_type">Tipo</label>
                    <select name="filter_type" id="filter_type" class="form-control">
                        <option value="0">Informe para filtrar</option>
                        <option value="C">Crédito</option>
                        <option value="D">Débito</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="filter_type">Data de Lançamento</label>
                    <input type="text" name="dates" id="dates" class="form-control">
                </div>
                <div class="col-1">
                    <button class="btn btn-info" id="search" style="margin-top: 45%">Pesquisar</button>
                </div>
            </div>

        </div>

        <table id="table_bank_account_posting" class="table table-striped table-bordered" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Data Lançamento</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Tipo de Lançamento</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>

    $('input[name="dates"]').daterangepicker();
    $('#dates').val(null);

    $('#dates').on('cancel.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        $('#dates').val('');
    });
    var datatable = $('#table_bank_account_posting').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('bank_account_posting.get', ['id' => $bankAccount->id]) !!}',
            data: function (s) {
                s.type_name     = $('#filter_type_bank_account_postings').val();
                s.type          = $('#filter_type').val();
                s.posting_date  = $('#dates').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'document', name: 'document' },
            { data: 'posting_date', name: 'posting_date' },
            { data: 'amount', name: 'amount' },
            { data: 'type', name: 'type' },
            { data: 'type_name', name: 'type_bank_account_postings.name' },
        ],
        createdRow: function( row, data, dataIndex ) {
            if ( data['type'] === "Crédito" ) {
                $(row).css('background', 'darkseagreen' );
            }else{
                $(row).css('background', 'indianred' );
            }
        }
    });

    $('#search').on('click', function () {
       datatable.draw();
    });
</script>
@endpush
