<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<div class="container-fluid">

    <!-- Page Heading -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1 class="h3 mb-2 text-gray-800">Packing Tabel</h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group" style="float: right;">
                        <button type="button" class="btn btn-success btn-sm btn-icon-split btn-jarak"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-excel"></i>
                            </span>
                            <span class="text">Export</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" id="btn-export-hari-ini">Data Hari ini</a>
                            <a class="dropdown-item" id="btn-export-packing">Semua Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Packing</th>
                            <th>No Carton</th>
                            <th>Qty Carton</th>
                            <th>ID Item</th>
                            <th>Style Item</th>
                            <th>Color Item</th>
                            <th>Size Item</th>
                            <th>Qty Item</th>
                            <th>Qty Order</th>
                            <th>Qty Packing</th>
                            <th>WIP</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <body>
                        <?php $packing = array_reverse($packing); ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($packing as $packing): ?>
                            <?php
                            $idCarton = $packing['id_carton'];

                            $modelCarton = new \App\Models\CartonModel();
                            $dataModelCarton = $modelCarton->where('id_carton', $idCarton)->first();

                            $cobaCekModel = new \App\Models\CartonDetailModel();
                            $dataCobaCek = $cobaCekModel->where('id_carton', $idCarton)->findAll();

                            $idItems = [];
                            $qtys = [];
                            foreach ($dataCobaCek as $item) {
                                $idItems[] = $item['id_item'];
                                $qtys[] = $item['qty'];
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
                                    <?= $packing['id_packing']; ?>
                                </td>
                                <td>
                                    <?= $dataModelCarton['nomor_carton']; ?>
                                </td>
                                <td>
                                    <?= $packing['qty_carton']; ?>
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
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $qty = $item2['qty'];
                                        }
                                        echo "{$qty}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $detailModel = new \App\Models\CartonDetailModel();
                                        $resultt = $detailModel->sumQtyByItemId($idItems[$i]);
                                        $qtyPac = isset($resultt['total_qty']) ? $resultt['total_qty'] : 0;
                                        echo "{$qtyPac}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $detailModel = new \App\Models\CartonDetailModel();
                                        $resultt = $detailModel->sumQtyByItemId($idItems[$i]);
                                        $qtyPac = isset($resultt['total_qty']) ? $resultt['total_qty'] : 0;

                                        $cekItem2 = new \App\Models\ItemModel();
                                        $dataCekItem2 = $cekItem2->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem2 as $item3) {
                                            $qtyOrder = $item3['qty'];
                                        }
                                        $hasilPengurangan = $qtyOrder - $qtyPac;
                                        echo "{$hasilPengurangan}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= $packing['date']; ?>
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

<script src="<?= base_url() ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url() ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>js/demo/data-table.js"></script>
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/report_packing_tabel_view.js"></script>

<script>
    function toggleClasses() {
        var iaElements = document.querySelectorAll('.RA');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.RS');
        isElements.forEach(function (element) {
            element.classList.toggle('show');
        });

        var iaElements = document.querySelectorAll('.RCP');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.MC');
        isElements.forEach(function (element) {
            element.classList.toggle('collapsed');
        });

        var isElements = document.querySelectorAll('.SC');
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