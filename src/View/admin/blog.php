<?php

declare(strict_types=1);

use App\Helpers\Csrf;

$escape = static fn($value): string =>
    htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$blogs = $blogs ?? [];
$categories = $categories ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blog | CodeCraft</title>

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


            <!-- =========================================
                 BLOG MANAGEMENT
            ========================================== -->

            <section class="page-section active" id="blog">

                <div class="welcome-row">

                    <div>
                        <h2>Blog Management</h2>
                        <p>Create, edit and manage your blog articles.</p>
                    </div>

                    <button
                        type="button"
                        class="primary-btn"
                        onclick="openBlogModal()">
                        <i class="fas fa-plus"></i>
                        Add New Post
                    </button>

                </div>


                <!-- =====================================
                     BLOG TABLE
                ====================================== -->

                <div class="panel">

                    <div class="section-toolbar">

                        <!-- SEARCH -->
                        <div class="search-box">

                            <i class="fas fa-search"></i>

                            <input
                                type="search"
                                id="blogSearch"
                                placeholder="Search blog posts...">

                        </div>


                        <!-- FILTERS -->
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">

                            <!-- CATEGORY FILTER -->
                            <select
                                id="categoryFilter"
                                class="outline-btn">

                                <option value="all">All Categories</option>

                                <?php foreach ($categories as $category): ?>

                                    <option value="<?= $escape($category['name']) ?>">
                                        <?= $escape($category['name']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>


                            <!-- STATUS FILTER -->
                            <select
                                id="statusFilter"
                                class="outline-btn">

                                <option value="all">All Statuses</option>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>

                            </select>

                        </div>

                    </div>


                    <!-- =================================
                         BLOG POSTS TABLE
                    ================================== -->

                    <div class="table-wrap">

                        <table id="blogTable">

                            <thead>
                                <tr>
                                    <th>TITLE</th>
                                    <th>CATEGORY</th>
                                    <th>FEATURED</th>
                                    <th>DATE</th>
                                    <th>VIEWS</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>


                            <tbody>

                                <?php if (!empty($blogs)): ?>

                                    <?php foreach ($blogs as $blog): ?>

                                        <?php

                                        $title = (string) ($blog['title'] ?? '');

                                        $categoryName = (string) (
                                            $blog['category_name'] ?? 'Uncategorized'
                                        );

                                        $status = (string) (
                                            $blog['status'] ?? 'draft'
                                        );

                                        $isFeatured = (int) (
                                            $blog['is_featured'] ?? 0
                                        );

                                        $views = (int) (
                                            $blog['view_count'] ?? 0
                                        );

                                        $publishedAt = $blog['published_at'] ?? null;
                                        $createdAt = $blog['created_at'] ?? null;

                                        $dateValue = $publishedAt ?: $createdAt;

                                        $formattedDate = $dateValue
                                            ? date('M j, Y', strtotime($dateValue))
                                            : 'Not published';

                                        $dateForInput = $dateValue
                                            ? date('Y-m-d\TH:i', strtotime($dateValue))
                                            : '';

                                        $blogId = (int) ($blog['id'] ?? 0);

                                        ?>

                                        <tr
                                            data-id="<?= $blogId ?>"
                                            data-title="<?= $escape($title) ?>"
                                            data-slug="<?= $escape($blog['slug'] ?? '') ?>"
                                            data-category="<?= $escape($categoryName) ?>"
                                            data-category-id="<?= (int) ($blog['category_id'] ?? 0) ?>"
                                            data-status="<?= $escape($status) ?>"
                                            data-featured="<?= $isFeatured ?>"
                                            data-published-at="<?= $escape($dateForInput) ?>"
                                            data-date="<?= $escape($dateForInput) ?>"
                                            data-views="<?= $views ?>"
                                            data-excerpt="<?= $escape($blog['excerpt'] ?? '') ?>"
                                            data-content="<?= $escape($blog['content'] ?? '') ?>"
                                            data-image-alt=""
                                            data-meta-title="<?= $escape($blog['meta_title'] ?? '') ?>"
                                            data-meta-description="<?= $escape($blog['meta_description'] ?? '') ?>"
                                            data-tags="">

                                            <!-- TITLE -->
                                            <td>
                                                <strong>
                                                    <?= $escape($title) ?>
                                                </strong>
                                            </td>


                                            <!-- CATEGORY -->
                                            <td>
                                                <?= $escape($categoryName) ?>
                                            </td>


                                            <!-- FEATURED -->
                                            <td>

                                                <?php if ($isFeatured): ?>

                                                    <span class="status published">
                                                        Yes
                                                    </span>

                                                <?php else: ?>

                                                    <span class="status pending">
                                                        No
                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <!-- DATE -->
                                            <td>
                                                <?= $escape($formattedDate) ?>
                                            </td>


                                            <!-- VIEWS -->
                                            <td>
                                                <?= number_format($views) ?>
                                            </td>


                                            <!-- STATUS -->
                                            <td>

                                                <span class="status <?= $status === 'published' ? 'published' : 'pending' ?>">
                                                    <?= ucfirst($escape($status)) ?>
                                                </span>

                                            </td>


                                            <!-- ACTIONS -->
                                            <td>

                                                <div style="display:flex; gap:8px;">

                                                    <button
                                                        type="button"
                                                        class="outline-btn"
                                                        onclick="editBlog(this)"
                                                        title="Edit post">
                                                        <i class="fas fa-pen"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="outline-btn"
                                                        onclick="deleteBlog(this)"
                                                        title="Delete post">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td
                                            colspan="7"
                                            style="text-align:center; padding:25px;">
                                            No blog posts found.
                                            Click "Add New Post" to create your first article.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>


                        <!-- NO SEARCH RESULTS -->
                        <p
                            id="noBlogResults"
                            style="display:none; text-align:center; padding:25px;">
                            No blog posts match your search.
                        </p>

                    </div>

                </div>

            </section>


            <!-- =========================================
                 ADD / EDIT BLOG MODAL
            ========================================== -->

            <div class="modal-overlay" id="blogModal">

                <div class="modal">

                    <!-- MODAL HEADER -->
                    <div class="modal-header">

                        <h3 id="blogModalTitle">
                            Create New Blog Post
                        </h3>

                        <button
                            type="button"
                            class="modal-close"
                            onclick="closeBlogModal()">
                            <i class="fas fa-times"></i>
                        </button>

                    </div>


                    <!-- BLOG FORM -->
                    <form
                        class="modal-form"
                        id="blogForm"
                        data-endpoint="<?= e(url('/admin/blogs')) ?>"
                        method="POST"
                        enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        <?= Csrf::field() ?>


                        <!-- EDITING BLOG ID -->
                        <input
                            type="hidden"
                            id="blogEditMode"
                            value="">


                        <!-- POST TITLE -->
                        <label for="blogTitle">
                            Post Title
                        </label>

                        <input
                            type="text"
                            id="blogTitle"
                            placeholder="Enter blog title"
                            maxlength="255"
                            required>


                        <!-- URL SLUG -->
                        <label for="blogSlug">
                            URL Slug
                        </label>

                        <input
                            type="text"
                            id="blogSlug"
                            placeholder="your-blog-post-url"
                            maxlength="255"
                            required>

                        <small>
                            Used in the article URL. Automatically generated from the title.
                        </small>


                        <!-- CATEGORY -->
                        <label for="blogCategory">
                            Category
                        </label>

                        <select id="blogCategory" required>

                            <option value="">
                                Select category
                            </option>

                            <?php foreach ($categories as $category): ?>

                                <option value="<?= (int) $category['id'] ?>">
                                    <?= $escape($category['name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>


                        <!-- SHORT DESCRIPTION -->
                        <label for="blogExcerpt">
                            Short Description
                        </label>

                        <textarea
                            id="blogExcerpt"
                            rows="3"
                            maxlength="1000"
                            placeholder="Write a short description..."></textarea>

                        <small>
                            <span id="excerptCount">0</span>/1000 characters
                        </small>


                        <!-- FULL CONTENT -->
                        <label for="blogContent">
                            Full Article Content
                        </label>

                        <textarea
                            id="blogContent"
                            rows="10"
                            placeholder="Write your complete blog article here..."
                            required></textarea>

                        <small>
                            This is the main content displayed on the individual blog article page.
                        </small>


                        <!-- TAGS -->
                        <label for="blogTags">
                            Blog Tags
                        </label>

                        <select id="blogTags" multiple size="6">

                            <option value="React">React</option>
                            <option value="React 19">React 19</option>
                            <option value="JavaScript">JavaScript</option>
                            <option value="Frontend">Frontend</option>
                            <option value="Next.js">Next.js</option>
                            <option value="UI Design">UI Design</option>
                            <option value="UX Design">UX Design</option>
                            <option value="Web Design">Web Design</option>
                            <option value="Career">Career</option>
                            <option value="Personal Branding">Personal Branding</option>
                            <option value="Productivity">Productivity</option>
                            <option value="Blockchain">Blockchain</option>

                        </select>

                        <small>
                            Hold Ctrl (Windows) or Command (Mac) to select multiple tags.
                        </small>


                        <!-- FEATURED IMAGE -->
                        <label for="blogImage">
                            Featured Image
                        </label>

                        <input
                            type="file"
                            id="blogImage"
                            name="featured_image"
                            accept="image/jpeg,image/png,image/webp,image/gif">

                        <div
                            id="blogImagePreview"
                            style="display:none; margin-top:12px;">

                            <img
                                id="blogPreviewImg"
                                src=""
                                alt="Featured image preview"
                                style="width:100%; max-height:220px; object-fit:cover; border-radius:10px;">

                        </div>


                        <!-- IMAGE ALT TEXT -->
                        <label for="blogImageAlt">
                            Image Alt Text
                        </label>

                        <input
                            type="text"
                            id="blogImageAlt"
                            placeholder="Describe the featured image"
                            maxlength="255">


                        <!-- STATUS -->
                        <label for="blogStatus">
                            Publication Status
                        </label>

                        <select id="blogStatus" required>

                            <option value="draft">
                                Draft
                            </option>

                            <option value="published">
                                Published
                            </option>

                        </select>


                        <!-- FEATURED POST -->
                        <label style="display:flex; align-items:center; gap:10px;">

                            <input
                                type="checkbox"
                                id="blogFeatured"
                                value="1"
                                style="width:auto;">

                            Feature this post

                        </label>


                        <!-- PUBLICATION DATE -->
                        <label for="blogPublishedAt">
                            Publication Date
                        </label>

                        <input
                            type="datetime-local"
                            id="blogPublishedAt">


                        <!-- SEO TITLE -->
                        <label for="blogMetaTitle">
                            SEO Title
                        </label>

                        <input
                            type="text"
                            id="blogMetaTitle"
                            maxlength="255"
                            placeholder="Enter SEO title">


                        <!-- SEO DESCRIPTION -->
                        <label for="blogMetaDescription">
                            SEO Description
                        </label>

                        <textarea
                            id="blogMetaDescription"
                            rows="3"
                            maxlength="500"
                            placeholder="Write a description for search engines..."></textarea>

                        <small>
                            <span id="metaDescriptionCount">0</span>/500 characters
                        </small>


                        <!-- FORM ACTIONS -->
                        <div class="modal-actions">

                            <button
                                type="button"
                                class="outline-btn"
                                onclick="closeBlogModal()">
                                Cancel
                            </button>

                            <button
                                class="primary-btn"
                                type="submit">

                                <i class="fas fa-save"></i>

                                <span id="blogSubmitText">
                                    Save Post
                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </main>

    </div>


    <!-- SHARED ADMIN JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/dashboard.js')) ?>" defer></script>

    <!-- BLOG MANAGEMENT JAVASCRIPT -->
    <script src="<?= e(url('/assets/js/blog.js')) ?>" defer></script>

</body>

</html>
