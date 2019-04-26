$('#table_cred_card').DataTable({
    processing: true,
    serverSide: true,
    ajax: url_data_table,
    columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'limit', name: 'limit' },
        { data: 'default_closing_date', name: 'default_closing_date' },
        { data: 'actions', name: 'actions' }
    ]
});