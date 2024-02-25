$(document).ready(function () { // DONEEE
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

    $('#qty').on('input', function () {
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

    document.getElementById('btn-stock-item').addEventListener('click', function () {
        event.preventDefault();

        var styleItem = $('#style').val();
        var colorItem = $('#color').val();
        var sizeItem = $('#size').val();
        var qtyItem = $('#qty').val();
        var moItem = $('#mo').val();
        var dateWhItem = $('#date_wh').val();

        if (!styleItem || !colorItem || !sizeItem || !qtyItem || !moItem || !dateWhItem) {
            Toast.fire({
                icon: 'warning',
                title: "Isi semua data dulu"
            }).then(() => {
            });
            return;
        }

        var tambahDataItem = {
            style: styleItem,
            color: colorItem,
            size: sizeItem,
            qty: qtyItem,
            mo: moItem,
            date_wh: dateWhItem
        };

        Swal.fire({
            title: "Apakah Anda ingin menyimpan data?",
            showCancelButton: true,
            confirmButtonText: "Simpan",
        }).then((result) => {
            if (result.isConfirmed) {
                function resetAllElements() {
                    $('#style').val('');
                    $('#color').val('');
                    $('#size').val('');
                    $('#qty').val('');
                    $('#mo').val('');
                    $('#date_wh').val('');
                    $('#mo').focus();
                }

                $.ajax({
                    url: '/cek-tambah-item',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(tambahDataItem),
                    success: function (response) {
                        if (response.duplicate) {
                            Toast.fire({
                                icon: 'error',
                                title: "Item dengan kombinasi yang sama sudah ada"
                            }).then(() => {
                            });
                        } else if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: "Berhasil menambahkan data item"
                            }).then(() => {
                                resetAllElements();
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Gagal menyimpan data"
                            }).then(() => {
                            });
                        }
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Terjadi kesalahan saat mengirim data."
                        }).then(() => {
                        });
                    }
                });
            }
        });
    });
});
