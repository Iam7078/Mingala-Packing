<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<div class="container-fluid">

    <!-- Page Heading -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1 class="h3 mb-2 text-gray-800">Stock Item</h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group" data-toggle="modal" data-target="#pilihItem" style="float: right;">
                        <button type="button" class="btn btn-success btn-sm btn-icon-split btn-jarak">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-excel"></i>
                            </span>
                            <span class="text">Export</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm tabel-stock-in" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Id Item</th>
                            <th>Style</th>
                            <th>MO</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty Order</th>
                            <th>Qty Stock</th>
                            <th>Qty Packing</th>
                            <th>WIP</th>
                            <th>WIP Packing</th>
                            <th>Date</th>
                            <th id="actionHeader">Action</th>
                        </tr>
                    </thead>
                    <tbody id="data-tabel-stock">
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

<div class="modal fade" id="pilihItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-gray-800" id="exampleModalLabel">Data stok item per hari</h5>
            </div>
            <div class="modal-body">
                <form id="filterForm" style="margin-top: -10px;">
                    <div class="row mb-1">
                        <div class="col">
                            <label for="dateFrom">Date From</label>
                            <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                        </div>
                        <div class="col">
                            <label for="dateTo">Date To</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="dateTo" name="dateTo">
                                <div class="input-group-append">
                                    <button type="button" id="btn-cek-data">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="modalDataTableStock" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Item</th>
                                <th>Style</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Qty Stock</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="modal-tabel">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-batal" type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button id="btn-export" type="button" class="btn btn-success">Export</button>
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
<script src="<?= base_url() ?>vendor/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/jszip.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/pdfmake.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/vfs_fonts.js"></script>
<script src="<?= base_url() ?>vendor/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/buttons.print.min.js"></script>
<script src="<?= base_url() ?>vendor/datatables/buttons.colVis.min.js"></script>
<script src="<?= base_url() ?>js/demo/datatables-demo.js"></script>
<script src="<?= base_url() ?>js/demo/tabel.js"></script>
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/report_stock_in_view.js"></script>

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

        var iaElements = document.querySelectorAll('.RCS');
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