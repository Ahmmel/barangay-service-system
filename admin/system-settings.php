<?php
session_start();

include_once __DIR__ . '/../views/templates/admin_header.php';
include_once __DIR__ . '/../models/SystemSettings.php';

$_SESSION["page_title"] = "System Settings";

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$systemSettings = SystemSettings::getInstance($db);

$settings = [
    'booking_time_start' => $systemSettings->get('booking_time_start', '08:00'),
    'booking_time_end' => $systemSettings->get('booking_time_end', '20:00'),
    'minimum_booking_lead_time_minutes' => $systemSettings->get('minimum_booking_lead_time_minutes', 30),
    'max_transactions_per_day' => $systemSettings->get('max_transactions_per_day', 10),
    'no_show_timeout_minutes' => $systemSettings->get('no_show_timeout_minutes', 15),
    'sms_sender_name' => $systemSettings->get('sms_sender_name', ''),
    'enable_sms_notifications' => $systemSettings->get('enable_sms_notifications', '0') === '1' ? 'checked' : '',
    'staff_update_cutoff_time' => $systemSettings->get('staff_update_cutoff_time', '17:00'),
    'staff_update_start_time' => $systemSettings->get('staff_update_start_time', '07:00'),
    'saturday_start_time' => $systemSettings->get('saturday_start_time', '08:00'),
    'saturday_end_time' => $systemSettings->get('saturday_end_time', '12:00'),
    'enable_saturday' => $systemSettings->get('enable_saturday', '0') === '1' ? 'checked' : ''
];
?>

<?php include(__DIR__ . '/../views/templates/side_bar.php'); ?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include(__DIR__ . '/../views/templates/top_bar.php'); ?>
        <div class="container my-5">
            <h2 class="mb-4 text-primary">
                <i class="fas fa-cog me-2"></i>System Settings
            </h2>

            <form id="settingsForm" class="card shadow-sm p-4 needs-validation" action="save_settings.php" method="POST" novalidate>
                <form id="settingsForm" class="card shadow-sm p-4 needs-validation" action="save_settings.php" method="POST" novalidate>
                    <h5 class="text-secondary mb-3">Booking Settings</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="booking_time_start" class="form-label">
                                Booking Time Start
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Earliest time users can book a service."></i>
                            </label>
                            <input type="time" class="form-control" id="booking_time_start" name="booking_time_start"
                                required value="<?= htmlspecialchars($settings['booking_time_start']) ?>">
                            <div class="invalid-feedback">Please set a valid start time.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="booking_time_end" class="form-label">
                                Booking Time End
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Latest time users can book a service."></i>
                            </label>
                            <input type="time" class="form-control" id="booking_time_end" name="booking_time_end"
                                required value="<?= htmlspecialchars($settings['booking_time_end']) ?>">
                            <div class="invalid-feedback">Please set a valid end time.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="minimum_booking_lead_time_minutes" class="form-label">
                                Minimum Lead Time (mins)
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Minimum time required before a booking can be made."></i>
                            </label>
                            <input type="number" class="form-control" id="minimum_booking_lead_time_minutes"
                                name="minimum_booking_lead_time_minutes" min="0" required
                                value="<?= htmlspecialchars($settings['minimum_booking_lead_time_minutes']) ?>">
                            <div class="invalid-feedback">Please enter a valid number of minutes.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="max_transactions_per_day" class="form-label">
                                Max Transactions Per Day
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Maximum number of transactions allowed per day."></i>
                            </label>
                            <input type="number" class="form-control" id="max_transactions_per_day"
                                name="max_transactions_per_day" min="1" required
                                value="<?= htmlspecialchars($settings['max_transactions_per_day']) ?>">
                            <div class="invalid-feedback">Please enter a valid maximum number.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="staff_update_start_time" class="form-label">
                                Staff Update Start Time
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Time when staff can begin modifying transactions."></i>
                            </label>
                            <input type="time" class="form-control" id="staff_update_start_time"
                                name="staff_update_start_time" required
                                value="<?= htmlspecialchars($settings['staff_update_start_time']) ?>">
                            <div class="invalid-feedback">Please select a valid start time.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="staff_update_cutoff_time" class="form-label">
                                Staff Update Cutoff Time
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Latest time staff can update transactions on a given day."></i>
                            </label>
                            <input type="time" class="form-control" id="staff_update_cutoff_time"
                                name="staff_update_cutoff_time" required
                                value="<?= htmlspecialchars($settings['staff_update_cutoff_time']) ?>">
                            <div class="invalid-feedback">Please select a cutoff time.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="no_show_timeout_minutes" class="form-label">
                                No-Show Timeout (mins)
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="After this duration, a user who has not arrived is considered a no-show."></i>
                            </label>
                            <input type="number" class="form-control" id="no_show_timeout_minutes"
                                name="no_show_timeout_minutes" min="1" required
                                value="<?= htmlspecialchars($settings['no_show_timeout_minutes']) ?>">
                            <div class="invalid-feedback">Please specify timeout duration.</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-secondary mb-3">Saturday Booking</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_saturday"
                                    name="enable_saturday" value="1" <?= $settings['enable_saturday'] ?>>
                                <label class="form-check-label" for="enable_saturday" data-bs-toggle="tooltip" title="Allow users to book appointments on Saturdays.">
                                    Enable Saturday Booking
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="saturday_start_time" class="form-label">
                                Saturday Start Time
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Start time for booking availability on Saturdays."></i>
                            </label>
                            <input type="time" class="form-control" id="saturday_start_time"
                                name="saturday_start_time" required
                                value="<?= htmlspecialchars($settings['saturday_start_time']) ?>">
                            <div class="invalid-feedback">Please set a valid start time.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="saturday_end_time" class="form-label">
                                Saturday End Time
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="End time for booking availability on Saturdays."></i>
                            </label>
                            <input type="time" class="form-control" id="saturday_end_time"
                                name="saturday_end_time" required
                                value="<?= htmlspecialchars($settings['saturday_end_time']) ?>">
                            <div class="invalid-feedback">Please set a valid end time.</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-secondary mb-3">SMS Settings</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sms_sender_name" class="form-label">
                                SMS Sender Name
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="This must match your approved sender name in Semaphore. Max 11 characters."></i>
                            </label>
                            <input type="text" class="form-control" id="sms_sender_name"
                                name="sms_sender_name" required maxlength="11"
                                value="<?= htmlspecialchars($settings['sms_sender_name']) ?>">
                            <div class="invalid-feedback">Please enter a valid sender name (max 11 characters).</div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="enable_sms_notifications"
                                    name="enable_sms_notifications" value="1" <?= $settings['enable_sms_notifications'] ?>>
                                <label class="form-check-label" for="enable_sms_notifications" data-bs-toggle="tooltip" title="Enable or disable SMS messages for system notifications.">
                                    Enable SMS Notifications
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Settings
                        </button>
                    </div>
                </form>

            </form>
        </div>
    </div>

    <?php include(__DIR__ . '/../views/templates/footer.php'); ?>
</div>

<?php include_once __DIR__ . '/../views/templates/admin_footer.php'; ?>

<!-- Bootstrap Tooltip + Client-side validation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Form validation
        const form = document.getElementById('settingsForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        const enableSaturdayCheckbox = document.getElementById('enable_saturday');
        const saturdayStartInput = document.getElementById('saturday_start_time');
        const saturdayEndInput = document.getElementById('saturday_end_time');

        function toggleSaturdayFields() {
            const enabled = enableSaturdayCheckbox.checked;
            saturdayStartInput.disabled = !enabled;
            saturdayEndInput.disabled = !enabled;
            saturdayStartInput.classList.toggle('text-muted', !enabled);
            saturdayEndInput.classList.toggle('text-muted', !enabled);
        }

        toggleSaturdayFields();
        enableSaturdayCheckbox.addEventListener('change', toggleSaturdayFields);
    });
</script>