<?php

declare(strict_types=1);

$escape = static fn($value): string =>
htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$subscribers = (isset($subscribers) && is_array($subscribers))
    ? $subscribers
    : [];

$totalSubscribers = (int) ($totalSubscribers ?? 0);
$activeSubscribers = (int) ($activeSubscribers ?? 0);
$unsubscribedSubscribers = (int) ($unsubscribedSubscribers ?? 0);
$csrfToken = (string) ($csrfToken ?? '');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Newsletter | CodeCraft</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="<?= e(url('/assets/css/admin.css')) ?>">
</head>

<body>

    <div class="app">

        <!-- SIDEBAR -->
        <div
            class="sidebar-overlay"
            id="sidebarOverlay"
            onclick="closeSidebar()"></div>

        <?php require __DIR__ . '/components/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <?php require __DIR__ . '/components/topbar.php'; ?>

            <!-- NEWSLETTER MANAGEMENT -->
            <section class="page-section active" id="newsletter">

                <div class="welcome-row">
                    <div>
                        <h2>Newsletter Management</h2>
                        <p>Manage subscribers and send updates to your audience.</p>
                    </div>
                </div>

                <!-- NEWSLETTER STATISTICS -->
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon purple">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <h3 id="totalSubscribers"><?= $totalSubscribers ?></h3>
                        <p>Total Subscribers</p>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon green">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <h3 id="activeSubscribers"><?= $activeSubscribers ?></h3>
                        <p>Active Subscribers</p>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <div class="stat-icon orange">
                                <i class="fas fa-user-minus"></i>
                            </div>
                        </div>
                        <h3 id="unsubscribedSubscribers"><?= $unsubscribedSubscribers ?></h3>
                        <p>Unsubscribed</p>
                    </div>

                </div>

                <!-- SUBSCRIBER MANAGEMENT -->
                <div class="panel">

                    <div class="panel-header">
                        <h3>Subscriber Management</h3>
                        <span>Manage your newsletter audience</span>
                    </div>

                    <div class="section-toolbar">

                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input
                                type="search"
                                id="subscriberSearch"
                                placeholder="Search subscribers...">
                        </div>

                        <select id="subscriberFilter" class="outline-btn">
                            <option value="all">All Subscribers</option>
                            <option value="active">Active</option>
                            <option value="unsubscribed">Unsubscribed</option>
                        </select>

                    </div>


                    <div class="table-wrap">

                        <table>

                            <thead>
                                <tr>
                                    <th>
                                        <input
                                            type="checkbox"
                                            id="selectAllSubscribers"
                                            aria-label="Select all active subscribers"
                                            title="Select all active subscribers">
                                    </th>
                                    <th>EMAIL ADDRESS</th>
                                    <th>DATE SUBSCRIBED</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>

                            <tbody
                                id="subscriberTableBody"
                                data-endpoint="<?= e(url('/admin/newsletter')) ?>">
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:30px;">
                                        Loading subscribers...
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>


                </div>

                <!-- NEWSLETTER COMPOSER -->
                <div class="panel" style="margin-top:25px;">

                    <div class="panel-header">
                        <h3>Compose Newsletter</h3>
                        <span>Create and send an email campaign</span>
                    </div>

                    <form
                        id="newsletterForm"
                        class="modal-form"
                        data-endpoint="<?= e(url('/admin/newsletter')) ?>">

                        <input
                            type="hidden"
                            name="_token"
                            value="<?= $escape($csrfToken) ?>">

                        <label>Recipients</label>

                        <select name="recipients" id="newsletterRecipients" required>
                            <option value="all">All Active Subscribers</option>
                            <option value="selected">Selected Subscribers</option>
                        </select>

                        <label>Email Subject</label>

                        <input
                            type="text"
                            name="subject"
                            placeholder="Enter newsletter subject"
                            required>

                        <label>Newsletter Message</label>

                        <textarea
                            name="message"
                            rows="8"
                            placeholder="Write your newsletter message..."
                            required></textarea>

                        <div class="modal-actions">

                            <button type="reset" class="outline-btn">
                                Clear
                            </button>

                            <button type="submit" class="primary-btn">
                                <i class="fas fa-paper-plane"></i>
                                Send Newsletter
                            </button>

                        </div>

                    </form>

                    <p style="font-size:12px; color:var(--text-muted); margin-top:15px;">
                        Emails will be sent to active subscribers only. Sending requires
                        a configured email service and backend integration.
                    </p>

                </div>

            </section>

        </main>

    </div>

    <!-- PASS PHP DATA TO JAVASCRIPT -->
    <script>
        window.newsletterSubscribers = <?= json_encode(
                                            $subscribers,
                                            JSON_HEX_TAG
                                                | JSON_HEX_APOS
                                                | JSON_HEX_AMP
                                                | JSON_HEX_QUOT
                                                | JSON_INVALID_UTF8_SUBSTITUTE
                                        ) ?: '[]' ?>;

        window.adminCsrfToken = <?= json_encode(
                                    $csrfToken,
                                    JSON_HEX_TAG
                                        | JSON_HEX_APOS
                                        | JSON_HEX_AMP
                                        | JSON_HEX_QUOT
                                ) ?: '""' ?>;
    </script>

    <!-- NEWSLETTER JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/newsletter.js')) ?>" defer></script>

</body>

</html>