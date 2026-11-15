<?php

declare(strict_types=1);

use App\Helpers\Flash;

$admin = $admin ?? [];
$csrfToken = $csrfToken ?? '';

$successMessage = Flash::get('success');
$errorMessage = Flash::get('error');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Settings | CodeCraft</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="<?= e(url('/assets/css/admin.css')) ?>">
</head>

<body>

    <div class="app">

        <!-- SIDEBAR OVERLAY -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- SIDEBAR -->
        <?php require __DIR__ . '/components/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <?php require __DIR__ . '/components/topbar.php'; ?>

            <!-- SETTINGS -->
            <section class="page-section active" id="settings">

                <div class="welcome-row">
                    <div>
                        <h2>Settings</h2>
                        <p>Manage your admin profile and website preferences.</p>
                    </div>
                </div>

                <!-- FLASH MESSAGES -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage): ?>
                    <div class="alert alert-error" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="settings-grid">

                    <!-- PROFILE INFORMATION -->
                    <div class="settings-group">

                        <h3>
                            <i class="fas fa-user-circle"></i>
                            Profile Information
                        </h3>

                        <form
                            id="profileSettingsForm"
                            action="<?= e(url('/admin/settings')) ?>"
                            method="POST"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
                            >

                            <input type="hidden" name="action" value="update_profile">

                            <div class="settings-field">
                                <label for="profileName">Full Name</label>

                                <input
                                    type="text"
                                    id="profileName"
                                    name="name"
                                    value="<?= htmlspecialchars((string) ($admin['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Enter your full name"
                                    autocomplete="name"
                                    required
                                >
                            </div>

                            <div class="settings-field">
                                <label for="profileEmail">Email Address</label>

                                <input
                                    type="email"
                                    id="profileEmail"
                                    name="email"
                                    value="<?= htmlspecialchars((string) ($admin['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Enter your email address"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                            <button type="submit" class="primary-btn">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>

                        </form>

                    </div>

                    <!-- SECURITY SETTINGS -->
                    <div class="settings-group">

                        <h3>
                            <i class="fas fa-lock"></i>
                            Security Settings
                        </h3>

                        <form
                            id="passwordSettingsForm"
                            action="<?= e(url('/admin/settings')) ?>"
                            method="POST"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
                            >

                            <input type="hidden" name="action" value="update_password">

                            <div class="settings-field">
                                <label for="currentPassword">Current Password</label>

                                <input
                                    type="password"
                                    id="currentPassword"
                                    name="current_password"
                                    placeholder="Enter current password"
                                    autocomplete="current-password"
                                    required
                                >
                            </div>

                            <div class="settings-field">
                                <label for="newPassword">New Password</label>

                                <input
                                    type="password"
                                    id="newPassword"
                                    name="new_password"
                                    placeholder="Enter new password"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <div class="settings-field">
                                <label for="confirmPassword">Confirm Password</label>

                                <input
                                    type="password"
                                    id="confirmPassword"
                                    name="confirm_password"
                                    placeholder="Confirm new password"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <button type="submit" class="primary-btn">
                                <i class="fas fa-key"></i>
                                Update Password
                            </button>

                        </form>

                    </div>

                </div>

            </section>

        </main>

    </div>

    <!-- SHARED ADMIN JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/dashboard.js')) ?>" defer></script>

    <!-- SETTINGS JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/settings.js')) ?>" defer></script>

</body>

</html>
