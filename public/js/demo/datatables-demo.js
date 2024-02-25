// Call the dataTables jQuery plugin
$(document).ready(function() {
  // $('#dataTable').DataTable();

  var table = $('#dataTable').DataTable({
    buttons: ['copy', 'excel', 'pdf', 'colvis']
  });

  table.buttons().container()
    .appendTo('#dataTable_wrapper .col-md-6:eq(0)');
});
