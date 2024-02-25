$(document).ready(function () { // DONEEE

    var itemCount = 1;

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

    $('#nomor_carton').on('input', function () {
        var value = $(this).val();
        if (!/^[1-9]\d*$/.test(value)) {
            Toast.fire({
                icon: 'error',
                title: `Masukkan angka yang valid (angka bulat positif lebih dari 0).`
            }).then(() => {
                $(this).val('');
            });
        }
    });

    // Mengatasi tombol Add
    $("#btn-add-item").click(function (e) {
        e.preventDefault();

        var idItemUniqueId = 'id_item_' + itemCount;
        var qtyItemUniqueId = 'qty_' + itemCount;

        var idItemInput = '<div class="form-group"><label for="' + idItemUniqueId + '" class="h6 mb-2 text-gray-800">Id Item ' + itemCount + '</label><input type="text" class="form-control" id="' + idItemUniqueId + '" name="id_item[]" required></div>';

        var qtyItemInput = '<div class="form-group cek-isi"><label for="' + qtyItemUniqueId + '" class="h6 mb-2 text-gray-800">Qty Item ' + itemCount + '</label><input type="number" class="form-control" id="' + qtyItemUniqueId + '" name="qty[]" required></div>';

        if (itemCount > 6) {
            Toast.fire({
                icon: 'warning',
                title: 'Sudah cukup.'
            }).then(() => {
            });
        } else if (itemCount > 3) {
            $("#additional-items2").append(idItemInput + qtyItemInput);
            itemCount++;
        } else {
            $("#additional-items").append(idItemInput + qtyItemInput);
            itemCount++;
        }

        $('body').on('input', '.cek-isi input[type="number"]', function () {
            var value = $(this).val();
            if (!/^[1-9]\d*$/.test(value)) {
                Toast.fire({
                    icon: 'error',
                    title: `Masukkan angka yang valid (angka bulat positif lebih dari 0).`
                }).then(() => {
                    $(this).val('');
                });
            }
        });
    });

    // Save data carton
    $("#btn-stock-carton").click(async function (e) {
        e.preventDefault();

        var nomorCarton = $("#nomor_carton").val();

        if (!nomorCarton) {
            showErrorToast("Isi nomor carton dulu.");
            return;
        }

        var idItems = [];
        gatherIdItems(idItems, "#additional-items input[name='id_item[]']");
        gatherIdItems(idItems, "#additional-items2 input[name='id_item[]']");

        if ($("#additional-items").is(':empty')) {
            showErrorToast("Tambahkan setidaknya satu item sebelum mengirimkan formulir.");
            return;
        }

        if (!checkIdItemsOrder(idItems)) {
            showErrorToast("Isi form dengan urutan id_item yang benar.");
            return;
        }

        var allPromises = gatherPromises("#additional-items input[name='id_item[]']");
        allPromises = allPromises.concat(gatherPromises("#additional-items2 input[name='id_item[]']"));

        try {
            await Promise.all(allPromises);

            if (!checkAllInputsFilled()) {
                showErrorToast("Qty input is empty");
            } else {
                var isConfirmed = await showConfirmationDialog("Apakah Anda ingin menyimpan data?");

                if (isConfirmed) {
                    await saveData("/add-stock-carton", $("#form-input-carton").serializeArray());
                    resetForm();
                }
            }
        } catch (error) {
            return;
        }
    });

    function showErrorToast(message) {
        Toast.fire({
            icon: 'warning',
            title: message
        });
    }

    function gatherIdItems(idItems, selector) {
        $(selector).each(function () {
            idItems.push($(this).val());
        });
    }

    function checkIdItemsOrder(idItems) {
        for (var i = 0; i < idItems.length - 1; i++) {
            if (idItems[i] >= idItems[i + 1]) {
                return false;
            }
        }
        return true;
    }

    function gatherPromises(selector) {
        var promises = [];
        var errorOccurred = false;

        $(selector).each(function () {
            var id_item = $(this).val();
            var qtyInput = $(this).closest(".form-group").next().find("input[name='qty[]']").val();

            var cekData = { id_item: id_item };
            var promise = new Promise(function (resolve, reject) {
                $.ajax({
                    type: "POST",
                    url: "/cek-item-tambah-carton",
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(cekData),
                    success: function (existenceResponse) {
                        if (existenceResponse.exists) {
                            if (parseInt(qtyInput) <= 0 || parseInt(qtyInput) > (existenceResponse.qty_order - existenceResponse.qty_carton)) {
                                var hasilPengurangan = existenceResponse.qty_order - existenceResponse.qty_carton;
                                if (!errorOccurred) {
                                    showErrorToast(`Qty untuk item ${id_item} melebihi qty order.\nQty tersedia : ${hasilPengurangan}`);
                                    errorOccurred = true;
                                }
                                reject();
                            } else {
                                resolve();
                            }
                        } else {
                            if (!errorOccurred) {
                                showErrorToast(`Item dengan ID ${id_item} tidak ditemukan dalam database.`);
                                errorOccurred = true;
                            }
                            reject();
                        }
                    },
                    error: function (existenceError) {
                        reject();
                    }
                });
            });
            promises.push(promise);
        });
        return promises;
    }


    function checkAllInputsFilled() {
        var allInputsFilled = true;
        $("#additional-items input[name='id_item[]']").each(function () {
            var qtyInput = $(this).closest(".form-group").next().find("input[name='qty[]']").val();
            if (qtyInput.trim() === "") {
                allInputsFilled = false;
                return false;
            }
        });

        $("#additional-items2 input[name='id_item[]']").each(function () {
            var qtyInput = $(this).closest(".form-group").next().find("input[name='qty[]']").val();
            if (qtyInput.trim() === "") {
                allInputsFilled = false;
                return false;
            }
        });

        return allInputsFilled;
    }

    function showConfirmationDialog(message) {
        return new Promise(function (resolve) {
            Swal.fire({
                title: message,
                showCancelButton: true,
                confirmButtonText: "Simpan"
            }).then(function (result) {
                resolve(result.isConfirmed);
            });
        });
    }

    async function saveData(url, formData) {
        try {
            var response = await $.ajax({
                type: "POST",
                url: url,
                data: formData
            });

            Toast.fire({
                icon: 'success',
                title: 'Anda telah berhasil menambahkan data Carton ke database.'
            });
        } catch (error) {
            Toast.fire({
                icon: 'error',
                title: 'Gagal menyimpan data'
            });
        }
    }

    function resetForm() {
        $("#additional-items").empty();
        $("#additional-items2").empty();
        $("#additional-items input").empty();
        $("#additional-items2 input").empty();
        $('#nomor_carton').val('');
        $('#nomor_carton').focus();
        itemCount = 1;
    }
});
