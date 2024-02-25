$(document).ready(function () { // DONEEE
    const formStockIn = $('.form-stock-in');
    var idStockIn = 0;
    var qtyStockIn = 0;
    var qtyOrder = 0;
    var qtyStock = 0;

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

    function resetAllElements() {
        idStockIn = 0;
        qtyStockIn = 0;
        qtyOrder = 0;
        qtyStock = 0;
        $("#id_item").val('');
        $("#id_item2").val('');
        $('#items').empty();
        $('#items2').empty();
        $('#tabel-hasil').hide();
        $('#tabel-hasil2').hide();
        $('#form-input-tambahan').show();
        formStockIn.hide();
        $('#id_item').focus();

    }

    $("#form-input-tambahan").submit(function (event) {
        event.preventDefault();
        idItem = $("#id_item").val();
        if (idItem) {
            $.ajax({
                url: '/scan-by-item',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ id_item: idItem }),
                success: function (itemData) {
                    if (itemData.success) {
                        idItemsFromServer = [];
                        qtyOrder = itemData.qty_order;
                        qtyStock = itemData.qty_stock;

                        if (qtyOrder == qtyStock) {
                            Toast.fire({
                                icon: 'error',
                                title: `Qty stock sudah melebihi pesanan.`
                            }).then(() => {
                                $('#id_item').val('');
                            });
                        } else {
                            const hasilScan = $('#items');
                            const kurangItem = itemData.qty_order - itemData.qty_stock;
                            const newRow = $('<tr>');
                            newRow.append($('<td>').text(itemData.id_item));
                            newRow.append($('<td>').text(itemData.style));
                            newRow.append($('<td>').text(itemData.color));
                            newRow.append($('<td>').text(itemData.size));
                            newRow.append($('<td>').text(itemData.qty_order));
                            newRow.append($('<td>').text(itemData.qty_stock));
                            newRow.append($('<td>').text(itemData.qty_packing));
                            newRow.append($('<td>').text(kurangItem));
                            hasilScan.append(newRow);

                            idItemsFromServer.push(itemData.id_item);


                            $('#tabel-hasil').show();
                            $('#form-input-tambahan').hide();
                            formStockIn.show();
                            $('#id_item2').focus();
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: `Item dengan ID ${idItem} tidak ditemukan dalam database.`
                        }).then(() => {
                            $('#id_item').val('');
                        });
                    }
                },
                error: function (error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengirim data ke server.'
                    });
                }
            });
        }
    });

    $("#form-stock-in").submit(function (event) {
        event.preventDefault();
        idItem2 = $("#id_item2").val();

        if (idItemsFromServer == idItem2) {
            const hasilScanStock = $('#items2');
            $('#tabel-hasil2').show();

            const existingRow = hasilScanStock.find(`tr:has(td:contains(${idItem2}))`);

            if (existingRow.length > 0) {
                const jumlahItemCell = existingRow.find('td:eq(1)');
                const jumlahItem = parseInt(jumlahItemCell.text()) || 0;
                const jumlahQty = qtyStock + jumlahItem;

                if (jumlahQty == qtyOrder) {
                    Toast.fire({
                        icon: 'error',
                        title: `Qty stock sudah melebihi pesanan.`
                    }).then(() => {
                        $('#id_item2').val('');
                    });
                } else {
                    jumlahItemCell.text(jumlahItem + 1);
                    qtyStockIn = jumlahItemCell.text();
                }
            } else {
                const newRow = $('<tr>');
                newRow.append($('<td>').text(idItem2));
                newRow.append($('<td>').text('1'));
                hasilScanStock.append(newRow);
                idStockIn = idItem2;
                qtyStockIn = 1;
            }
            $('#id_item2').val('');
        } else {
            Toast.fire({
                icon: 'error',
                title: `ID Item tidak sesuai.`
            }).then(() => {
                $('#id_item2').val('');
            });
        }
    });

    document.getElementById('button-save-stock').addEventListener('click', function () {
        var dataToSend = {
            id_item: idStockIn,
            qty: qtyStockIn
        };

        if (qtyStockIn == 0) {
            Toast.fire({
                icon: 'error',
                title: `Anda belum memasukkan item.`
            }).then(() => {
            });
        } else {
            Swal.fire({
                title: "Apakah Anda ingin menyimpan data?",
                showCancelButton: true,
                confirmButtonText: "Simpan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/add-to-stock',
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify(dataToSend),
                        success: function (response) {
                            if (response.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                }).then(() => {
                                    resetAllElements();
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        },
                        error: function (error) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan saat mengirim data ke server.'
                            });
                        }
                    });
                }
            });

        }
    });
});