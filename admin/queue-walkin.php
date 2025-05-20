<?php
// Set timezone globally
date_default_timezone_set('Asia/Manila');

session_start();

// Include necessary files
include_once __DIR__ . '/../views/templates/admin_header.php';
include_once __DIR__ . '/../models/Queue.php';

$_SESSION["page_title"] = "Queue";

if ($_SESSION['user_role'] !== 'staff') {
    header('Location: dashboard.php');
    exit();
}

$queueModel = new Queue($db);
$walkinQueue = $queueModel->getTodayPendingQueues(1); // 1 = Walkin
$currentlyServing = null;

$isSchedQueueDisabled  = '';
if (!empty($walkinQueue)) {
    $currentlyServing = array_shift($walkinQueue);
    $currentTransactionCode = htmlspecialchars($currentlyServing['transaction_code']);
    $currentQueueId = (int)$currentlyServing['id'];
    $currentTransactionId = (int)($currentlyServing['id'] ?? 0);
    $scheduledDateTime = $currentlyServing['scheduled_date'] ?? '';
} else {
    $scheduledDateTime = '';
    $currentTransactionCode = '—';
    $currentQueueId = 0;
    $currentTransactionId = 0;
    $isSchedQueueDisabled = 'disabled';
}
?>
<style>
    /* Primary Colors */
    :root {
        --primary-black: #251f21;
        --primary-white: #f1f0ef;
        --primary-brown: rgb(224, 118, 80);
    }

    .queue-item {
        font-size: 16px;
        font-weight: bold;
        padding: 15px;
        background: var(--primary-white);
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background 0.3s ease, transform 0.3s ease;
        margin-bottom: 10px;
        height: 74px;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--primary-black);
    }

    .queue-item:hover {
        background: #e1d9e5;
        transform: scale(1.02);
    }

    .queue-item .transaction-code {
        color: var(--primary-brown);
    }

    .queue-item .name {
        color: var(--primary-brown);
    }

    .current-serving {
        background-color: var(--primary-white);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
        height: 400px;
        max-height: 100%;
    }

    .current-number {
        font-size: 65px;
        /* Increased size for large number */
        font-weight: bold;
        color: var(--primary-brown);
    }

    .timer {
        font-size: 48px;
        /* Larger timer font */
        color: var(--primary-brown);
    }

    .btn {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }

    .btn-secondary {
        background: var(--primary-brown);
        color: var(--primary-white);
        border: none;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        background: #a87f6f;
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background: var(--primary-brown);
        color: var(--primary-white);
        border: none;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background: #a87f6f;
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn:active {
        transform: translateY(2px);
        box-shadow: none;
    }

    .container-fluid {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .queue-item-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        max-height: 610px;
        overflow-y: auto;
        padding: 10px;
    }

    .queue-item-container::-webkit-scrollbar {
        display: none;
    }

    .clock {
        font-size: 30px;
        /* Large clock font size */
        font-weight: bold;
        color: var(--primary-black);
        margin-bottom: 20px;
        text-align: center;
        font-family: "Courier New", monospace;
        /* Digital clock font style */
    }

    h4 {
        font-weight: 700;
        color: var(--primary-black);
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        justify-content: space-between;
    }

    .col-lg-8,
    .col-md-8 {
        margin-top: 20px;
        flex: 0 0 100%;
    }

    .col-lg-4,
    .col-md-4 {
        margin-top: 20px;
        flex: 0 0 100%;
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
        <?php include(__DIR__ . '/../views/templates/top_bar.php') ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="container-fluid m-2">
                <div class="clock" id="clock">00:00:00</div>
                <!-- Walk-in Queue Section -->
                <div class="mb-4">
                    <h4>Walk-in Queue</h4>
                    <div class="row">
                        <!-- Walk-in Queue List -->
                        <div class="col-lg-8 col-md-8 queue-item-container" id="walkinQueueList">
                            <?php if (!empty($walkinQueue)): ?>
                                <?php foreach ($walkinQueue as $item): ?>
                                    <div
                                        class="queue-item mb-2"
                                        id="walkin-<?= htmlspecialchars($item['id']) ?>"
                                        data-transaction-id="<?= htmlspecialchars($item['id'] ?? '') ?>">
                                        <span class="transaction-code"><?= htmlspecialchars($item['transaction_code']) ?></span>
                                        <span class="name"><?= htmlspecialchars($item['display_name']) ?></span>
                                        <i class="fas fa-check-circle" style="color: var(--primary-brown)"></i>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-muted">No walk-in queues today.</div>
                            <?php endif; ?>
                        </div>
                        <!-- Current Serving for Walk-in Queue -->
                        <div class="col-lg-4 col-md-4">
                            <div class="current-serving" id="currentServingWalkin">
                                <h4>Now Serving Walk-in</h4>
                                <div class="current-number" id="walkinCurrentNumber">
                                    <?= $currentTransactionCode ?>
                                </div>
                                <div class="walkin-time text-muted text-center mt-2">
                                    <small>Elapsed Time: <span id="walkinElapsedTimer">00:00</span></small>
                                    <div id="walkinNoShowNote" class="text-danger mt-1" style="font-weight: bold; display: none;">
                                        You may now mark this as No Show.
                                    </div>
                                </div>
                                <button
                                    class="btn btn-primary"
                                    id="walkinStartTransaction"
                                    onclick="openUpdateTransactionModal(<?= $currentTransactionId ?>)"
                                    <?= $isSchedQueueDisabled ?>>
                                    <i class="fas fa-clipboard-check me-1"></i> Start Transaction
                                </button>

                                <button
                                    class="btn btn-secondary no-show-btn"
                                    id="walkinNoShow"
                                    disabled
                                    data-type="walkin"
                                    data-transaction-code="<?= $currentTransactionCode ?>">
                                    <i class="fas fa-user-times me-1"></i> Mark as No Show
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

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
    var isWalkinPage = true;
    var isTransactionPage = false;
    document.addEventListener("DOMContentLoaded", function() {
        const scheduledStart = new Date("<?= $scheduledDateTime ?>");

        function initElapsedTimer({
            timerId,
            noShowBtnId,
            noteId
        }) {
            const timerEl = document.getElementById(timerId);
            const noShowBtn = document.getElementById(noShowBtnId);
            const noteEl = document.getElementById(noteId);

            if (!timerEl || !noShowBtn || !noteEl) return;

            if (isNaN(scheduledStart.getTime())) {
                timerEl.textContent = "----";
                return;
            }

            function updateElapsedTime() {
                const now = new Date();
                const diffMs = now - scheduledStart;

                if (diffMs < 0) {
                    timerEl.textContent = "00:00";
                    return;
                }

                const diffMins = Math.floor(diffMs / 60000);
                const minutes = String(diffMins).padStart(2, "0");
                const seconds = String(Math.floor((diffMs % 60000) / 1000)).padStart(2, "0");

                timerEl.textContent = `${minutes}:${seconds}`;

                if (diffMins >= 10) {
                    noShowBtn.removeAttribute("disabled");
                    noteEl.style.display = "block";
                }
            }

            updateElapsedTime();
            setInterval(updateElapsedTime, 1000);
        }

        initElapsedTimer({
            timerId: "walkinElapsedTimer",
            noShowBtnId: "walkinNoShow",
            noteId: "walkinNoShowNote",
        });
    });
</script>