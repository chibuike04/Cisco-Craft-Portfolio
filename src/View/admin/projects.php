<?php

declare(strict_types=1);

use App\Helpers\Csrf;

$projects = $projects ?? [];
$categories = $categories ?? [];

$escape = static fn ($value): string =>
    htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$formatDateTimeLocal = static function ($value): string {
    if (empty($value)) {
        return '';
    }

    try {
        return (new DateTimeImmutable((string) $value))->format('Y-m-d\TH:i');
    } catch (Throwable) {
        return '';
    }
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Projects | CodeCraft</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="<?= e(url('/assets/css/admin.css')) ?>">
</head>

<body>

    <div class="app">

        <!-- SIDEBAR -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <?php require __DIR__ . '/components/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <?php require __DIR__ . '/components/topbar.php'; ?>

            <!-- PROJECT MANAGEMENT -->
            <section class="page-section active" id="projects">

                <div class="welcome-row">
                    <div>
                        <h2>Project Management</h2>
                        <p>Create, edit and manage your portfolio projects.</p>
                    </div>

                    <button class="primary-btn" type="button" onclick="openProjectModal()">
                        <i class="fas fa-plus"></i> Add New Project
                    </button>
                </div>

                <!-- PROJECT FILTERS -->
                <div class="panel project-management-panel">

                    <div class="section-toolbar project-toolbar">

                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input
                                type="search"
                                id="projectSearch"
                                placeholder="Search projects...">
                        </div>

                        <div class="project-filters">

                            <select id="projectCategoryFilter" class="outline-btn">
                                <option value="">All Categories</option>

                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $escape($category['name'] ?? '') ?>">
                                        <?= $escape($category['name'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <select id="projectStatusFilter" class="outline-btn">
                                <option value="">All Statuses</option>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>

                        </div>
                    </div>

                    <div class="table-wrap">

                        <table class="project-admin-table">

                            <thead>
                                <tr>
                                    <th>PROJECT</th>
                                    <th>CATEGORY</th>
                                    <th>TECHNOLOGIES</th>
                                    <th>STATUS</th>
                                    <th>FEATURED</th>
                                    <th>ORDER</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody
                                id="projectsTableBody"
                                data-endpoint="<?= e(url('/admin/projects')) ?>">

                                <?php if (!empty($projects)): ?>

                                    <?php foreach ($projects as $project): ?>

                                        <?php
                                        $projectId = (int) ($project['id'] ?? 0);
                                        $title = (string) ($project['title'] ?? '');
                                        $slug = (string) ($project['slug'] ?? '');
                                        $category = (string) ($project['category_name'] ?? 'Uncategorized');
                                        $categoryId = (int) ($project['category_id'] ?? 0);
                                        $status = (string) ($project['status'] ?? 'draft');
                                        $isFeatured = (int) ($project['is_featured'] ?? 0) === 1;
                                        $sortOrder = (int) ($project['sort_order'] ?? 0);

                                        $thumbnailUrl = (string) ($project['thumbnail_url'] ?? '');

                                        $publishedAt = $formatDateTimeLocal(
                                            $project['published_at'] ?? null
                                        );
                                        ?>

                                        <tr
                                            data-id="<?= $projectId ?>"
                                            data-title="<?= $escape($title) ?>"
                                            data-slug="<?= $escape($slug) ?>"
                                            data-category="<?= $escape($category) ?>"
                                            data-category-id="<?= $categoryId ?>"
                                            data-status="<?= $escape($status) ?>"
                                            data-client-name="<?= $escape($project['client_name'] ?? '') ?>"
                                            data-role="<?= $escape($project['role'] ?? '') ?>"
                                            data-summary="<?= $escape($project['summary'] ?? '') ?>"
                                            data-description="<?= $escape($project['description'] ?? '') ?>"
                                            data-thumbnail-url="<?= $escape($thumbnailUrl) ?>"
                                            data-project-url="<?= $escape($project['project_url'] ?? '') ?>"
                                            data-repository-url="<?= $escape($project['repository_url'] ?? '') ?>"
                                            data-published-at="<?= $escape($publishedAt) ?>"
                                            data-sort-order="<?= $sortOrder ?>"
                                            data-is-featured="<?= $isFeatured ? '1' : '0' ?>"
                                            data-meta-title="<?= $escape($project['meta_title'] ?? '') ?>"
                                            data-meta-description="<?= $escape($project['meta_description'] ?? '') ?>">

                                            <td>
                                                <div class="project-table-title">

                                                    <strong>
                                                        <?= $escape($title) ?>
                                                    </strong>

                                                    <small>
                                                        <?= $escape($slug) ?>
                                                    </small>

                                                </div>
                                            </td>

                                            <td>
                                                <?= $escape($category) ?>
                                            </td>

                                            <td>
                                                <div class="project-tech-list">
                                                    <span>Not configured</span>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="status <?= $escape($status) ?>">
                                                    <?= $escape(ucfirst($status)) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php if ($isFeatured): ?>
                                                    <span class="project-featured yes">Yes</span>
                                                <?php else: ?>
                                                    <span class="project-featured no">No</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?= $sortOrder ?>
                                            </td>

                                            <td>
                                                <div class="project-action-buttons">

                                                    <button
                                                        type="button"
                                                        class="outline-btn"
                                                        title="Edit project"
                                                        onclick="editProject(this)">
                                                        <i class="fas fa-pen"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="outline-btn project-delete-btn"
                                                        title="Delete project"
                                                        onclick="deleteProject(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                        <div class="project-empty-state" id="projectEmptyState" hidden>
                            <i class="fas fa-folder-open"></i>
                            <h3>No projects found</h3>
                            <p>Try changing your search or filters.</p>
                        </div>

                    </div>
                </div>

            </section>

            <!-- ADD / EDIT PROJECT MODAL -->
            <div class="modal-overlay" id="projectModal">

                <div class="modal project-modal">

                    <div class="modal-header">

                        <div>
                            <h3 id="projectModalTitle">Add New Project</h3>

                            <p class="project-modal-subtitle">
                                Enter the details of your portfolio project.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="modal-close"
                            onclick="closeProjectModal()">
                            <i class="fas fa-times"></i>
                        </button>

                    </div>

                    <form
                        class="modal-form project-form"
                        id="projectForm"
                        method="POST"
                        action="<?= e(url('/admin/projects')) ?>"
                        data-endpoint="<?= e(url('/admin/projects')) ?>"
                        enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        <input
                            type="hidden"
                            name="_token"
                            value="<?= $escape(Csrf::token()) ?>">

                        <!-- PROJECT ID USED BY EDIT MODE -->
                        <input
                            type="hidden"
                            id="projectEditingRow"
                            name="id"
                            value="">

                        <!-- BASIC INFORMATION -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-info-circle"></i>
                                Basic Information
                            </h4>

                            <div class="project-form-grid">

                                <div class="project-field project-field-full">

                                    <label for="projectTitle">Project Title *</label>

                                    <input
                                        type="text"
                                        id="projectTitle"
                                        name="title"
                                        placeholder="e.g. Portfolio Website"
                                        maxlength="200"
                                        required>

                                </div>

                                <div class="project-field">

                                    <label for="projectSlug">Project Slug</label>

                                    <input
                                        type="text"
                                        id="projectSlug"
                                        placeholder="portfolio-website"
                                        readonly>

                                    <small>URL-friendly project name.</small>

                                </div>

                                <div class="project-field">

                                    <label for="projectCategory">Category *</label>

                                    <select
                                        id="projectCategory"
                                        name="category_id"
                                        required>

                                        <option value="">Select category</option>

                                        <?php foreach ($categories as $category): ?>

                                            <option value="<?= (int) ($category['id'] ?? 0) ?>">
                                                <?= $escape($category['name'] ?? '') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <div class="project-field">

                                    <label for="projectClient">Client Name</label>

                                    <input
                                        type="text"
                                        id="projectClient"
                                        name="client_name"
                                        maxlength="150"
                                        placeholder="Client or organization">

                                </div>

                                <div class="project-field">

                                    <label for="projectRole">Your Role</label>

                                    <input
                                        type="text"
                                        id="projectRole"
                                        name="role"
                                        maxlength="150"
                                        placeholder="e.g. Full-Stack Developer">

                                </div>

                                <div class="project-field project-field-full">

                                    <label for="projectSummary">Short Summary *</label>

                                    <textarea
                                        id="projectSummary"
                                        name="summary"
                                        rows="3"
                                        maxlength="500"
                                        placeholder="Write a short description for the project card..."
                                        required></textarea>

                                </div>

                                <div class="project-field project-field-full">

                                    <label for="projectDescription">Full Description</label>

                                    <textarea
                                        id="projectDescription"
                                        name="description"
                                        rows="5"
                                        placeholder="Describe the project, its features, purpose and implementation..."></textarea>

                                </div>

                            </div>
                        </div>

                        <!-- PROJECT IMAGE -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-image"></i>
                                Project Thumbnail
                            </h4>

                            <div class="project-image-upload">

                                <div class="project-image-preview" id="projectImagePreview">
                                    <i class="fas fa-image"></i>
                                    <span>Image preview</span>
                                </div>

                                <div class="project-image-controls">

                                    <label for="projectImage">Featured Image</label>

                                    <input
                                        type="file"
                                        id="projectImage"
                                        name="projectImage"
                                        accept="image/jpeg,image/png,image/webp,image/gif">

                                    <small>
                                        Choose the image that will appear on your public project card.
                                        Maximum file size: 5MB.
                                    </small>

                                </div>

                            </div>

                        </div>

                        <!-- TECHNOLOGIES -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-code"></i>
                                Technologies Used
                            </h4>

                            <div class="project-tech-input-row">

                                <input
                                    type="text"
                                    id="projectTechnologyInput"
                                    list="projectTechnologySuggestions"
                                    placeholder="Enter a technology, e.g. React">

                                <datalist id="projectTechnologySuggestions">
                                    <option value="PHP">
                                    <option value="JavaScript">
                                    <option value="TypeScript">
                                    <option value="React">
                                    <option value="Next.js">
                                    <option value="Node.js">
                                    <option value="MySQL">
                                    <option value="Tailwind CSS">
                                    <option value="Laravel">
                                    <option value="Python">
                                    <option value="WebSockets">
                                    <option value="Framer Motion">
                                </datalist>

                                <button
                                    type="button"
                                    class="outline-btn"
                                    onclick="addProjectTechnology()">
                                    <i class="fas fa-plus"></i> Add
                                </button>

                            </div>

                            <div
                                class="project-selected-technologies"
                                id="projectSelectedTechnologies">

                                <span class="project-tech-empty">
                                    No technologies added yet.
                                </span>

                            </div>

                            <small>
                                Add multiple technologies to describe the project's stack.
                            </small>

                        </div>

                        <!-- LINKS -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-link"></i>
                                Project Links
                            </h4>

                            <div class="project-form-grid">

                                <div class="project-field">

                                    <label for="projectLiveUrl">Live Demo URL</label>

                                    <input
                                        type="url"
                                        id="projectLiveUrl"
                                        name="project_url"
                                        maxlength="500"
                                        placeholder="https://example.com">

                                </div>

                                <div class="project-field">

                                    <label for="projectRepositoryUrl">GitHub / Repository URL</label>

                                    <input
                                        type="url"
                                        id="projectRepositoryUrl"
                                        name="repository_url"
                                        maxlength="500"
                                        placeholder="https://github.com/username/project">

                                </div>

                            </div>
                        </div>

                        <!-- PUBLISHING -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-sliders-h"></i>
                                Publishing Settings
                            </h4>

                            <div class="project-form-grid">

                                <div class="project-field">

                                    <label for="projectStatus">Project Status</label>

                                    <select id="projectStatus" name="status">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>

                                </div>

                                <div class="project-field">

                                    <label for="projectPublishedAt">Publication Date</label>

                                    <input
                                        type="datetime-local"
                                        id="projectPublishedAt"
                                        name="published_at">

                                </div>

                                <div class="project-field">

                                    <label for="projectSortOrder">Display Order</label>

                                    <input
                                        type="number"
                                        id="projectSortOrder"
                                        name="sort_order"
                                        min="0"
                                        value="1">

                                </div>

                                <div class="project-field project-featured-field">

                                    <label for="projectIsFeatured">Featured Project</label>

                                    <label class="project-switch">

                                        <input
                                            type="checkbox"
                                            id="projectIsFeatured"
                                            name="is_featured"
                                            value="1">

                                        <span class="project-switch-slider"></span>

                                    </label>

                                </div>

                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="project-form-section">

                            <h4>
                                <i class="fas fa-search"></i>
                                SEO Settings
                            </h4>

                            <div class="project-form-grid">

                                <div class="project-field project-field-full">

                                    <label for="projectMetaTitle">SEO Meta Title</label>

                                    <input
                                        type="text"
                                        id="projectMetaTitle"
                                        name="meta_title"
                                        maxlength="255"
                                        placeholder="Search engine title">

                                </div>

                                <div class="project-field project-field-full">

                                    <label for="projectMetaDescription">SEO Meta Description</label>

                                    <textarea
                                        id="projectMetaDescription"
                                        name="meta_description"
                                        rows="3"
                                        maxlength="500"
                                        placeholder="Short description for search engines..."></textarea>

                                </div>

                            </div>
                        </div>

                        <!-- MODAL ACTIONS -->
                        <div class="modal-actions project-modal-actions">

                            <button
                                type="button"
                                class="outline-btn"
                                onclick="closeProjectModal()">
                                Cancel
                            </button>

                            <button type="submit" class="primary-btn">
                                <i class="fas fa-save"></i>
                                <span id="projectSaveButtonText">Save Project</span>
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </main>

    </div>

    <script src="<?= e(url('/assets/js/dashboard.js')) ?>" defer></script>
    <script src="<?= e(url('/assets/js/project.js')) ?>" defer></script>

</body>
</html>
