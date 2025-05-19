<?php
require_once  __DIR__ . '/../models/Transaction.php';

// start the session
session_start();

// Include necessary files
include_once __DIR__ . '/../views/templates/admin_header.php';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$transaction = new Transaction($db);
$ratings = $transaction->getAllRatedTransactions();

// Build summary counts
$ratingCounts = array_fill(1, 5, 0);
foreach ($ratings as $r) {
    if ($r['rating'] >= 1 && $r['rating'] <= 5) {
        $ratingCounts[$r['rating']]++;
    }
}
$totalRatings = array_sum($ratingCounts);
?>
<style>
    .main-content {
        margin-left: 240px;
        padding: 40px;
        width: calc(100% - 240px);
        height: 100vh;
        overflow-y: auto;
    }

    .rating-summary {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 40px;
        max-width: 750px;
        margin-left: auto;
        margin-right: auto;
    }

    .rating-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 30px;
        color: #1e293b;
    }

    .rating-bar {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
    }

    .rating-stars {
        min-width: 80px;
        font-weight: 500;
        color: #facc15;
    }

    .progress {
        height: 10px;
        flex-grow: 1;
        margin: 0 15px;
        background-color: #e5e7eb;
        border-radius: 50px;
    }

    .progress-bar {
        background-color: #facc15;
    }

    .rating-count {
        min-width: 40px;
        text-align: right;
        font-weight: 500;
        color: #475569;
    }

    .rating-footer {
        text-align: center;
        font-size: 14px;
        color: #6b7280;
        margin-top: 20px;
    }

    .table-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
    }

    h5.table-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: #1e293b;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 20px;
        }

        .sidebar-space {
            display: none;
        }
    }
</style>
<!-- Sidebar -->
<?php include(__DIR__ . '/../views/templates/side_bar.php'); ?>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php include(__DIR__ . '/../views/templates/top_bar.php'); ?>

        <div class="main-content">

            <!-- Rating Summary Card -->
            <div class="rating-summary">
                <div class="rating-title">Transaction Rating Summary</div>

                <?php for ($i = 5; $i >= 1; $i--):
                    $percent = $totalRatings > 0 ? ($ratingCounts[$i] / $totalRatings) * 100 : 0;
                ?>
                    <div class="rating-bar">
                        <div class="rating-stars"><?= $i ?> <i class="fas fa-star"></i></div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
                        </div>
                        <div class="rating-count"><?= $ratingCounts[$i] ?></div>
                    </div>
                <?php endfor; ?>

                <div class="rating-footer">
                    Based on <?= $totalRatings ?> total rating<?= $totalRatings === 1 ? '' : 's' ?>
                </div>
            </div>

            <!-- DataTable -->
            <div class="table-container">
                <h5 class="table-title">Individual Ratings</h5>
                <div class="table-responsive">
                    <table id="ratingsTable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Transaction Code</th>
                                <th>Rating</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ratings as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                                    <td><?= htmlspecialchars($r['transaction_code']) ?></td>
                                    <td><i class="fas fa-star text-warning"></i> <?= (int)$r['rating'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include(__DIR__ . '/../views/templates/footer.php'); ?>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->

<?php
// Include footer template
include_once __DIR__ . '/../views/templates/admin_footer.php';
?>

<script>
    $(document).ready(function() {
        $("#ratingsTable").DataTable({
            responsive: true,
            pageLength: 5,
            lengthChange: false,
        });
    });
</script>