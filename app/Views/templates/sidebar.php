<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-15">
                    <img style="width: 80px; height: 30px; " src="<?= base_url() ?>img/logo-timw1.png">
                </div>
                <div class="sidebar-brand-text mx-3">Mingala System</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php if (session('userRole') === 'admin' || session('userRole') === 'packing'): ?>
                <li class="nav-item DA">
                    <a class="nav-link" href="/pack/dash">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>

                <li class="nav-item MA">
                    <a class="nav-link MC" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-database"></i>
                        <span>Master</span>
                    </a>
                    <div id="collapseUtilities" class="collapse MS" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item MCI" href="/pack/MaIt">Item</a>
                            <a class="collapse-item MCC" href="/pack/MaCa">Carton</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item SA">
                    <a class="nav-link SC" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
                        aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-qrcode"></i>
                        <span>Scan</span>
                    </a>
                    <div id="collapseTwo" class="collapse SS" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item SCS" href="/pack/ScSi">Stock In</a>
                            <a class="collapse-item SCP" href="/pack/ScPa">Packing</a>
                        </div>
                    </div>
                </li>


                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item RA">
                    <a class="nav-link RC" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                        aria-controls="collapsePages">
                        <i class="fas fa-fw fa-file"></i>
                        <span>Report</span>
                    </a>
                    <div id="collapsePages" class="collapse RS" aria-labelledby="headingPages"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item RCS" href="/pack/ReSt">Stock Item</a>
                            <a class="collapse-item RCP" href="/pack/RePa">Packing Tabel</a>
                        </div>
                    </div>
                </li>
            <?php else: ?>
                <li class="nav-item DA">
                    <a class="nav-link" href="/pack/dash">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>

                <!-- Nav Item - Utilities Collapse Menu -->
                <li class="nav-item MA">
                    <a class="nav-link MC" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-database"></i>
                        <span>Master</span>
                    </a>
                    <div id="collapseUtilities" class="collapse MS" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item MCI" href="/pack/MaIt">Item</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="<?= base_url() ?>#" id="userDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo session('userName'); ?>
                                </span>
                                <img class="img-profile rounded-circle" src="<?= base_url() ?>img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-header d-flex align-items-center">
                                    <img class="img-profile rounded-circle drop-pro-img"
                                        src="<?= base_url() ?>img/undraw_profile.svg">
                                    <div class="drop-pro-text ml-2">
                                        <div class="font-weight-bold text-gray-800 drop-pro-text1">
                                            <?php echo session('userName'); ?>
                                        </div>
                                        <div class="text-gray-500">
                                            <?php echo session('userEmail'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <?php if (session('userRole') === 'admin'): ?>
                                    <a class="dropdown-item" id="export-database">
                                        <i class="fas fa-database fa-lg fa-fw mr-2 text-gray-400"></i>
                                        Backup Database
                                    </a>
                                    <a class="dropdown-item" href="<?= base_url() ?>#" data-toggle="modal"
                                        data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="<?= base_url() ?>#" data-toggle="modal"
                                        data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-lg fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                <?php endif; ?>

                            </div>
                        </li>


                    </ul>

                </nav>
                <!-- End of Topbar -->