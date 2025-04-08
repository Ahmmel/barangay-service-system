<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Service Handled Transaction (Monthly)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $totalMonthlyTransactions; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Handled Service Transaction (Annual)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $totalAnnualTransactions; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Users
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div
                                    class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?php echo $totalUsers; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Services
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $totalServices; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i
                            class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>