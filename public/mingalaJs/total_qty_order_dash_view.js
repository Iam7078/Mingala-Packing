$(document).ready(function () { // DONEEE
    $('#dataTable').on('click', '.view-button', function () {
        var dataStyle = $(this).data('style');
        
        getDataOrder(dataStyle);
    });

    function getDataOrder(dataStyle) {
        window.location = '/get-data-order-by-style?dataStyle=' + dataStyle;
    }

    $('#btn-order-report').on('click', function () {
        getOrderReport();
    });

    function getOrderReport() {
        window.location = '/get-order-report';
    }
});