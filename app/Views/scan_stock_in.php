<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Input Packing / Stock In</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body py-3 form-input-tambahan">
            <form id="form-input-tambahan" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" id="id_item" name="id_item"
                        style="max-width: 300px; margin: 0 auto;" required autofocus>
                </div>
            </form>
        </div>

        <div class="card-body" id="tabel-hasil" style="display: none;">
            <div class="table-responsive">
                <table class="table table-bordered" id="item-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Item</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty Order</th>
                            <th>Qty Stock</th>
                            <th>Qty Packing</th>
                            <th>WIP</th>
                        </tr>
                    </thead>
                    <tbody id="items">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-body py-3 form-stock-in" style="display: none;">
            <form id="form-stock-in" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" id="id_item2" name="id_item2"
                        style="max-width: 300px; margin: 0 auto;" required autofocus>
                </div>
            </form>
        </div>
        <div class="card-body hasil-pengecekan" id="tabel-hasil2"
            style="display: none; max-width: 400px; margin: 0 auto;">
            <div class="table-responsive">
                <table class="table table-bordered" id="item-table2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Item</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="items2">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-3 form-stock-in" id="button-save" style="display: none;">
            <a class="btn btn-success btn-icon-split" id="button-save-stock" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-check"></i>
                </span>
                <span class="text">Save</span>
            </a>
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
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/scan_stock_in_view.js"></script>

<script>
    function toggleClasses() {
        var iaElements = document.querySelectorAll('.SA');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
        });

        var isElements = document.querySelectorAll('.SS');
        isElements.forEach(function (element) {
            element.classList.toggle('show');
        });

        var iaElements = document.querySelectorAll('.SCS');
        iaElements.forEach(function (element) {
            element.classList.toggle('active');
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