<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800 mr-2">Stock Data Carton</h1>
        </div>
        <div class="col-md-6">
            <a class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#pilihItem" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-plus-square"></i>
                </span>
                <span class="text">Select Items</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row carton-stock">
                <div class="col-md-6">
                    <p class="text-gray-800">ID Item : <span id="selectedItemId"></span></p>
                    <p class="text-gray-800">Style : <span id="selectedItemStyle"></span></p>
                    <p class="text-gray-800">Color : <span id="selectedItemColor"></span></p>
                    <p class="text-gray-800">Size : <span id="selectedItemSize"></span></p>
                </div>
                <div class="col-md-6">
                    <p class="text-gray-800">Qty Order : <span id="selectedItemQty"></span></p>
                    <p class="text-gray-800">Qty Carton : <span id="hasilQtyCarton"></span></p>
                    <p class="text-gray-800">Qty Item Per Carton : <span id="hasilQtyItem"></span></p>
                    <p class="text-gray-800">Sisa Qty : <span id="hasilQtySisa"></span></p>
                </div>
            </div>

            <div class="form-group text-center" id="form-pilih" style="margin-top: 30px; display: none;">
                <label for="qty_pilih_carton" class="h6 mb-2 text-gray-800">Qty Item Per Carton</label>
                <input type="number" class="form-control" id="qty_pilih" name="qty_pilih_carton"
                    style="max-width: 300px; margin: 0 auto;">
            </div>

            <div class="form-group" id="button-pilih" style="margin-top: 40px; display: none;">
                <a class="btn btn-success btn-icon-split" id="button-hitung" style="float: right;">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <span class="text">Check</span>
                </a>
            </div>

            <div class="form-group" id="button-save" style="margin-top: 40px; display: none;">
                <a class="btn btn-info btn-icon-split" id="button-hitung" style="float: right;">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Save</span>
                </a>
            </div>
            <div class="form-group" id="button-batal" style="margin-top: 40px; display: none;">
                <a class="btn btn-danger btn-icon-split btn-jarak" id="button-hitung" style="float: right;">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash"></i>
                    </span>
                    <span class="text">Cancel</span>
                </a>
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

<div class="modal fade" id="pilihItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-gray-800" id="exampleModalLabel">Select Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="modalDataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Item</th>
                                <th>Qty</th>
                                <th>Style</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Mo</th>
                                <th>Choose</th>
                            </tr>
                        </thead>
                        <tbody id="modal-tabel">
                            <?php $item = array_reverse($item); ?>
                            <?php foreach ($item as $item): ?>
                                <tr>
                                    <td>
                                        <?= $item['id_item']; ?>
                                    </td>
                                    <td>
                                        <?= $item['qty']; ?>
                                    </td>
                                    <td>
                                        <?= $item['style']; ?>
                                    </td>
                                    <td>
                                        <?= $item['color']; ?>
                                    </td>
                                    <td>
                                        <?= $item['size']; ?>
                                    </td>
                                    <td>
                                        <?= $item['mo']; ?>
                                    </td>
                                    <td>
                                        <label class="custom-radio">
                                            <input type="radio" class="item-radio" id="inputModal<?= $item['id_item']; ?>"
                                                name="selectedItem"
                                                value="<?php echo $item['id_item'] . ',' . $item['qty'] . ',' . $item['style'] . ',' . $item['color'] . ',' . $item['size']; ?>">
                                            <span class="radio-label"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeModalTutup" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn-pilih" type="button" class="btn btn-info">Select</button>
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
<script src="<?= base_url() ?>js/FileSaver.js-master/src/FileSaver.js"></script>
<script src="<?= base_url() ?>node_modules/jexcel/dist/jexcel.js"></script>
<script src="<?= base_url() ?>js/xlsx.full.min.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/master_carton_tambah_view.js"></script>

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