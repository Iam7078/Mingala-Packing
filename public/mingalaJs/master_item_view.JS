$(document).ready(function () { // DONEEE
    const baseUrlView = document.getElementById('baseUrl').getAttribute('data-baseurl');
    getDataTabelItem(baseUrlView);
    let itemPar = '';
    let iStyle = '';
    let iColor = '';
    let iSize = '';
    let iQty = '';
    let iMo = '';
    let iDate = '';
    let qtyCekTotal = '';
    let iHapus = '';


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

    // Edit item
    $('#dataTable').on('click', '.edit-button', function () {
        itemPar = $(this).data('id_item');
        iStyle = $(this).data('style');
        iColor = $(this).data('color');
        iSize = $(this).data('size');
        iQty = $(this).data('qty');
        iMo = $(this).data('mo');
        iDate = $(this).data('date_wh');
        qtyCekTotal = $(this).data('qty_di_carton');

        $('#styleEdit').val(iStyle);
        $('#colorEdit').val(iColor);
        $('#sizeEdit').val(iSize);
        $('#qtyEdit').val(iQty);
        $('#moEdit').val(iMo);
        $('#dateEdit').val(iDate);
    });


    document.getElementById('btn-edit-save').addEventListener('click', function () {
        var valStyle = $('#styleEdit').val();
        var valColor = $('#colorEdit').val();
        var valSize = $('#sizeEdit').val();
        var valQty = $('#qtyEdit').val();
        var valMo = $('#moEdit').val();
        var valDate = $('#dateEdit').val();

        if (!/^[1-9]\d*$/.test(valQty)) {
            Toast.fire({
                icon: 'error',
                title: `Masukkan angka yang valid (angka bulat positif lebih dari 0).`
            }).then(() => {
                $('#qtyEdit').val('').focus();
                return;
            });
        } else if (!valStyle || !valColor || !valSize || !valQty || !valMo || !valDate) {
            Toast.fire({
                icon: 'error',
                title: `Isi semua data dulu`
            }).then(() => {
                return;
            });
        } else if (valQty < qtyCekTotal) {
            Toast.fire({
                icon: 'error',
                title: `Qty di data carton sebanyak ${qtyCekTotal}, tidak boleh kurang dari itu.`
            }).then(() => {
                $('#qtyEdit').val('').focus();
                return;
            });
        } else {
            var editData = {
                id: itemPar,
                style: valStyle,
                color: valColor,
                size: valSize,
                qty: valQty,
                mo: valMo,
                date_wh: valDate
            };

            $.ajax({
                url: '/edit-item',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(editData),
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(() => {
                        $('#bd-example-modal-lg').modal('hide');
                        getDataTabelItem(baseUrlView);
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
    });


    // Delete item
    $('#dataTable').on('click', '.delete-button', function () {
        const iHapus = $(this).data('id');
        const iStatus = $(this).data('status');
        const iRole = $(this).data('role');
        const iDiCar = $(this).data('statuss');

        Swal.fire({
            title: "Apakah Anda yakin ingin menghapus item ini?",
            showCancelButton: true,
            confirmButtonText: "Hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                if (iDiCar !== 0 && iRole !== 'admin') {
                    return Toast.fire({
                        icon: 'error',
                        title: 'Tidak dapat menghapus item yang sudah ada data carton atau dipacking.'
                    });
                }

                const cekPac = iStatus === 0 ? 0 : 1;
                const cekCar = iDiCar === 0 ? 0 : 1;

                const deletedData = {
                    id_item: iHapus,
                    cekPac: cekPac,
                    cekCar: cekCar
                };

                $.ajax({
                    url: '/delete-item',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(deletedData),
                    success: function (response) {
                        const { success, message } = response;
                        const icon = success ? 'success' : 'error';

                        Toast.fire({
                            icon: icon,
                            title: message
                        }).then(() => {
                            if (success) {
                                getDataTabelItem(baseUrlView);
                            }
                        });
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Terjadi kesalahan saat mengirim data."
                        });
                    }
                });
            }
        });
    });


    // Import Excel
    document.getElementById('btn-import-excel').addEventListener('click', function () {
        var input = document.createElement('input');
        input.type = 'file';

        input.onchange = function (e) {
            var file = e.target.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('excelFile', file);

                $.ajax({
                    url: '/import-excel-item',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: "Data berhasil diimpor"
                            }).then(() => {
                                getDataTabelItem(baseUrlView);
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan saat mengimpor data: ' + response.error
                            }).then(() => {
                            });
                        }
                    },
                    error: function (error) {
                        if (error.responseJSON && error.responseJSON.error) {
                            Toast.fire({
                                icon: 'error',
                                title: error.responseJSON.error
                            }).then(() => {
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Terjadi kesalahan saat mengirim data"
                            }).then(() => {
                            });
                        }
                    }
                });
            }
        };
        input.click();
    });

    $('#get-data-modal-item').on('click', function () {
        getDataTabelItemModal();
    });


    // Cetak PDF
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


    document.getElementById('btn-pdf').addEventListener('click', function () {
        const selectedCheckboxes = document.querySelectorAll(".item-radio:checked");

        if (selectedCheckboxes.length === 0) {
            Toast.fire({
                icon: 'error',
                title: 'Pilih setidaknya satu ID Item sebelum mencetak.'
            });
            return;
        }

        const selectedItems = Array.from(selectedCheckboxes).map((checkbox) => checkbox.value);

        const pdf = new jsPDF();
        let xPosition = 6;
        let yPosition = 6;
        const imageWidth = 20;
        const imageHeight = 20;
        const fontSize = 3;
        const boxSize = 26;

        const baseUrl = baseUrlView + "/itemQr/";

        const printImages = (index) => {
            if (index < selectedItems.length) {
                const [idItem, qty, mo, style, color, size] = selectedItems[index].split(',');
                const imageNumber = idItem;
                const imageUrl = baseUrl + imageNumber + ".png";
                const image = new Image();
                image.src = imageUrl;

                image.addEventListener('load', function () {
                    for (let i = 0; i < qty; i++) {
                        pdf.rect(xPosition, yPosition, boxSize, boxSize);
                        const imageX = xPosition + (boxSize - imageWidth) / 2;
                        pdf.addImage(image, 'PNG', imageX, yPosition + 3, imageWidth, imageHeight);

                        pdf.setFontSize(fontSize);
                        const text = `${idItem}_${mo}`;
                        const textLength = pdf.getTextWidth(text);
                        const textX = xPosition + (boxSize - textLength) / 2;
                        const textY = yPosition + 4;
                        pdf.text(text, textX, textY);

                        const text2 = `${style}`;
                        const text2Length = pdf.getTextWidth(text2);
                        const text2X = xPosition + (boxSize - text2Length) / 2;
                        const text2Y = yPosition + imageHeight + 2;
                        pdf.text(text2, text2X, text2Y);

                        const text3 = `${color}_${size}`;
                        const text3Length = pdf.getTextWidth(text3);
                        const text3X = xPosition + (boxSize - text3Length) / 2;
                        const text3Y = yPosition + imageHeight + 4;
                        pdf.text(text3, text3X, text3Y);



                        xPosition += boxSize + 6;
                        if (xPosition + boxSize > pdf.internal.pageSize.width) {
                            xPosition = 6;
                            yPosition += boxSize + 6;
                            if (yPosition + boxSize > pdf.internal.pageSize.height) {
                                pdf.addPage();
                                yPosition = 6;
                            }
                        }
                    }

                    const currentDate = new Date();
                    const formatter = new Intl.DateTimeFormat('id-ID', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const formattedDate = formatter.format(currentDate);
                    const filename = `QrCode_${idItem}_${qty}_${formattedDate}.pdf`;
                    pdf.save(filename);
                    $('#cetakPdfModal').modal('hide');
                    const inputModal = document.querySelector('input[name="selectedItem"]:checked');

                    if (inputModal) {
                        inputModal.checked = false;
                    }
                    printImages(index + 1);
                });
            } else {
                console.log('Done printing.');
            }
        };
        printImages(0);
    });
});

var currentPage;
function getDataTabelItem(baseUrlView) {
    $.ajax({
        url: '/data-item',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            var itemTable = $('#dataTable').DataTable();
            currentPage = itemTable.page();
            itemTable.clear().draw();

            response.data.forEach(function (item, index) {
                var noUrut = index + 1;
                var qrCodeImg = '<img src="' + baseUrlView + '/itemQr/' + item.id_item + '.png" alt="QR Code">';
                var userStatusHtml = '<span style="background-color: ' + item.status_color + '; color: white; padding: 3px 10px; border-radius: 5px;">' + item.status_text + '</span>';
                var dropdownMenuHtml = `
                    <div class="btn-group">
                        <button type="button" class="btn btn-link font-24 " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                            <a class="dropdown-item edit-button" data-id_item="${item.id_item}" data-style="${item.style}" data-color="${item.color}" data-size="${item.size}" data-qty="${item.qty_order}" data-mo="${item.mo}" data-date_wh="${item.date_wh}" data-qty_di_carton="${item.qty_carton}" data-toggle="modal" data-target="#bd-example-modal-lg" id="edit-btn">
                                <i class='fas fa-edit'></i> Edit
                            </a>
                            <a class="dropdown-item delete-button" data-id="${item.id_item}" data-status="${item.qty_packing}" data-role="${item.userRole}" data-statuss="${item.qty_carton}">
                                <i class='fas fa-trash-alt'></i> Delete
                            </a>
                        </div>
                    </div>
                `;


                itemTable.row.add([
                    noUrut,
                    qrCodeImg,
                    item.id_item,
                    item.style,
                    item.mo,
                    item.color,
                    item.size,
                    item.qty_order,
                    item.date_wh,
                    userStatusHtml,
                    dropdownMenuHtml
                ]).draw(false);
            });
            itemTable.page(currentPage).draw(false);
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

var currentPageModal;
function getDataTabelItemModal() {
    $.ajax({
        url: '/data-item',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            var itemModalTable = $('#modalDataTable').DataTable();
            currentPageModal = itemModalTable.page();
            itemModalTable.clear().draw();

            response.data.forEach(function (item, index) {
                var radioId = 'inputModal' + item.id_item;
                var radioButtonHtml = `
                    <label class="custom-radio">
                        <input type="radio" class="item-radio" id="${radioId}" name="selectedItem"
                            value="${item.id_item},${item.qty_order},${item.mo},${item.style},${item.color},${item.size}">
                        <span class="radio-label"></span>
                    </label>
                `;
                
                itemModalTable.row.add([
                    item.id_item,
                    item.style,
                    item.mo,
                    item.color,
                    item.size,
                    item.qty_order,
                    radioButtonHtml
                ]).draw(false);
            });
            itemModalTable.page(currentPageModal).draw(false);
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
}