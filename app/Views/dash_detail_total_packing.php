<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-md-10">
                    <h1 class="h3 mb-2 text-gray-800 mr-2">Details Packing Total</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-10">
                    <h1 class="h6 mb-2 text-gray-800">Total Packing :
                        <?= $totalPacking ? $totalPacking : '0'; ?>
                    </h1>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm tabel-packingtoday" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Carton</th>
                            <th>Nomor</th>
                            <th>Qty</th>
                            <th>Id Item</th>
                            <th>Style</th>
                            <th>MO</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty Item</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($packingg as $packing): ?>
                            <?php
                            $idCarton = $packing['id_carton'];

                            $cartonModel = new \App\Models\CartonModel();
                            $carton = $cartonModel->where('id_carton', $idCarton)->first();

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
                                    <?= $packing['id_carton']; ?>
                                </td>
                                <td>
                                    <?= $carton['nomor_carton']; ?>
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
                                            $mo = $item2['mo'];
                                        }
                                        echo "{$mo}<br>";
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
<script src="<?= base_url() ?>js/demo/tabel.js"></script>
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