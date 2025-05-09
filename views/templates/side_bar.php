<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center p-3" href="dashboard.php" data-ajax="true">
        <div class="sidebar-brand-icon">
            <img src="../images/qpila-logo.png" alt="QPila Logo" />
        </div>
    </a>

    <hr class="sidebar-divider my-0" />

    <!-- Dashboard -->
    <li class="nav-item <?= ($currentPage === 'dashboard.php') ? 'active' : '' ?>">
        <a class="nav-link" href="dashboard.php" data-ajax="true">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Users -->
    <li class="nav-item <?= ($currentPage === 'users.php') ? 'active' : '' ?>">
        <a class="nav-link" href="users.php">
            <i class="fas fa-fw fa-user"></i>
            <span>User</span>
        </a>
    </li>

    <?php if ($isAdmin): ?>
        <!-- Services -->
        <li class="nav-item <?= ($currentPage === 'services.php') ? 'active' : '' ?>">
            <a class="nav-link" href="services.php">
                <i class="fas fa-fw fa-suitcase"></i>
                <span>Services</span>
            </a>
        </li>

        <!-- Requirements -->
        <li class="nav-item <?= ($currentPage === 'requirements.php') ? 'active' : '' ?>">
            <a class="nav-link" href="requirements.php">
                <i class="fas fa-fw fa-suitcase"></i>
                <span>Service Requirements</span>
            </a>
        </li>
    <?php else: ?>
        <!-- Manage Queue (Staff Only) -->
        <li class="nav-item <?= in_array($currentPage, ['queue-sched.php', 'queue-walkin.php']) ? 'active' : '' ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#queueManagement" aria-expanded="true" aria-controls="queueManagement">
                <i class="fas fa-fw fa-tasks"></i>
                <span>Manage Queue</span>
            </a>
            <div id="queueManagement" class="collapse <?= in_array($currentPage, ['queue-sched.php', 'queue-walkin.php']) ? 'show' : '' ?>">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Queue Types:</h6>
                    <a class="collapse-item <?= ($currentPage === 'queue-sched.php') ? 'active' : '' ?>" href="queue-sched.php">Scheduled Queue</a>
                    <a class="collapse-item <?= ($currentPage === 'queue-walkin.php') ? 'active' : '' ?>" href="queue-walkin.php">Walk-in Queue</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Transactions -->
    <li class="nav-item <?= ($currentPage === 'transactions.php') ? 'active' : '' ?>">
        <a class="nav-link" href="transactions.php">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Transactions</span>
        </a>
    </li>

    <!-- System Settings -->
    <?php if ($isAdmin): ?>
        <li class="nav-item <?= ($currentPage === 'system-settings.php') ? 'active' : '' ?>">
            <a class="nav-link" href="system-settings.php">
                <i class="fas fa-fw fa-cogs"></i>
                <span>System Settings</span>
            </a>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider d-none d-md-block toggle-sidebar-divider" />

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>