<?php include('templates/header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 10px;">
        <h1 class="h3 mb-2 text-white">Mingala Packing</h1>
        <div>
            <a id="fullscreen-button" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrows-alt"></i>
                </span>
                <span class="text">FullScreen</span>
            </a>
            <a id="back-button" class="btn btn-danger btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Back</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3" id="cek-form-header" action="#">
            <form id="cek-form" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" id="id_carton" name="id_carton"
                        style="max-width: 400px; margin: 0 auto;" required autofocus>
                </div>
            </form>
        </div>
        <div class="card-body hasil-pengecekan" style="display: none;">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Carton</th>
                            <th>Qty Per Carton</th>
                            <th>ID Item</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Qty Item</th>
                            <th>Qty Stock</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable1Body">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-header py-3 form-input-tambahan" style="display: none;">
            <form id="form-input-tambahan" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" id="id_item" name="id_item"
                        style="max-width: 400px; margin: 0 auto;" required autofocus>
                </div>
            </form>
        </div>

        <div class="card-body hasil-pengecekan" style="display: none;">
            <div class="table-responsive">
                <table class="table table-bordered" id="item-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Item</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Mo</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="items">
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
<script src="<?= base_url() ?>vendor/sweet/sweet2.js"></script>

<script src="<?= base_url() ?>mingalaJs/sidebar_view.js"></script>
<script src="<?= base_url() ?>mingalaJs/scan_packing_view.js"></script>

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

        var iaElements = document.querySelectorAll('.SCP');
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

    const fullscreenButton = document.getElementById('fullscreen-button');
    fullscreenButton.addEventListener('click', toggleFullscreen);

    function toggleFullscreen() {
        if (document.documentElement.requestFullscreen) {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    }
    document.getElementById('back-button').addEventListener('click', function () {
        window.history.back();
    });
</script>

</body>

</html>