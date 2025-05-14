<?php
// start the session
session_start();

// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../models/Transaction.php';

$_SESSION["page_title"] = "Transactions";
$transaction = new Transaction($db);
$transactions = $isAdmin ? $transaction->getAllTransactions() : $transaction->getAllStaffTransactions($_SESSION['user_id']);
$isStaffAllowedToUpdate = !$isAdmin ? $transaction->isStaffAllowedToUpdate() : true;
$disabledAttr = !$isStaffAllowedToUpdate ? 'disabled' : '';
?>

<!-- Sidebar -->
<?php include('../views/templates/side_bar.php'); ?>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php include('../views/templates/top_bar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Button Group: Add and Search Transaction -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-success" <?= $disabledAttr ?> onclick="openAddTransactionModal()">
                    <i class="fas fa-plus me-1"></i> Add Transaction
                </button>

                <button type="button" class="btn btn-primary" <?= $disabledAttr ?> onclick="openSearchTransactionModal()">
                    <i class="fas fa-search me-1"></i> Search Transaction
                </button>
            </div>

            <!-- Table for Displaying Transactions -->
            <table class="table table-bordered table-striped" id="transactionTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Transaction Code</th>
                        <th>Applicant Name</th>
                        <th>Service Details</th>
                        <th>Date Requested</th>
                        <th>Date Last Update</th>
                        <th>Date Closed</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)) : ?>
                        <?php foreach ($transactions as $transaction) : ?>
                            <?php
                            $fullname = htmlspecialchars(trim(
                                $transaction['first_name'] . ' ' .
                                    ($transaction['last_name'] ?? '') . ' ' .
                                    ($transaction['middle_name'] ?? '') . ' ' .
                                    ($transaction['suffix'] ?? '')
                            ));

                            $services = explode(',', $transaction['services'] ?? '');
                            $servicesList = '<ul class="bullet-list mb-0">';
                            foreach ($services as $service) {
                                $servicesList .= '<li>' . htmlspecialchars(trim($service)) . '</li>';
                            }
                            $servicesList .= '</ul>';

                            $isActionVisible = !in_array($transaction['status'], ['Closed', 'Cancelled']);
                            ?>
                            <tr id="transactionData_<?= htmlspecialchars($transaction['transaction_id']) ?>">
                                <td><?= htmlspecialchars($transaction['transaction_code']) ?></td>
                                <td><?= $fullname ?></td>
                                <td><?= $servicesList ?></td>
                                <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                                <td><?= htmlspecialchars($transaction['updated_at']) ?></td>
                                <td><?= htmlspecialchars($transaction['date_closed']) ?></td>
                                <td><?= htmlspecialchars($transaction['status']) ?></td>
                                <td>
                                    <?php if ($isActionVisible  && $isStaffAllowedToUpdate): ?>
                                        <button class="btn btn-info btn-sm" onclick="openUpdateTransactionModal(<?= (int)$transaction['transaction_id'] ?>)">
                                            Update Status
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include('../views/templates/footer.php'); ?>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

<script>
    var isTransactionPage = true;
</script>

<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>