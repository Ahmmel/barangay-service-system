<!-- Topbar -->
<nav
    class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button
        id="sidebarToggleTop"
        class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">
                    <?= ($notificationCount > 5) ? '5+' : $notificationCount ?>
                </span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">Notification Center</h6>

                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notif): ?>
                        <?php
                        $date = date('F j, Y', strtotime($notif['created_at']));
                        $message = htmlspecialchars($notif['message']);
                        $bgClass = 'bg-primary';
                        $icon = 'fas fa-calendar-check';

                        if (str_contains(strtolower($message), 'missed')) {
                            $bgClass = 'bg-warning';
                            $icon = 'fas fa-exclamation-triangle';
                        } elseif (str_contains(strtolower($message), 'queue')) {
                            $bgClass = 'bg-success';
                            $icon = 'fas fa-user-check';
                        } elseif (str_contains(strtolower($message), 'scheduled')) {
                            $bgClass = 'bg-primary';
                            $icon = 'fas fa-calendar-check';
                        }
                        ?>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle <?= $bgClass ?>">
                                    <i class="<?= $icon ?> text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500"><?= $date ?></div>
                                <span class="font-weight-bold"><?= $message ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="dropdown-item text-center small text-gray-500">No new alerts</div>
                <?php endif; ?>
                <?php if ($notificationCount > 5): ?>
                    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                <?php endif; ?>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a
                class="nav-link dropdown-toggle"
                href="#"
                id="userDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $sessionUsername; ?></span>
                <img
                    class="img-profile rounded-circle"
                    src="../images/undraw_profile.svg" />
            </a>
            <!-- Dropdown - User Information -->
            <div
                class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <!-- <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a> -->
                <!-- <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a> -->
                <!-- <div class="dropdown-divider"></div> -->
                <a
                    class="dropdown-item"
                    href="#"
                    data-toggle="modal"
                    data-target="#logoutModal">
                    <i
                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->