<?php

declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$isDashboard = str_ends_with($currentPath, '/admin')
    || str_ends_with($currentPath, '/admin/dashboard');

$isBlog = str_contains($currentPath, '/admin/blogs');
$isMessages = str_contains($currentPath, '/admin/messages');
$isProjects = str_contains($currentPath, '/admin/projects');
$isSettings = str_contains($currentPath, '/admin/settings');
$isNewsletter = str_contains($currentPath, '/admin/newsletter');

?>

<aside class="sidebar" id="sidebar">

    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-code"></i>
        </div>
        <h2>Code<span>Craft.</span></h2>
    </div>

    <div class="menu-label">MAIN MENU</div>

    <nav class="sidebar-nav">

        <a href="<?= e(url('/admin/dashboard')) ?>"
            class="nav-item <?= $isDashboard ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <a href="<?= e(url('/admin/blogs')) ?>"
            class="nav-item <?= $isBlog ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Blog
        </a>

        <a href="<?= e(url('/admin/messages')) ?>"
            class="nav-item <?= $isMessages ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Messages / Contact
        </a>

        <a href="<?= e(url('/admin/projects')) ?>"
            class="nav-item <?= $isProjects ? 'active' : '' ?>">
            <i class="fas fa-folder-open"></i> Projects
        </a>

        <a href="<?= e(url('/admin/newsletter')) ?>"
            class="nav-item <?= $isNewsletter ? 'active' : '' ?>">
            <i class="fas fa-paper-plane"></i> Newsletter
        </a>

        <a href="<?= e(url('/admin/settings')) ?>"
            class="nav-item <?= $isSettings ? 'active' : '' ?>">
            <i class="fas fa-gear"></i> Settings
        </a>

    </nav>

    <div class="sidebar-bottom">

        <div class="menu-label">ACCOUNT</div>

        <button type="button" class="nav-item"
            onclick="openModal('logoutModal')">
            <i class="fas fa-right-from-bracket"></i> Logout
        </button>

        <div class="sidebar-profile">
            <div class="profile-avatar">AD</div>

            <div class="profile-info">
                <strong>Admin User</strong>
                <span>Administrator</span>
            </div>
        </div>

    </div>

    <!-- LOGOUT CONFIRMATION MODAL -->
    <div class="modal-overlay" id="logoutModal">

        <div class="modal">

            <div class="confirm-icon">
                <i class="fas fa-right-from-bracket"></i>
            </div>

            <h3 style="font-size:18px; margin-bottom:10px;">
                Confirm Logout
            </h3>

            <p class="confirm-text">
                Are you sure you want to log out of your admin dashboard?
            </p>

            <div class="modal-actions">

                <button type="button"
                    class="outline-btn"
                    onclick="closeModal('logoutModal')">
                    Cancel
                </button>

                <form action="<?= e(url('/admin/logout')) ?>" method="POST">

                    <input
                        type="hidden"
                        name="_token"
                        value="<?= e(\App\Helpers\Csrf::token()) ?>">

                    <button type="submit"
                        class="primary-btn"
                        style="background:var(--danger)">
                        Yes, Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</aside>
