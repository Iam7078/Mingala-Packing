const scannedItems = [];
$(document).ready(function () { // DONEEE
    const hasilPengecekan = $('.hasil-pengecekan');
    const formInputTambahan = $('.form-input-tambahan');
    const itemListContainer = $('.item-list');
    let idCarton = '';
    let qtyFromServer = 0;
    let qtyPerCarton = '';
    idItemsFromServer = [];
    qtyFromServer = [];

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
        idItemsFromServer = [''];
        qtyFromServer = [''];
        $('#id_carton').val('');
        $('#id_item').val('');
        idCarton = '';
        qtyPerCarton = '';
        itemListContainer.hide();
        hasilPengecekan.hide();
        formInputTambahan.hide();
        $('#dataTable1Body').empty();
        $('#items').empty();
        $('#cek-form-header').show();
        $('#id_carton').focus();
        scannedItems.length = 0;
        itemStatus = {};
        itemsWithErrors.length = 0;
    }

    $("#cek-form").submit(function (event) {
        event.preventDefault();
        idCarton = $("#id_carton").val();
        if (idCarton) {
            $.ajax({
                url: '/cek-carton-semua',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ id_carton: idCarton }),
                success: function (response) {
                    if (response.success) {

                        let allDataValid = true;

                        for (var i = 0; i < response.id_item.length; i++) {
                            qtyPerCarton = response.qty_total_carton;
                            const hasilPengecekanBody = $('#dataTable1Body');
                            var qtyStock = response.qty_stock[i] - response.qty_packing[i];
                            var qtyItem = response.qty_per_carton[i];
                            var idItem = response.id_item[i];

                            if (qtyItem <= qtyStock) {
                                const newRow = $('<tr>');
                                newRow.append($('<td>').text(response.id_carton));
                                newRow.append($('<td>').text(response.qty_total_carton));
                                newRow.append($('<td>').text(response.id_item[i]));
                                newRow.append($('<td>').text(response.style[i]));
                                newRow.append($('<td>').text(response.color[i]));
                                newRow.append($('<td>').text(response.size[i]));
                                newRow.append($('<td>').text(response.qty_per_carton[i]));
                                newRow.append($('<td>').text(qtyStock));
                                hasilPengecekanBody.append(newRow);

                                idItemsFromServer.push(response.id_item[i]);
                                qtyFromServer.push(response.qty_per_carton[i]);

                            } else {
                                allDataValid = false;
                                break;
                            }
                        }

                        if (!allDataValid) {
                            Toast.fire({
                                icon: 'error',
                                title: `Qty untuk Id Item ${idItem} belum memenuhi qty ${qtyItem}, stock tersedia ${qtyStock}`
                            }).then(() => {
                                resetAllElements();
                            });
                        } else {
                            hasilPengecekan.show();
                            formInputTambahan.show();
                            $('#id_item').focus();
                            $('#cek-form-header').hide();
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        }).then(() => {
                            resetAllElements();
                        });
                    }
                },
                error: function (error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengakses server.'
                    }).then(() => {
                        resetAllElements();
                    });
                }
            });
        }
    });

    var itemStatus = {};
    var itemsWithErrors = [];

    $("#form-input-tambahan").submit(function (event) {
        event.preventDefault();
        const idItem = $("#id_item").val();

        if (idItemsFromServer.includes(idItem)) {
            $.ajax({
                url: '/scan-by-item',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ id_item: idItem }),
                success: function (itemData) {
                    if (itemData.id_item && itemData.style && itemData.color && itemData.size) {
                        const table = $('#items');
                        itemListContainer.show();
                        scannedItems.push(itemData);

                        const qtyForItem = qtyFromServer.find((qty, index) => idItemsFromServer[index] === idItem);
                        const itemCount = itemStatus[idItem] ? itemStatus[idItem] + 1 : 1;

                        if (itemCount > qtyForItem) {
                            Toast.fire({
                                icon: 'error',
                                title: `ID Item ${idItem} sudah melebihi Qty yang ada di carton.`
                            }).then(() => {
                                $('#id_item').val('');
                            });
                        } else {
                            const existingRow = table.find(`tr:has(td:contains(${itemData.id_item}))`);
                            if (existingRow.length > 0) {
                                const jumlahItemCell = existingRow.find('td:eq(5)');
                                const jumlahItem = parseInt(jumlahItemCell.text()) || 0;
                                jumlahItemCell.text(jumlahItem + 1);
                            } else {
                                const newRow = $('<tr>');
                                newRow.append($('<td>').text(itemData.id_item));
                                newRow.append($('<td>').text(itemData.style));
                                newRow.append($('<td>').text(itemData.color));
                                newRow.append($('<td>').text(itemData.size));
                                newRow.append($('<td>').text(itemData.mo));
                                newRow.append($('<td>').text('1'));
                                table.append(newRow);
                            }
                            $('#id_item').val('');
                        }

                        itemStatus[idItem] = itemCount;
                        const allItemsAreInserted = idItemsFromServer.every(id => {
                            const qtyForItem = qtyFromServer.find((qty, index) => idItemsFromServer[index] === id);
                            const itemCount = itemStatus[id] || 0;
                            return itemCount >= qtyForItem;
                        });

                        if (allItemsAreInserted) {
                            formInputTambahan.hide();
                            saveToDatabase();
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Data item tidak lengkap.'
                        }).then(() => {
                            $('#id_item').val('');
                        });
                    }
                }
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: 'ID Item tidak sesuai dengan isi Carton.'
            }).then(() => {
                $('#id_item').val('');
            });
        }
    });

    function saveToDatabase() {
        var hasilIdCar = $('#id_carton').val();
        const now = new Date().toLocaleString();

        $('#form-input-tambahan').prop('disabled', true);

        $.ajax({
            url: '/save-packing',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                id_carton: hasilIdCar,
                qty_carton: qtyPerCarton,
                date_cuy: now
            }),
            success: function (response) {
                if (response.id_packing) {
                    Swal.fire({
                        icon: "success",
                        title: "Packing Berhasil",
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        itemCount = 0;
                        $('#id_item').val('');
                        formInputTambahan.hide();
                        resetAllElements();
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan data ke database'
                    }).then(() => {
                        $('#id_item').val('');
                    });
                }
            },
            error: function (xhr, status, error) {
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan saat mengakses server.'
                }).then(() => {
                    formInputTambahan.hide();
                    itemListContainer.hide();
                });
            }
        });
    }

});