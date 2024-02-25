document.addEventListener("DOMContentLoaded", function () { // DONEEE
    const fontName = "Helvetica";
    const fontSize = 11;
    let idCartonHapus = '';
    let statusText = '';
    let idCarton = '';
    let idItem = '';
    let qty = 0;
    let qtyCar = 0;
    const baseUrlView = document.getElementById('baseUrl').getAttribute('data-baseurl');

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


    // Import excel
    $('#btn-import-excel').on('click', function () {
        var input = document.createElement('input');
        input.type = 'file';

        input.onchange = function (e) {
            var file = e.target.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('excelFile', file);

                $.ajax({
                    url: '/import-excel-carton',
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
                                location.reload();
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


    $('#dataTable').on('click', '.edit-button', function () {
        idCarton = $(this).data('id_carton');
        idItem = $(this).data('id');
        qty = $(this).data('qty');
        qtyCar = $(this).data('qty_car');
        idKey = $(this).data('id_key');

        Swal.fire({
            title: "Edit Qty Id Item : " + idItem,
            html:
                '<input type="text" id="quantity" class="swal2-input" value="' + qty + '">',
            showCancelButton: true,
            confirmButtonText: "Save",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const editedQty = parseFloat(Swal.getPopup().querySelector('#quantity').value);

                var hasilQty = qtyCar - qty + editedQty;

                var editData = {
                    id_key: idKey,
                    id_item: idItem,
                    id_carton: idCarton,
                    qty_awal: qty,
                    qty: editedQty,
                    qty_per_carton: hasilQty
                };

                $.ajax({
                    url: '/edit-carton',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(editData),
                    success: function (response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            }).then(() => {
                            });
                        }
                    },
                    error: function (response) {
                        Toast.fire({
                            icon: 'error',
                            title: "Terjadi kesalahan saat mengirim data"
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

    $('#dataTable').on('click', '.delete-button', function () {
        idCartonHapus = $(this).data('id');
        statusText = $(this).data('status');
        statusRole = $(this).data('role');
        Swal.fire({
            title: "Apakah Anda yakin telah menghapus item ini?",
            showCancelButton: true,
            confirmButtonText: "Hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                if (statusText == 'Finished' && statusRole != 'admin') {
                    Toast.fire({
                        icon: 'error',
                        title: 'Tidak dapat menghapus karton yang sudah selesai.'
                    }).then(() => {
                    });
                } else {
                    if (statusText == 'Finished') {
                        cekPac = 1;
                    } else {
                        cekPac = 0;
                    }

                    var deletedCartonData = {
                        id_carton: idCartonHapus,
                        cek: cekPac
                    };

                    $.ajax({
                        url: '/delete-carton',
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify(deletedCartonData),
                        success: function (response) {
                            if (response.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                TToast.fire({
                                    icon: 'error',
                                    title: response.message
                                }).then(() => {
                                });
                            }
                        },
                        error: function (response) {
                            Toast.fire({
                                icon: 'error',
                                title: "Terjadi kesalahan saat mengirim data"
                            }).then(() => {
                            });
                        }
                    });
                }
            }
        });
    });


    // Cetak pdf
    $('#closeModalTutup').on('click', function () {
        $('#start-image-count').val('1');
        $('#end-image-count').val('1');
    });
    $('#generate-pdf-button').on('click', function () {
        const pdf = new jsPDF();
        const imageWidth = 65;
        const imageHeight = 65;
        const margin = 10;
        const startImageCountInput = document.getElementById("start-image-count");
        const endImageCountInput = document.getElementById("end-image-count");

        const awalCek = document.getElementById("start-image-count").value;
        const akhirCek = document.getElementById("end-image-count").value;

        if (parseInt(awalCek) > parseInt(akhirCek)) {
            Toast.fire({
                icon: 'error',
                title: "ID awal tidak boleh lebih besar dari ID akhir"
            });
            $('#start-image-count').val('1');
            $('#end-image-count').val('1');
            return;
        }

        const startImageCount = parseInt(startImageCountInput.value, 10);
        const endImageCount = parseInt(endImageCountInput.value, 10);
        const baseUrl = baseUrlView + "/cartonQr/";
        const boxWidth = 190;
        const boxHeight = 60;
        const rowHeight = 10;
        let tinggiAkhir = 0;
        let yPosition = margin;
        let xPosition = margin;

        async function addImageToPDF(imageUrl) {
            const image = new Image();
            image.src = imageUrl;
            await new Promise((resolve, reject) => {
                image.onload = function () {
                    const setBox = ((boxHeight - imageHeight) / 2);
                    const setBox2 = ((boxHeight - imageHeight) / 2) + tinggiAkhir;
                    pdf.setFontSize(12);
                    pdf.setFont("Calibri");
                    pdf.setDrawColor(0, 0, 0);
                    pdf.addImage(image, 'PNG', xPosition + setBox, yPosition + setBox2, imageWidth, imageHeight);
                    resolve();
                };
                image.onerror = reject;
            });
        }

        async function generatePDFforImage(i) {
            const imageNumber = "CR" + String(i).padStart(10, "0");
            const imageUrl = baseUrl + imageNumber + ".png";
            try {
                const data = await fetchData(imageNumber);
                if (data.success) {
                    const setBox = (boxHeight - imageHeight) / 2;
                    const tableYPosition = yPosition + 7;
                    const dynamicBoxHeight = boxHeight + Math.max(0, data.id_item.length - 3) * rowHeight;
                    tinggiAkhir = ((dynamicBoxHeight - imageHeight) / 2) - setBox;

                    await addImageToPDF(imageUrl);

                    tinggiAkhir = 0;

                    pdf.rect(xPosition, yPosition, boxWidth, dynamicBoxHeight, 'S');

                    const textID = `ID : ${imageNumber}     No Carton : ${data.nomor_carton}     Qty : ${data.qty_total_carton}     Gross Weight :`;

                    pdf.text(textID, imageWidth + 5, yPosition + 5);

                    const tableXPosition = xPosition + setBox + setBox + imageWidth;
                    const tableHeader = [['ID Item', 'Style', 'Color', 'Size', 'Qty']];
                    const tableBody = [];

                    for (let a = 0; a < data.id_item.length; a++) {
                        tableBody.push([
                            data.id_item[a],
                            data.style[a],
                            data.color[a],
                            data.size[a],
                            data.qty_per_carton[a]
                        ]);
                    }

                    pdf.autoTable({
                        startY: tableYPosition,
                        margin: { left: tableXPosition },
                        head: tableHeader,
                        body: tableBody,
                        theme: 'grid',
                        styles: {
                            font: fontName,
                            fontSize: fontSize,
                        },
                    });

                    yPosition += dynamicBoxHeight + margin;

                    if (yPosition + boxHeight + margin > pdf.internal.pageSize.height) {
                        pdf.addPage();
                        yPosition = margin;
                    }

                    if (i === endImageCount) {
                        savePDF(pdf, endImageCount - startImageCount + 1);
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                    $('#start-image-count').val('1');
                    $('#end-image-count').val('1');
                    return { error: true };
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: "Terjadi kesalahan saat mengakses server."
                });
                $('#start-image-count').val('1');
                $('#end-image-count').val('1');
                return { error: true };
            }
        }

        async function fetchData(imageNumber) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/scan-carton',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ id_carton: imageNumber }),
                    success: resolve,
                    error: reject
                });
            });
        }

        function savePDF(pdf, totalImages) {
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
            const filename = `CartonQrCode(${totalImages})_${formattedDate}.pdf`;
            pdf.save(filename);
            $('#cetakPdfModal').modal('hide');
            $('#start-image-count').val('1');
            $('#end-image-count').val('1');
        }

        async function generatePDFs() {
            const promises = [];

            for (let i = startImageCount; i <= endImageCount; i++) {
                try {
                    const result = await generatePDFforImage(i);
                    if (result.error) {
                        console.error(`Error in generatePDFforImage(${i}):`, result.error);
                        return;
                    }
                    promises.push(result);
                } catch (error) {
                    continue;
                }
            }

            $('#cetakPdfModal').modal('hide');
        }

        generatePDFs();
    });
});
