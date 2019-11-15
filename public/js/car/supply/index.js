$('#table_car_supplies').DataTable({
    processing: true,
    serverSide: true,
    ajax: carSuppliesUrlDataTables,
    columns: [
        { data: 'id', name: 'id' },
        { data: 'kilometer', name: 'kilometer' },
        { data: 'liters', name: 'liters' },
        { data: 'total_paid', name: 'total_paid' },
        { data: 'date', name: 'date' },
        { data: 'fuel', name: 'fuel' },
        { data: 'gas_station', name: 'gas_station' },
        { data: 'actions', name: 'actions' }
    ],
    columnDefs:[
        { className: 'text-center', targets: [2] }
    ]
});
