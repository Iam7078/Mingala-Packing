const scannedItems = [];
$(document).ready(function () {
    const hasilPengecekan = $('.hasil-pengecekan');
    const formInputTambahan = $('.form-input-tambahan');
    const itemListContainer = $('.item-list');
    let idCarton = '';
    let qtyFromServer = 0;
    let qtyPerCarton = '';

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
        $('#id_carton').val('');
        $('#id_item').val('');
        idCarton = '';
        qtyPerCarton = ';'
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
                url: '/check-carton',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ id_carton: idCarton }),
                success: function (data) {
                    if (data.exists) {
                        Toast.fire({
                            icon: 'error',
                            title: 'ID Carton ini sudah di packing.'
                        }).then(() => {
                            $('#id_carton').val('');
                        });
                    } else {
                        $.ajax({
                            url: '/scan-carton',
                            type: 'POST',
                            dataType: 'json',
                            contentType: 'application/json',
                            data: JSON.stringify({ id_carton: idCarton }),
                            success: function (data) {
                                if (data.id_carton && data.qty_per_carton) {
                                    qtyPerCarton = data.qty_per_carton;
                                    idItemsFromServer = [];
                                    qtyFromServer = [];
                                    const hasilPengecekanTable = $('#dataTable1');
                                    const hasilPengecekanBody = $('#dataTable1Body');
                                    let allDataValid = true;

                                    for (var i = 0; i < data.id_item.length; i++) {
                                        const stockQty = data.stock_qty[data.id_item[i]];
                                        const packingQty = data.qty_packing[data.id_item[i]];
                                        const hasilQty = stockQty - packingQty;
                                        const masukQty = data.qty[i];

                                        if (masukQty <= hasilQty) {
                                            const newRow = $('<tr>');
                                            newRow.append($('<td>').text(data.id_carton));
                                            newRow.append($('<td>').text(data.qty_per_carton));
                                            newRow.append($('<td>').text(data.id_item[i]));
                                            newRow.append($('<td>').text(data.style[i]));
                                            newRow.append($('<td>').text(data.color[i]));
                                            newRow.append($('<td>').text(data.size[i]));
                                            newRow.append($('<td>').text(data.qty[i]));
                                            newRow.append($('<td>').text(hasilQty));
                                            hasilPengecekanBody.append(newRow);

                                            idItemsFromServer.push(data.id_item[i]);
                                            qtyFromServer.push(data.qty[i]);
                                        } else {
                                            allDataValid = false;
                                        }
                                    }

                                    if (!allDataValid) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Qty item dalam stock belum terpenuhi.'
                                        }).then(() => {
                                            formInputTambahan.hide();
                                            itemListContainer.hide();
                                            $('#id_carton').val('');
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
                                        title: 'Carton dengan ID yang dimasukkan tidak ditemukan.'
                                    }).then(() => {
                                        formInputTambahan.hide();
                                        itemListContainer.hide();
                                        $('#id_carton').val('');
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

    var itemStatus = {};
    var itemsWithErrors = [];

    $("#form-input-tambahan").submit(function (event) {
        event.preventDefault();
        const idItem = $("#id_item").val();

        if (idItemsFromServer.includes(idItem)) {
            console.log(idItemsFromServer);
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

                        if (itemsWithErrors.includes(idItem)) {
                            $('#id_item').val('');
                        } else if (itemCount > qtyForItem) {
                            Toast.fire({
                                icon: 'error',
                                title: `ID Item ${idItem} sudah melebihi Qty yang diharapkan.`
                            }).then(() => {
                                itemsWithErrors.push(idItem);
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
            console.log(idItemsFromServer);
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
                        position: "top-end",
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