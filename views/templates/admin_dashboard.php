<div class="row">

    <!-- Overall Rating -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-primary text-uppercase font-weight-bold mb-2">Overall Rating</h6>
                    <h4 class="font-weight-bold text-dark mb-0">
                        <?= $overallRatingPerTransactions; ?>
                    </h4>
                </div>
                <div class="icon-circle bg-primary text-white">
                    <i class="fas fa-star fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Annual Transactions -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-success text-uppercase font-weight-bold mb-2">Annual Transactions</h6>
                    <h4 class="font-weight-bold text-dark mb-0">
                        <?= $totalAnnualTransactions; ?>
                    </h4>
                </div>
                <div class="icon-circle bg-success text-white">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-info text-uppercase font-weight-bold mb-2">Total Users</h6>
                    <h4 class="font-weight-bold text-dark mb-0">
                        <?= $totalUsers; ?>
                    </h4>
                </div>
                <div class="icon-circle bg-info text-white">
                    <i class="fas fa-users fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Services -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-warning text-uppercase font-weight-bold mb-2">Total Services</h6>
                    <h4 class="font-weight-bold text-dark mb-0">
                        <?= $totalServices; ?>
                    </h4>
                </div>
                <div class="icon-circle bg-warning text-white">
                    <i class="fas fa-concierge-bell fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>