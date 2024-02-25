$(document).ready(function () { // DONEEE
    getDataTabelStock();
    let qtyOrder = 0;
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        iconColor: 'white',
        showConfirmButton: false,
        customClass: {
            popup: 'colored-toast',
        },
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    $('#dataTable').on('click', '.edit-button', function () {
        idItem = $(this).data('id_item');
        qty = $(this).data('qty_stock');
        idToday = $(this).data('id_stock_today');
        qtyToday = $(this).data('qty_stock_today');
        qtyOrder = $(this).data('qty_order');

        if (idToday === 0) {
            Toast.fire({
                icon: 'warning',
                title: "Tidak ada data stock hari ini"
            });
            return;
        }

        var cek1 = qty - qtyToday;
        
        Swal.fire({
            title: "Edit Qty Stock Id Item : " + idItem,
            html:
                '<input type="text" id="quantity" class="swal2-input" value="' + qtyToday + '">',
            showCancelButton: true,
            confirmButtonText: "Save",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const editedQty = parseInt(Swal.getPopup().querySelector('#quantity').value, 10);
                var cek2 = cek1 + editedQty;

                if (cek2 > qtyOrder) {
                    Toast.fire({
                        icon: 'warning',
                        title: "Stock melebihi qty order."
                    });
                    return;
                }

                var editData = {
                    id: idItem,
                    qty: cek2,
                    id_detail: idToday,
                    qty_detail: editedQty
                };

                $.ajax({
                    url: '/edit-stock',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(editData),
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        }).then(() => {
                            getDataTabelStock();
                        });
                    },
                    error: function (response) {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        }).then(() => {
                        });
                    }
                });

            }
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    });

    $('#btn-cek-data').on('click', function () {
        var dateFrom = $('#dateFrom').val();
        var dateTo = $('#dateTo').val();

        if (dateFrom === "" || dateTo === "") {
            Toast.fire({
                icon: 'warning',
                title: "Silakan isi semua form."
            });
            return;
        }

        if (dateFrom > dateTo) {
            Toast.fire({
                icon: 'warning',
                title: "Tanggal mulai tidak boleh lebih besar dari tanggal selesai."
            });
            return;
        }

        var dateData = {
            date_from: dateFrom,
            date_to: dateTo
        };

        $.ajax({
            url: '/cek-data-stock',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(dateData),
            success: function (response) {
                if (response.success) {
                    var modalTable = $('#modalDataTableStock').DataTable();
                    modalTable.clear().draw();

                    response.data.forEach(function (item) {
                        modalTable.row.add([
                            item.id_item,
                            item.style,
                            item.color,
                            item.size,
                            item.qty,
                            item.date
                        ]).draw(false);
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
            },
            error: function (response) {
                Toast.fire({
                    icon: 'error',
                    title: "Terjadi kesalahan saat mengirim data ke server"
                });
            }
        });
    });

    $('#btn-batal').on('click', function () {
        $('#dateFrom').val('');
        $('#dateTo').val('');
        var modalTable = $('#modalDataTableStock').DataTable();
        modalTable.clear().draw();
    });

    $('#btn-export').on('click', function () {
        var cekData = $('#modalDataTableStock').DataTable().rows().data().length;

        if (cekData == 0) {
            Toast.fire({
                icon: 'warning',
                title: "Tidak bisa mengexport data yang kosong"
            });
            return;
        }

        var dateFrom = $('#dateFrom').val();
        var dateTo = $('#dateTo').val();

        if (!dateFrom || !dateTo) {
            Toast.fire({
                icon: 'warning',
                title: "Silakan isi kedua tanggal"
            });
            return;
        }

        exportDataStockHariIni(dateFrom, dateTo);
        $('#dateFrom').val('');
        $('#dateTo').val('');
        var modalTable = $('#modalDataTableStock').DataTable();
        modalTable.clear().draw();
        $('#pilihItem').modal('hide');
    });

    function exportDataStockHariIni(dateFrom, dateTo) {
        window.location = '/export-data-stock-hari-ini?dateFrom=' + dateFrom + '&dateTo=' + dateTo;
    }
});

var currentPage;

function getDataTabelStock() {
    $.ajax({
        url: '/data-stock',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            var stockTable = $('#dataTable').DataTable();
            currentPage = stockTable.page();
            stockTable.clear().draw();

            response.data.forEach(function (item, index) {
                var noUrut = index + 1;
                var actionColumn = '';
                if (item.role === 'admin') {
                    actionColumn = '<button type="button" class="btn btn-link font-24 edit-button" data-id_item="' + item.id_item + '" data-qty_order="' + item.qty_order + '" data-qty_stock="' + item.qty_stock + '" data-qty_stock_today="' + item.qty_stock_today + '" data-id_stock_today="' + item.id_stock_today + '"><i class="fas fa-edit"></i> Edit</button>';
                }
                stockTable.row.add([
                    noUrut,
                    item.id_item,
                    item.style,
                    item.mo,
                    item.color,
                    item.size,
                    item.qty_order,
                    item.qty_stock,
                    item.qty_packing,
                    item.wip,
                    item.wip_packing,
                    item.date,
                    actionColumn
                ]).draw(false);
            });
            stockTable.page(currentPage).draw(false);

            if ($('.edit-button').length === 0) {
                $('#actionHeader').hide();
                $('#dataTable').find('tr').each(function () {
                    $(this).find('td:last-child').hide();
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

