<?php

declare(strict_types=1);

use App\Helpers\Csrf;

$escape = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
};

$messages = is_array($messages ?? null) ? $messages : [];
$totalMessages = (int) ($totalMessages ?? count($messages));
$unreadMessages = (int) ($unreadMessages ?? 0);
$readMessages = max(0, $totalMessages - $unreadMessages);
$messagesConfigured = $messagesConfigured ?? true;

$csrfToken = Csrf::token();

$formatDate = static function (?string $date): string {
    if (!$date) {
        return '-';
    }

    try {
        return (new DateTimeImmutable($date))->format('M j, Y');
    } catch (Throwable) {
        return '-';
    }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Messages | CodeCraft</title>

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

        <!-- SIDEBAR OVERLAY -->
        <div
            class="sidebar-overlay"
            id="sidebarOverlay"
            onclick="closeSidebar()"></div>

        <!-- SIDEBAR -->
        <?php require __DIR__ . '/components/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <?php require __DIR__ . '/components/topbar.php'; ?>

            <section class="page-section active" id="messages">

                <!-- PAGE HEADING -->
                <div class="welcome-row">
                    <div>
                        <h2>Messages &amp; Contact</h2>

                        <p>
                            View, manage and reply to messages submitted through your contact form.
                        </p>
                    </div>
                </div>

                <!-- MESSAGE STATISTICS -->
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-info">
                            <span>Total Messages</span>
                            <h3 id="totalMessages">
                                <?= $totalMessages ?>
                            </h3>
                        </div>

                        <div class="stat-icon blue">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-info">
                            <span>Unread Messages</span>
                            <h3 id="unreadMessages">
                                <?= $unreadMessages ?>
                            </h3>
                        </div>

                        <div class="stat-icon orange">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-info">
                            <span>Read Messages</span>
                            <h3 id="readMessages">
                                <?= $readMessages ?>
                            </h3>
                        </div>

                        <div class="stat-icon green">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>

                </div>

                <!-- MESSAGES TABLE -->
                <div class="panel">

                    <div class="section-toolbar msg-toolbar">

                        <div class="search-box">
                            <i class="fas fa-search"></i>

                            <input
                                type="search"
                                id="messageSearch"
                                placeholder="Search by name, email or subject...">
                        </div>

                        <select
                            class="outline-btn msg-filter"
                            id="messageFilter">
                            <option value="all">All Messages</option>
                            <option value="unread">Unread Messages</option>
                            <option value="read">Read Messages</option>
                        </select>

                    </div>

                    <div class="table-wrap">

                        <table class="messages-table">

                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>SUBJECT</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody
                                id="messagesTableBody"
                                data-endpoint="<?= e(url('/admin/messages')) ?>">

                                <?php if (!empty($messages)): ?>

                                    <?php foreach ($messages as $message): ?>

                                        <?php
                                        $messageId = (int) ($message['id'] ?? 0);

                                        $isRead = !empty($message['read_at'])
                                            || (($message['status'] ?? '') === 'read');

                                        $status = $isRead ? 'read' : 'unread';

                                        $senderName = $message['name'] ?? 'Unknown sender';
                                        $senderEmail = $message['email'] ?? '';
                                        $subject = $message['subject'] ?? '(No subject)';
                                        $messageContent = $message['message'] ?? '';

                                        $receivedAt = $message['created_at'] ?? null;
                                        ?>

                                        <tr
                                            class="message-row <?= $isRead ? '' : 'unread-row' ?>"
                                            data-id="<?= $messageId ?>"
                                            data-status="<?= $status ?>"
                                            data-name="<?= $escape($senderName) ?>"
                                            data-email="<?= $escape($senderEmail) ?>"
                                            data-subject="<?= $escape($subject) ?>"
                                            data-date="<?= $escape($formatDate($receivedAt)) ?>"
                                            data-created-at="<?= $escape($receivedAt) ?>"
                                            data-message="<?= $escape($messageContent) ?>"
                                            data-read-at="<?= $escape($message['read_at'] ?? '') ?>">

                                            <td>
                                                <strong class="msg-sender">
                                                    <?= $escape($senderName) ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= $escape($senderEmail) ?>
                                            </td>

                                            <td>
                                                <?= $escape($subject) ?>
                                            </td>

                                            <td>
                                                <?= $escape($formatDate($receivedAt)) ?>
                                            </td>

                                            <td>
                                                <span class="msg-status <?= $status ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="msg-actions">

                                                    <button
                                                        type="button"
                                                        class="msg-action-btn view"
                                                        title="View message"
                                                        onclick="viewMessage(this)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="msg-action-btn delete"
                                                        title="Delete message"
                                                        onclick="deleteMessage(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                        <div
                            class="msg-empty-state"
                            id="messagesEmptyState"
                            <?= !empty($messages) ? 'hidden' : '' ?>>

                            <i class="fas fa-inbox"></i>

                            <h3>
                                <?= $messagesConfigured
                                    ? 'No messages found'
                                    : 'Message management is not configured' ?>
                            </h3>

                            <p>
                                <?= $messagesConfigured
                                    ? 'Messages submitted through your contact form will appear here.'
                                    : 'Connect the Messages controller to your database to display contact messages.' ?>
                            </p>
                        </div>

                    </div>

                </div>

            </section>

        </main>

    </div>

    <!-- MESSAGE DETAILS MODAL -->
    <div class="msg-modal-overlay" id="messageModal">

        <div class="msg-modal">

            <div class="msg-modal-header">

                <div>
                    <h3>Message Details</h3>
                    <p>View the complete message from your visitor.</p>
                </div>

                <button
                    type="button"
                    class="msg-close-btn"
                    onclick="closeMessageModal()"
                    aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>

            </div>

            <div class="msg-modal-body">

                <div class="msg-detail-grid">

                    <div class="msg-detail-item">
                        <label>From</label>
                        <p id="detailName">-</p>
                    </div>

                    <div class="msg-detail-item">
                        <label>Email Address</label>
                        <p id="detailEmail">-</p>
                    </div>

                    <div class="msg-detail-item">
                        <label>Subject</label>
                        <p id="detailSubject">-</p>
                    </div>

                    <div class="msg-detail-item">
                        <label>Date Received</label>
                        <p id="detailDate">-</p>
                    </div>

                </div>

                <div class="msg-detail-item msg-message-content">
                    <label>Message</label>
                    <div id="detailMessage"></div>
                </div>

                <div class="msg-detail-item">
                    <label>Message Status</label>
                    <span class="msg-status unread" id="detailStatus">
                        Unread
                    </span>
                </div>

            </div>

            <div class="msg-modal-footer">

                <button
                    type="button"
                    class="outline-btn"
                    onclick="closeMessageModal()">
                    Close
                </button>

                <button
                    type="button"
                    class="outline-btn"
                    id="toggleReadButton"
                    onclick="toggleCurrentMessageStatus()">
                    <i class="fas fa-check"></i>
                    Mark as Read
                </button>

                <button
                    type="button"
                    class="primary-btn"
                    onclick="openReplyModal()">
                    <i class="fas fa-reply"></i>
                    Reply via Email
                </button>

            </div>

        </div>

    </div>

    <!-- REPLY EMAIL MODAL -->
    <div class="msg-modal-overlay" id="replyModal">

        <div class="msg-modal msg-reply-modal">

            <div class="msg-modal-header">

                <div>
                    <h3>Reply via Email</h3>
                    <p>Compose and send a reply to this message.</p>
                </div>

                <button
                    type="button"
                    class="msg-close-btn"
                    onclick="closeReplyModal()"
                    aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>

            </div>

            <form id="replyForm" class="msg-modal-body">

                <input
                    type="hidden"
                    name="_token"
                    value="<?= $escape($csrfToken) ?>">

                <div class="msg-detail-item">
                    <label for="replyTo">To</label>

                    <input
                        type="email"
                        id="replyTo"
                        class="msg-form-control"
                        readonly
                        required>
                </div>

                <div class="msg-detail-item">
                    <label for="replySubject">Subject</label>

                    <input
                        type="text"
                        id="replySubject"
                        class="msg-form-control"
                        maxlength="255"
                        required>
                </div>

                <div class="msg-detail-item">
                    <label for="replyMessage">Your Reply</label>

                    <textarea
                        id="replyMessage"
                        class="msg-form-control msg-reply-textarea"
                        placeholder="Write your reply here..."
                        required></textarea>
                </div>

                <div class="msg-reply-note">
                    <i class="fas fa-info-circle"></i>

                    Your reply will be sent directly to the visitor's email address.
                </div>

                <div class="msg-modal-footer">

                    <button
                        type="button"
                        class="outline-btn"
                        onclick="closeReplyModal()">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="primary-btn"
                        id="sendReplyButton">
                        <i class="fas fa-paper-plane"></i>
                        Send Reply
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- CSRF TOKEN FOR JAVASCRIPT REQUESTS -->
    <script>
        window.adminCsrfToken = <?= json_encode(
            $csrfToken,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        ) ?>;
    </script>

    <!-- MESSAGE MANAGEMENT JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/message.js')) ?>" defer></script>

</body>

</html>
