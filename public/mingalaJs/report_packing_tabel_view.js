$(document).ready(function () { // DONEEE
    $('#btn-export-hari-ini').click(function () {
        var status = 1;
        exportDataPacking(status);
    });

    $('#btn-export-packing').click(function () {
        var status = 0;
        var table = $('#dataTable').DataTable();
        var data = table.rows({ search: 'applied' }).data();
    
        var dataArray = data.toArray().map(function(row) {
            return [row[0], row[1]];
        });
    
        var queryString = 'dataPacking=' + JSON.stringify(dataArray) + '&status=' + status;
    
        window.location = '/export-data-packing?' + queryString;
    });    

    function exportDataPacking(status) {
        window.location = '/export-data-packing?status=' + status;
    }
});