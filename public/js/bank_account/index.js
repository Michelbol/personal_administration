$('#table_bank_account').DataTable({
    processing: true,
    serverSide: true,
    ajax: url_data_table,
    columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'name_bank', name: 'banks.name' },
        { data: 'agency', name: 'agency' },
        { data: 'actions', name: 'actions' }
    ]
});