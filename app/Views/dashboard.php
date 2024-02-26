<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <a href="/pack/DaToIt" class="col-xl-3 col-md-6 mb-4">
            <div class="card card-order border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Quantity Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalItemOrder">
                                <?= $totalQtyOrder ? $totalQtyOrder : '0'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>


        <a href="/pack/DaPaIt" class="col-xl-3 col-md-6 mb-4">
            <div class="card card-order border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Packaged Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalItemPacking">
                                <?= $totalQtyPacking ? $totalQtyPacking : '0'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <a href="/pack/DaPaTo" class="col-xl-3 col-md-6 mb-4">
            <div class="card card-order border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Packing Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPackingHariIni">
                                <?= $totalPackingHariIni ? $totalPackingHariIni : '0'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <a href="/pack/DaToPa" class="col-xl-3 col-md-6 mb-4">
            <div class="card card-order border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Packing</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPacking">
                                <?= $totalPacking ? $totalPacking : '0'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1 class="h3 mb-2 text-gray-800 mr-2">Today's Packing Data</h1>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Packing</th>
                            <th>No Carton</th>
                            <th>ID Carton</th>
                            <th>Qty Carton</th>
                            <th>ID Item</th>
                            <th>Style Item</th>
                            <th>Color Item</th>
                            <th>Size Item</th>
                            <th>Qty Item</th>
                            <th>MO</th>
                            <th>Date Wh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $packingg = array_reverse($packingg); ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($packingg as $packing): ?>
                            <?php
                            $idCarton = $packing['id_carton'];

                            $cartonModel = new \App\Models\CartonModel();

                            $carton = $cartonModel->where('id_carton', $idCarton)->first();
                            ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
                                    <?= $packing['id_packing']; ?>
                                </td>
                                <td>
                                    <?= $carton['nomor_carton']; ?>
                                </td>
                                <td>
                                    <?= $packing['id_carton']; ?>
                                </td>
                                <td>
                                    <?= $packing['qty_carton']; ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        echo $cartonDetail['id_item'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['id_item'];
                                        $itemModel = new \App\Models\ItemModel();
                                        $item = $itemModel->where('id_item', $idItem)->first();
                                        echo $item['style'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['id_item'];
                                        $itemModel = new \App\Models\ItemModel();
                                        $item = $itemModel->where('id_item', $idItem)->first();
                                        echo $item['color'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['id_item'];
                                        $itemModel = new \App\Models\ItemModel();
                                        $item = $itemModel->where('id_item', $idItem)->first();
                                        echo $item['size'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['qty'];
                                        echo $idItem;

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['id_item'];
                                        $itemModel = new \App\Models\ItemModel();
                                        $item = $itemModel->where('id_item', $idItem)->first();
                                        echo $item['mo'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $cartonDetailModel = new \App\Models\CartonDetailModel();
                                    $cartonDetails = $cartonDetailModel->where('id_carton', $idCarton)->findAll();

                                    $itemCount = count($cartonDetails);
                                    foreach ($cartonDetails as $index => $cartonDetail) {
                                        $idItem = $cartonDetail['id_item'];
                                        $itemModel = new \App\Models\ItemModel();
                                        $item = $itemModel->where('id_item', $idItem)->first();
                                        echo $item['date_wh'];

                                        if ($index < ($itemCount - 1)) {
                                            echo ', ';
                                        }
                                    }
                                    ?>
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
<script src="<?= base_url() ?>js/demo/datatables-demo.js"></script>
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>

<script>
    function toggleClasses() {
        var iaElements = document.querySelectorAll('.DA');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.SC');
        isElements.forEach(function (element) {
            element.classList.toggle('collapsed');
        });

        var isElements = document.querySelectorAll('.MC');
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