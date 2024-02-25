<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1 class="h3 mb-2 text-gray-800 mr-2">Data Item</h1>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-info btn-sm btn-icon-split" id="get-data-modal-item" data-toggle="modal"
                        data-target="#cetakPdfModal" style="float: right;">
                        <span class="icon text-white-50">
                            <i class="fas fa-download"></i>
                        </span>
                        <span class="text">PDF</span>
                    </a>
                    <a class="btn btn-danger btn-sm btn-icon-split btn-jarak" href="<?= base_url() ?>pack/MaItAd"
                        style="float: right;">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus-square"></i>
                        </span>
                        <span class="text">Add Item</span>
                    </a>
                    <a class="btn btn-success btn-sm btn-icon-split btn-jarak" id="btn-import-excel"
                        style="float: right;">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-excel"></i>
                        </span>
                        <span class="text">Import</span>
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm tabel-item" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Qr Code</th>
                            <th>ID Item</th>
                            <th>Style</th>
                            <th>MO</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty Order</th>
                            <th>Date Wh</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <body id="data-tabel-item">

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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-gray-800" id="exampleModalLabel">Print QR Code to PDF</h5>
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
                                <th>Style</th>
                                <th>Mo</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Choose</th>
                            </tr>
                        </thead>
                        <tbody id="modal-tabel">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closeModalTutup" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn-pdf" type="button" class="btn btn-info">Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Edit Data Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-gray-800" for="style">Style</label>
                                <input type="text" class="form-control" id="styleEdit">
                            </div>
                            <div class="form-group">
                                <label class="text-gray-800" for="color">Color</label>
                                <input type="text" class="form-control" id="colorEdit">
                            </div>
                            <div class="form-group">
                                <label class="text-gray-800" for="size">Size</label>
                                <input type="text" class="form-control" id="sizeEdit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-gray-800" for="qty">Qty Order</label>
                                <input type="number" class="form-control" id="qtyEdit">
                            </div>
                            <div class="form-group">
                                <label class="text-gray-800" for="mo">Mo</label>
                                <input type="text" class="form-control" id="moEdit">
                            </div>
                            <div class="form-group">
                                <label class="text-gray-800" for="date_wh">Date WH</label>
                                <input type="text" class="form-control" id="dateEdit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="btn-edit-save">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete Data -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center font-18">
                <h4 class="padding-top-30 mb-30 weight-500">Are you sure you want to delete this data?</h4>
                <div class="padding-bottom-30 row" style="max-width: 170px; margin: 0 auto;">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                            data-dismiss="modal"><i class="fa fa-times"></i></button>
                        NO
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-info border-radius-100 btn-block confirmation-btn"
                            data-dismiss="modal" id="btn-save-delete"><i class="fa fa-check"></i></button>
                        YES
                    </div>
                </div>
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
<script src="<?= base_url() ?>mingalaJs/master_item_view.js"></script>

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

        var iaElements = document.querySelectorAll('.MCI');
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