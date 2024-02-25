<?php include('templates/header.php'); ?>
<?php include('templates/sidebar.php'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-10">
                    <h1 class="h3 mb-2 text-gray-800 mr-2">Details Qty Item Ordered</h1>
                </div>
                <div class="col-md-2">
                    <a class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" id="btn-order-report" style="float: right;"><i
                            class="fas fa-download fa-sm text-white-50"></i> Order Report</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm tabel-detail-order" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Style</th>
                            <th>MO</th>
                            <th>Qty Total Order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $item = array_reverse($item); ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($item as $item): ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
                                    <?= $item['style']; ?>
                                </td>
                                <td>
                                    <?= $item['mo']; ?>
                                </td>
                                <td>
                                    <?= $item['total_qty']; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-link font-24 view-button"
                                        data-style="<?= $item['style']; ?>"><i class="fas fa-file"></i> Details</button>
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
<script src="<?= base_url() ?>mingalaJs/total_qty_order_dash_view.js"></script>

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