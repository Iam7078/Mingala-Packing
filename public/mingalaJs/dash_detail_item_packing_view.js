$(document).ready(function () { // DONEEE
    getDataPackagedItem();
});

var currentPage;
function getDataPackagedItem() {
    $.ajax({
        url: '/data-packaged-item',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            var stockTable = $('#dataTable').DataTable();
            currentPage = stockTable.page();
            stockTable.clear().draw();

            response.data.forEach(function (item, index) {
                var noUrut = index + 1;
                stockTable.row.add([
                    noUrut,
                    item.id_item,
                    item.style,
                    item.mo,
                    item.color,
                    item.size,
                    item.qty_order,
                    item.qty,
                    item.wip
                ]).draw(false);
            });

            stockTable.page(currentPage).draw(false);
            stockTable.columns.adjust().draw();
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
}