@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Lançamentos -  Conta Corrente: {{ $bankAccount->name }}</h3>
        <div class="form-group">
            <div id="filters" class="row">
                <div class="form-group col-3">
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
                    <label for="dates">Data de Lançamento</label>
                    <input type="text" name="dates" id="dates" class="form-control">
                </div>
                <div class="col-4">
                    <button class="btn btn-info" id="search" style="margin-top: 8%">Pesquisar</button>
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#add_bank_account_posting" style="margin-top: 8%">Adicionar Lançamento</button>
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
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="add_bank_account_posting">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Lançamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <div class="container-fluid">
                        <form action="{{ route('bank_account_posting.store') }}" method="post" id="form_bank_acount_posting">
                            {{csrf_field()}}
                            <div class="row">
                                <input type="hidden" id="bank_account_id" name="bank_account_id" value="{{$bankAccount->id}}">
                                <div class="col-4 form-group">
                                    <label for="document">Documento</label>
                                    <input type="text" class="form-control" id="document" name="document" maxlength="15-">
                                </div>
                                <div class="col-4 form-group">
                                    <label for="posting_date">Data Lançamento</label>
                                    <input type="text" class="form-control" id="posting_date" name="posting_date">
                                </div>
                                <div class="col-4 form-group">
                                    <label for="amount">Valor</label>
                                    <input type="text" class="form-control money" id="amount" name="amount">
                                </div>
                                <div class="col-3 form-group">
                                    <label for="type">Tipo</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="C">Crédito</option>
                                        <option value="D">Débito</option>
                                    </select>
                                </div>
                                <div class="col-4 form-group">
                                    <label for="type_bank_account_posting_id">Tipo de Lançamento</label>
                                    <select name="type_bank_account_posting_id" id="type_bank_account_posting_id" class="form-control">
                                        <option value="0">Informe um tipo</option>
                                        @foreach($filter_type_bank_account_postings as $type_bank_account_postings)
                                            <option value="{{$type_bank_account_postings->id}}">{{$type_bank_account_postings->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="$('#form_bank_acount_posting').submit()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>

    $('input[name="dates"]').daterangepicker(configDataRangePicker());
    $('#dates').val(null);

    $('#dates').on('cancel.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        $('#dates').val('');
    });


    let config = configDataRangePicker();
    config.singleDatePicker = true;
    config.timePicker = true;
    config.timePicker24Hour = true;
    config.locale.format= "DD/MM/YYYY HH:mm";
    config.startDate = moment();
    $('#posting_date').daterangepicker(config);

    var datatable = $('#table_bank_account_posting').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
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
            { data: 'account_balance', name: 'account_balance' }
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
