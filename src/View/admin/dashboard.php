<?php

declare(strict_types=1);

/**
 * Dashboard data supplied by DashboardController.
 */

$statistics = $statistics ?? [];

$recentBlogs = $recentBlogs ?? [];
$recentProjects = $recentProjects ?? [];
$recentMessages = $recentMessages ?? [];

$totalBlogs = (int) ($statistics['total_blogs'] ?? 0);
$totalMessages = (int) ($statistics['total_messages'] ?? 0);
$activeProjects = (int) ($statistics['active_projects'] ?? 0);

$portfolioViews = $statistics['portfolio_views'] ?? null;

// Format dates safely.
$formatDashboardDate = static function (?string $date): string {
    if (!$date) {
        return 'Date unavailable';
    }

    $timestamp = strtotime($date);

    return $timestamp
        ? date('M j, Y', $timestamp)
        : 'Date unavailable';
};

// Calculate readable time differences.
$timeAgo = static function (?string $date): string {
    if (!$date) {
        return 'Date unavailable';
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return 'Date unavailable';
    }

    $difference = max(0, time() - $timestamp);

    if ($difference < 60) {
        return 'Just now';
    }

    if ($difference < 3600) {
        return floor($difference / 60) . ' minutes ago';
    }

    if ($difference < 86400) {
        return floor($difference / 3600) . ' hours ago';
    }

    if ($difference < 604800) {
        return floor($difference / 86400) . ' days ago';
    }

    return date('M j, Y', $timestamp);
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($pageTitle ?? 'Admin Dashboard', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
        href="<?= e(url('/assets/css/admin.css')) ?>">
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

            <!-- DASHBOARD -->
            <section class="page-section active" id="dashboard">

                <!-- Welcome section -->
                <div class="welcome-row">

                    <div>
                        <h2>Good morning, Admin 👋</h2>
                        <p>Here's what's happening with your portfolio today.</p>
                    </div>

                    <button class="primary-btn" type="button" onclick="showPage('blog')">
                        <i class="fas fa-plus"></i>
                        Manage Content
                    </button>

                </div>

                <!-- Dashboard statistics -->
                <div class="stats-grid">

                    <!-- Blog Posts -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon purple">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>

                        <h3><?= number_format($totalBlogs) ?></h3>
                        <p>Total Blog Posts</p>

                    </div>

                    <!-- Messages -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon blue">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>

                        <h3><?= number_format($totalMessages) ?></h3>
                        <p>Total Messages</p>

                    </div>

                    <!-- Projects -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon green">
                                <i class="fas fa-folder-open"></i>
                            </div>
                        </div>

                        <h3><?= number_format($activeProjects) ?></h3>
                        <p>Active Projects</p>

                    </div>

                    <!-- Portfolio Views -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon orange">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>

                        <h3>
                            <?= $portfolioViews !== null
                                ? number_format((int) $portfolioViews)
                                : '—' ?>
                        </h3>

                        <p>Portfolio Views</p>

                    </div>

                </div>

                <!-- Dashboard content -->
                <div class="dashboard-grid">

                    <!-- Recent Blog Posts -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>Recent Blog Posts</h3>

                            <button class="outline-btn" type="button" onclick="showPage('blog')">
                                View All
                            </button>

                        </div>

                        <div class="table-wrap">

                            <table>

                                <thead>
                                    <tr>
                                        <th>ARTICLE</th>
                                        <th>DATE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if (!empty($recentBlogs)): ?>

                                        <?php foreach ($recentBlogs as $blog): ?>

                                            <tr>

                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $blog['title'] ?? 'Untitled article',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </strong>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars(
                                                        $formatDashboardDate(
                                                            $blog['published_at'] ?? $blog['created_at'] ?? null
                                                        ),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <span class="status published">
                                                        Published
                                                    </span>
                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="3">
                                                No published blog posts found.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <!-- Recent Activity -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>Recent Activity</h3>

                            <span>Latest updates</span>

                        </div>

                        <div class="activity-list">

                            <?php

                            // Combine recent blogs, messages and projects.
                            $activities = [];

                            foreach ($recentBlogs as $blog) {
                                $activities[] = [
                                    'type' => 'blog',
                                    'title' => 'Blog post published',
                                    'description' => $blog['title'] ?? 'Untitled article',
                                    'date' => $blog['published_at'] ?? $blog['created_at'] ?? null,
                                ];
                            }

                            foreach ($recentMessages as $message) {
                                $activities[] = [
                                    'type' => 'message',
                                    'title' => 'New contact message',
                                    'description' => $message['name'] ?? 'Unknown sender',
                                    'date' => $message['created_at'] ?? null,
                                ];
                            }

                            foreach ($recentProjects as $project) {
                                $activities[] = [
                                    'type' => 'project',
                                    'title' => 'Project published',
                                    'description' => $project['title'] ?? 'Untitled project',
                                    'date' => $project['published_at'] ?? $project['created_at'] ?? null,
                                ];
                            }

                            // Sort activities by date, newest first.
                            usort(
                                $activities,
                                static function (array $a, array $b): int {
                                    return strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? '');
                                }
                            );

                            $activities = array_slice($activities, 0, 5);

                            ?>

                            <?php if (!empty($activities)): ?>

                                <?php foreach ($activities as $activity): ?>

                                    <?php

                                    $iconClass = match ($activity['type']) {
                                        'blog' => 'purple',
                                        'message' => 'blue',
                                        'project' => 'green',
                                        default => 'purple',
                                    };

                                    $icon = match ($activity['type']) {
                                        'blog' => 'fa-newspaper',
                                        'message' => 'fa-envelope',
                                        'project' => 'fa-folder',
                                        default => 'fa-bell',
                                    };

                                    ?>

                                    <div class="activity-item">

                                        <div class="activity-icon <?= $iconClass ?>">
                                            <i class="fas <?= $icon ?>"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $activity['title'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </strong>

                                            <p>
                                                <?= htmlspecialchars(
                                                    $activity['description'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </p>

                                            <small>
                                                <?= htmlspecialchars(
                                                    $timeAgo($activity['date']),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </small>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <p>No recent activity available.</p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </section>

        </main>

    </div>

    <script src="<?= e(url('/assets/js/dashboard.js')) ?>"></script>

</body>

</html>
