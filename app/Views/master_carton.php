<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800 mr-2">Data Carton</h1>
        </div>
        <div class="col-md-6">
            <a class="btn btn-info btn-sm btn-icon-split" data-toggle="modal" data-target="#cetakPdfModal"
                style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-download"></i>
                </span>
                <span class="text">PDF</span>
            </a>
            <div class="btn-group" style="float: right;">
                <button type="button" class="btn btn-danger btn-sm btn-icon-split btn-jarak" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add Carton</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= base_url() ?>pack/MaCaAdSa">One Item</a>
                    <a class="dropdown-item" href="<?= base_url() ?>pack/MaCaAd">More Than One</a>
                </div>
            </div>
            <a class="btn btn-success btn-sm btn-icon-split btn-jarak" id="btn-import-excel" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-file-excel"></i>
                </span>
                <span class="text">Import</span>
            </a>
        </div>
    </div>
    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm tabel-carton" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Qr Code</th>
                            <th>ID Carton</th>
                            <th>No Carton</th>
                            <th>Qty Carton</th>
                            <th>ID Item</th>
                            <th>Style Item</th>
                            <th>Color Item</th>
                            <th>Size Item</th>
                            <th>Qty Item</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <body>
                        <?php $carton = array_reverse($carton); ?>
                        <?php $counter = 1; ?>
                        <?php include "phpqrcode/qrlib.php";
                        $penyimpanan = "cartonQr/";

                        if (!file_exists($penyimpanan))
                            mkdir($penyimpanan); ?>
                        <?php foreach ($carton as $carton): ?>
                            <?php
                            $idCarton = $carton['id_carton'];
                            $cobaCekModel = new \App\Models\CartonDetailModel();
                            $dataCobaCek = $cobaCekModel->where('id_carton', $idCarton)->findAll();

                            $idCarton = $carton['id_carton'];
                            QRcode::png($idCarton, $penyimpanan . $idCarton . ".png");

                            $idItems = [];
                            $qtys = [];
                            $iD = [];
                            foreach ($dataCobaCek as $item) {
                                $idItems[] = $item['id_item'];
                                $qtys[] = $item['qty'];
                                $iD[] = $item['id'];
                            }

                            $statusColor = 'red';
                            $statusText = 'Unfinished';
                            $packingModel = new \App\Models\PackingModel();
                            $cartonStatus = $packingModel->checkCartonStatus($idCarton);
                            if ($cartonStatus) {
                                $statusColor = 'green';
                                $statusText = 'Finished';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
                                    <img src="<?= base_url() ?>/cartonQr/<?= $carton['id_carton']; ?>.png" alt="QR Code">
                                </td>
                                <td>
                                    <?= $carton['id_carton']; ?>
                                </td>
                                <td>
                                    <?= $carton['nomor_carton']; ?>
                                </td>
                                <td>
                                    <?= $carton['qty_per_carton']; ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        echo "{$idItems[$i]}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $style = $item2['style'];
                                        }
                                        echo "{$style}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $color = $item2['color'];
                                        }
                                        echo "{$color}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $size = $item2['size'];
                                        }
                                        echo "{$size}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        echo "{$qtys[$i]}<br>";
                                    }
                                    ?>
                                </td>
                                <td style='padding: 5px;'>
                                    <span
                                        style='background-color: <?= $statusColor; ?>; color: white; padding: 3px 10px; border-radius: 5px;'>
                                        <?= $statusText; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-link font-24 " data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <?php for ($i = 0; $i < count($idItems); $i++): ?>
                                                <a class="dropdown-item edit-button" data-id="<?= $idItems[$i] ?>"
                                                    data-qty="<?= $qtys[$i] ?>" data-qty_car="<?= $carton['qty_per_carton'] ?>"
                                                    data-id_carton="<?= $carton['id_carton'] ?>" data-id_key="<?= $iD[$i] ?>">
                                                    <i class='fas fa-edit'></i> Edit
                                                    <?= $idItems[$i] ?>
                                                </a>
                                            <?php endfor; ?>
                                            <a class="dropdown-item delete-button" data-id="<?= $carton['id_carton'] ?>"
                                                data-status="<?= $statusText; ?>"
                                                data-role="<?php echo session('userRole'); ?>" data-toggle="modal"
                                                data-target="#confirmation-modal">
                                                <i class='fas fa-trash-alt'></i> Delete
                                            </a>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


</div>
<!-- End of Main Content -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="<?= base_url() ?>#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-log">
            <div class="modal-header">
                <h5 class="modal-title ti-log text-gray-800" id="exampleModalLabel">Confirmation</h5>
            </div>
            <div class="modal-body bo-log text-gray-800">
                Are you sure you want to leave this session?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-log" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-log" href="/logout-user">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal cetak PDF -->
<div class="modal fade" id="cetakPdfModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-log">
            <div class="modal-header">
                <h5 class="modal-title text-gray-800" id="exampleModalLabel">Cetak Kode QR ke PDF</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-gray-800" for="start-image-count">Nomor ID Awal</label>
                    <input type="number" class="form-control form-control-sm" id="start-image-count" min="1" max=""
                        step="1" value="1">
                </div>
                <div class="form-group">
                    <label class="text-gray-800" for="end-image-count">Nomor ID Akhir</label>
                    <input type="number" class="form-control form-control-sm" id="end-image-count" min="1" max=""
                        step="1" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeModalTutup" type="button" class="btn btn-secondary btn-sm"
                    data-dismiss="modal">Tutup</button>
                <button id="generate-pdf-button" type="button" class="btn btn-info btn-sm">Cetak</button>
            </div>
        </div>
    </div>
</div>

<div id="baseUrl" data-baseurl="<?= base_url() ?>"></div>

<script src="<?= base_url() ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url() ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>js/demo/tabel.js"></script>
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>js/jsPDF-1.3.2/dist/jspdf.debug.js"></script>
<script src="<?= base_url() ?>js/jsPDF-1.3.2/dist/jspdf.plugin.autotable.min.js"></script>
<script src="<?= base_url() ?>js/FileSaver.js-master/src/FileSaver.js"></script>
<script src="<?= base_url() ?>node_modules/jexcel/dist/jexcel.js"></script>
<script src="<?= base_url() ?>js/xlsx.full.min.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/master_carton_view.js"></script>

<script>
    function toggleClasses() {
        var iaElements = document.querySelectorAll('.MA');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.MS');
        isElements.forEach(function (element) {
            element.classList.toggle('show');
        });

        var iaElements = document.querySelectorAll('.MCC');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.SC');
        isElements.forEach(function (element) {
            element.classList.toggle('collapsed');
        });

        var isElements = document.querySelectorAll('.RC');
        isElements.forEach(function (element) {
            element.classList.toggle('collapsed');
        });

    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleClasses();
    });
</script>

</body>

</html>