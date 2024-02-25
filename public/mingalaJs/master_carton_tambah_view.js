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

    document.getElementById('closeModalButton').addEventListener('click', function () {
        const inputModal = document.querySelector('input[name="selectedItem"]:checked');

        if (inputModal) {
            inputModal.checked = false;
        }
    });

    document.getElementById('closeModalTutup').addEventListener('click', function () {
        const inputModal = document.querySelector('input[name="selectedItem"]:checked');

        if (inputModal) {
            inputModal.checked = false;
        }
    });


    document.getElementById('btn-pilih').addEventListener('click', function () {
        const selectedCheckboxes = document.querySelectorAll(".item-radio:checked");

        if (selectedCheckboxes.length === 0) {
            Toast.fire({
                icon: 'error',
                title: 'Pilih setidaknya satu ID Item sebelum menghitung.'
            });
            return;
        }

        const selectedItems = Array.from(selectedCheckboxes).map((checkbox) => checkbox.value);

        const selectedItemParts = selectedItems[0].split(',');

        $.ajax({
            type: "POST",
            url: "/check-qty-item-carton",
            data: {
                id_item: selectedItemParts[0]
            },
            success: function (response) {
                dbQty = response.qty_carton;

                const selectedItemID = document.getElementById('selectedItemId');
                const selectedItemStyle = document.getElementById('selectedItemStyle');
                const selectedItemColor = document.getElementById('selectedItemColor');
                const selectedItemSize = document.getElementById('selectedItemSize');
                const selectedItemQty = document.getElementById('selectedItemQty');

                selectedItemID.textContent = selectedItemParts[0];
                selectedItemQty.textContent = selectedItemParts[1] - dbQty;
                selectedItemStyle.textContent = selectedItemParts[2];
                selectedItemColor.textContent = selectedItemParts[3];
                selectedItemSize.textContent = selectedItemParts[4];

                $('#pilihItem').modal('hide');
                $('#form-pilih').show();
                $('#qty_pilih').focus();
                $('#button-pilih').show();
            },
            error: function (error) {
                Toast.fire({
                    icon: 'error',
                    title: `Terjadi kesalahan saat mengirim ke server.`
                }).then(() => {
                });
            }
        });
    });

    $('#qty_pilih').on('input', function () {
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

    $('#button-hitung').on('click', function () {
        var qtyCarton = $('#qty_pilih').val();

        if (!qtyCarton || isNaN(qtyCarton)) {
            alert('Masukkan jumlah karton yang valid.');
            return;
        }

        var qtyItem = parseInt(selectedItemQty.textContent) / parseInt(qtyCarton);
        var sisa = parseInt(selectedItemQty.textContent) % parseInt(qtyCarton);


        $('#hasilQtyCarton').text(Math.floor(qtyItem));
        $('#hasilQtyItem').text(qtyCarton);
        $('#hasilQtySisa').text(sisa);

        $('#button-pilih').hide();
        $('#button-batal').show();
        $('#button-save').show();
    });

    $('#button-batal').on('click', function () {

        $('#hasilQtyCarton').text('');
        $('#hasilQtyItem').text('');
        $('#hasilQtySisa').text('');
        $('#qty_pilih').val('');
        $('#qty_pilih').focus();
        $('#button-pilih').show();
        $('#button-batal').hide();
        $('#button-save').hide();
    });

    $('#button-save').on('click', function () {
        const jumlah = $('#hasilQtyCarton').text();
        const idItem = $('#selectedItemId').text();
        const qtyItem = $('#hasilQtyItem').text();

        if (jumlah == 0) {
            Toast.fire({
                icon: 'error',
                title: `Tidak dapat menambah carton dengan jumlah 0.`
            }).then(() => { });
            return;
        }

        Swal.fire({
            title: "Apakah nomor carton dilanjut?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Lanjut",
            denyButtonText: `Mulai Dari 1`
        }).then((result) => {
            const cek = result.isConfirmed ? 1 : 2;

            $.ajax({
                type: "POST",
                url: "/add-carton-satu-item",
                data: {
                    id_item: idItem,
                    qty: qtyItem,
                    jumlah: jumlah,
                    cek: cek
                },
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (error) {
                    Toast.fire({
                        icon: 'error',
                        title: `Terjadi kesalahan saat mengirim ke server.`
                    }).then(() => { });
                }
            });
        });
    });


});
