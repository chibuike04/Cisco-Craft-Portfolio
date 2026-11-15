<?php
$currentPage = max(1, (int) ($pagination['current_page'] ?? 1));
$totalPages = max(1, (int) ($pagination['total_pages'] ?? 1));
$basePath = (string) ($pagination['base_path'] ?? 'blog');
?>
<?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Pagination">
        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <a class="page-btn <?= $page === $currentPage ? 'active' : '' ?>"
               href="<?= e(url($basePath . '?page=' . $page)) ?>"
               <?= $page === $currentPage ? 'aria-current="page"' : '' ?>>
                <?= $page ?>
            </a>
        <?php endfor; ?>
    </nav>
<?php endif; ?>
