<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800 mr-2">Stock Data Carton</h1>
        </div>
        <div class="col-md-6">
            <a class="btn btn-info btn-icon-split" id="btn-stock-carton" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-check"></i>
                </span>
                <span class="text">Save data</span>
            </a>
            <a class="btn btn-danger btn-icon-split btn-jarak" id="btn-add-item" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-plus-square"></i>
                </span>
                <span class="text">Add Item</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Form untuk menambahkan data item -->
            <form id="form-input-carton" method="post">
                <div class="form-group text-center">
                    <label for="nomor_carton" class="h6 mb-2 text-gray-800">Nomor Carton</label>
                    <input type="number" class="form-control" id="nomor_carton" name="nomor_carton"
                        style="max-width: 300px; margin: 0 auto;" autofocus required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="additional-items"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="additional-items2"></div>
                    </div>
                </div>
            </form>
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
<script src="<?= base_url() ?>mingalaJs/master_carton_add_view.js"></script>

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