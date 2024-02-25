// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#dataTable').DataTable();

    $('#modalDataTable').DataTable({
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 5
    });

    $('#modalDataTableStock').DataTable({
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 5,
        searching: false,
        lengthChange: false
    });
});
