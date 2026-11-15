<?php

declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$pageTitle = 'Dashboard';

if (str_contains($currentPath, '/admin/blogs')) {
    $pageTitle = 'Blog Management';
} elseif (str_contains($currentPath, '/admin/messages')) {
    $pageTitle = 'Messages & Contact';
} elseif (str_contains($currentPath, '/admin/projects')) {
    $pageTitle = 'Project Management';
} elseif (str_contains($currentPath, '/admin/newsletter')) {
    $pageTitle = 'Newsletter Management';
} elseif (str_contains($currentPath, '/admin/settings')) {
    $pageTitle = 'Settings';
}

?>

<header class="topbar">

    <div class="topbar-left">

        <button
            type="button"
            class="menu-toggle"
            onclick="toggleSidebar()"
            aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-heading">

            <h1 id="pageTitle">
                <?= e($pageTitle) ?>
            </h1>

            <p>Welcome back to your admin panel</p>

        </div>

    </div>

    <div class="topbar-right">

        <button type="button" class="icon-btn" title="Notifications">
            <i class="far fa-bell"></i>
            <span class="notification-dot"></span>
        </button>

        <div class="topbar-avatar">AD</div>

    </div>

</header>
