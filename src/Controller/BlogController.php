<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogTagRelation;
use App\Models\Media;
use App\View\View;

final class BlogController extends ControllerSupport
{
    /**
     * Display all published blog articles.
     */
    public function index(Request $request): void
    {
        // Retrieve published blog articles.
        $allBlogs = (new Blog())->all(
            500,
            0,
            'published_at',
            'DESC'
        );

        $blogs = array_values(array_filter(
            $allBlogs,
            static fn(array $blog): bool => ($blog['status'] ?? '') === 'published'
        ));

        // Retrieve all blog categories.
        $categories = (new BlogCategory())->all(
            100,
            0,
            'name',
            'ASC'
        );

        // Build a category lookup.
        $categoryLookup = [];

        foreach ($categories as $category) {
            $categoryLookup[(int) $category['id']] = $category;
        }

        // Attach category information, reading time, and featured images.
        foreach ($blogs as &$blog) {
            $category = $categoryLookup[(int) ($blog['category_id'] ?? 0)] ?? [];

            $blog['category_name'] = $category['name'] ?? 'Uncategorized';
            $blog['category_slug'] = $category['slug'] ?? '';

            $blog['reading_minutes'] = $this->readingTime(
                $blog['content'] ?? ''
            );

            $blog['featured_image_url'] = $this->getFeaturedImageUrl(
                $blog['featured_media_id'] ?? null
            );
        }

        unset($blog);

        // Pagination.
        $perPage = 6;

        $currentPage = max(
            1,
            (int) $request->input('page', 1)
        );

        $totalBlogs = count($blogs);

        $pagination = $this->pagination(
            $totalBlogs,
            $currentPage,
            $perPage
        );

        $blogs = array_slice(
            $blogs,
            $pagination['offset'],
            $perPage
        );

        $pagination['base_path'] = 'blog';

        // Render the blog listing page.
        View::render('public/blog', [
            'blogs' => $blogs,
            'categories' => $categories,
            'pagination' => $pagination,
            'pageTitle' => 'Blog',
        ]);
    }

    /**
     * Display a single blog article.
     */
    public function show(Request $request, string $slug): void
    {
        // Find the requested article by its slug.
        $blog = (new Blog())->firstWhere('slug', $slug);

        // Return a 404 response if the article does not exist
        // or has not been published.
        if (
            $blog === null ||
            ($blog['status'] ?? '') !== 'published'
        ) {
            http_response_code(404);

            echo '404 - Blog Article Not Found';

            return;
        }

        // Retrieve the article category.
        $category = (new BlogCategory())->find(
            (int) ($blog['category_id'] ?? 0)
        );

        $blog['category_name'] = $category['name'] ?? '';
        $blog['category_slug'] = $category['slug'] ?? '';

        // Calculate reading time.
        $blog['reading_minutes'] = $this->readingTime(
            $blog['content'] ?? ''
        );

        // Retrieve the featured image.
        $blog['featured_image_url'] = $this->getFeaturedImageUrl(
            $blog['featured_media_id'] ?? null
        );

        // Retrieve tags connected to this article.
        $relations = (new BlogTagRelation())->where(
            'blog_id',
            (int) $blog['id'],
            100
        );

        $allTags = (new BlogTag())->all(500, 0, 'name', 'ASC');

        $tagLookup = [];

        foreach ($allTags as $tag) {
            $tagLookup[(int) $tag['id']] = $tag;
        }

        $tags = [];

        foreach ($relations as $relation) {
            $tagId = (int) ($relation['tag_id'] ?? 0);

            if (isset($tagLookup[$tagId])) {
                $tags[] = $tagLookup[$tagId];
            }
        }

        // Retrieve related published articles from the same category.
        $relatedBlogs = (new Blog())->where(
            'category_id',
            (int) ($blog['category_id'] ?? 0),
            100
        );

        $relatedBlogs = array_values(array_filter(
            $relatedBlogs,
            static fn(array $related): bool => ($related['status'] ?? '') === 'published' &&
                (int) $related['id'] !== (int) $blog['id']
        ));

        // Limit related articles.
        $relatedBlogs = array_slice($relatedBlogs, 0, 3);

        // Attach category, reading information, and featured images.
        foreach ($relatedBlogs as &$related) {
            $related['category_name'] =
                $category['name'] ?? 'Uncategorized';

            $related['category_slug'] =
                $category['slug'] ?? '';

            $related['reading_minutes'] = $this->readingTime(
                $related['content'] ?? ''
            );

            $related['featured_image_url'] = $this->getFeaturedImageUrl(
                $related['featured_media_id'] ?? null
            );
        }

        unset($related);

        // Author data is unavailable until the users table is configured.
        $author = [];

        // The article view supports a table of contents.
        // This will remain empty until heading extraction is implemented.
        $tableOfContents = [];

        // Render the individual article page.
        View::render('public/blog-view', [
            'blog' => $blog,
            'tags' => $tags,
            'relatedBlogs' => $relatedBlogs,
            'author' => $author,
            'tableOfContents' => $tableOfContents,
            'pageTitle' => $blog['title'],
        ]);
    }

    /**
     * Retrieve the public URL of a blog's featured image.
     *
     * Returns null when the blog has no associated media record.
     */
    private function getFeaturedImageUrl(mixed $mediaId): ?string
    {
        $mediaId = (int) $mediaId;

        if ($mediaId <= 0) {
            return null;
        }

        $media = (new Media())->find($mediaId);

        if ($media === null) {
            return null;
        }

        $filePath = trim(
            str_replace('\\', '/', (string) ($media['file_path'] ?? '')),
            '/'
        );

        if (
            $filePath === '' ||
            str_contains($filePath, '..') ||
            preg_match('/^[a-z][a-z0-9+.-]*:/i', $filePath)
        ) {
            return null;
        }

        // Build the public URL using the application's base URL.
        return url('/' . $filePath);
    }
}
