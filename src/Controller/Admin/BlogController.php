<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Helpers\Flash;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Media;
use App\View\View;
use DateTimeImmutable;
use RuntimeException;
use Throwable;

final class BlogController extends ControllerSupport
{
    /**
     * Display all blog articles.
     */
    public function index(Request $request): void
    {
        $blogs = (new Blog())->all(
            500,
            0,
            'created_at',
            'DESC'
        );

        $categories = (new BlogCategory())->all(
            100,
            0,
            'name',
            'ASC'
        );

        $categoryLookup = [];

        foreach ($categories as $category) {
            $categoryLookup[(int) $category['id']] = $category;
        }

        foreach ($blogs as &$blog) {
            $category = $categoryLookup[
                (int) ($blog['category_id'] ?? 0)
            ] ?? [];

            $blog['category_name'] = $category['name'] ?? 'Uncategorized';
        }

        unset($blog);

        View::render('admin/blog', [
            'blogs' => $blogs,
            'categories' => $categories,
            'pageTitle' => 'Manage Blog Posts',
        ]);
    }

    /**
     * The blog form is handled by the modal on the main blog page.
     */
    public function create(Request $request): void
    {
        $this->redirectTo(url('/admin/blogs'));
    }

    /**
     * Store a new blog article.
     *
     * Supports an optional featured image through $_FILES.
     */
    public function store(Request $request): void
    {
        $data = $this->getBlogData($request);

        $errors = $this->validateBlogData($data);

        if (!empty($errors)) {
            $this->respondWithError(
                $request,
                implode(' ', $errors),
                422,
                $errors
            );

            Flash::error(implode(' ', $errors));
            $this->redirectTo(url('/admin/blogs'));
            return;
        }

        $uploadedMedia = null;

        try {
            $data['slug'] = $this->makeUniqueSlug($data['slug']);

            $data['published_at'] = $data['status'] === 'published'
                ? ($data['published_at'] ?? date('Y-m-d H:i:s'))
                : null;

            // Upload the featured image if one was selected.
            $uploadedMedia = $this->storeFeaturedImage($request);

            if ($uploadedMedia !== null) {
                $data['featured_media_id'] = $uploadedMedia['id'];
            }

            $blogId = (new Blog())->create($data);

            $this->respondWithSuccess(
                $request,
                'Blog article created successfully.',
                [
                    'id' => $blogId,
                    'featured_media_id' => $uploadedMedia['id'] ?? null,
                    'featured_image_url' => $uploadedMedia['url'] ?? null,
                ],
                201
            );

            Flash::success('Blog article created successfully.');
            $this->redirectTo(url('/admin/blogs'));
        } catch (Throwable $exception) {
            // Remove the uploaded file and media record if blog creation fails.
            if ($uploadedMedia !== null) {
                $this->removeUploadedMedia($uploadedMedia);
            }

            error_log('Blog creation failed: ' . $exception->getMessage());

            $this->respondWithError(
                $request,
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Unable to create the blog article.',
                $exception instanceof RuntimeException ? 422 : 500
            );

            Flash::error(
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Unable to create the blog article.'
            );

            $this->redirectTo(url('/admin/blogs'));
        }
    }

    /**
     * The edit form is handled by the modal on the main blog page.
     */
    public function edit(Request $request, int $id): void
    {
        $this->redirectTo(url('/admin/blogs'));
    }

    /**
     * Update an existing blog article.
     *
     * Featured image replacement is handled separately by
     * uploadFeaturedImage().
     */
    public function update(Request $request, int $id): void
    {
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            $this->respondWithError(
                $request,
                'Blog article not found.',
                404
            );

            Flash::error('Blog article not found.');
            $this->redirectTo(url('/admin/blogs'));
            return;
        }

        $data = $this->getBlogData($request);

        $errors = $this->validateBlogData($data);

        if (!empty($errors)) {
            $this->respondWithError(
                $request,
                implode(' ', $errors),
                422,
                $errors
            );

            Flash::error(implode(' ', $errors));
            $this->redirectTo(url('/admin/blogs'));
            return;
        }

        try {
            $data['slug'] = $this->makeUniqueSlug(
                $data['slug'],
                $id
            );

            if ($data['status'] === 'published') {
                $data['published_at'] = $data['published_at']
                    ?? $blog['published_at']
                    ?? date('Y-m-d H:i:s');
            } else {
                $data['published_at'] = null;
            }

            $blogModel->update($id, $data);

            $this->respondWithSuccess(
                $request,
                'Blog article updated successfully.',
                ['id' => $id]
            );

            Flash::success('Blog article updated successfully.');
            $this->redirectTo(url('/admin/blogs'));
        } catch (Throwable $exception) {
            error_log('Blog update failed: ' . $exception->getMessage());

            $this->respondWithError(
                $request,
                'Unable to update the blog article.',
                500
            );

            Flash::error('Unable to update the blog article.');
            $this->redirectTo(url('/admin/blogs'));
        }
    }

    /**
     * Upload or replace the featured image of an existing blog.
     *
     * This endpoint must receive a multipart/form-data POST request.
     */
    public function uploadFeaturedImage(Request $request, int $id): void
    {
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            $this->respondWithError(
                $request,
                'Blog article not found.',
                404
            );

            Flash::error('Blog article not found.');
            $this->redirectTo(url('/admin/blogs'));
            return;
        }

        $uploadedMedia = null;

        try {
            $uploadedMedia = $this->storeFeaturedImage($request);

            if ($uploadedMedia === null) {
                throw new RuntimeException('Please select an image to upload.');
            }

            $updated = $blogModel->update($id, [
                'featured_media_id' => $uploadedMedia['id'],
            ]);

            if (!$updated) {
                throw new RuntimeException(
                    'Unable to attach the uploaded image to this blog article.'
                );
            }

            $this->respondWithSuccess(
                $request,
                'Featured image uploaded successfully.',
                [
                    'id' => $id,
                    'featured_media_id' => $uploadedMedia['id'],
                    'featured_image_url' => $uploadedMedia['url'],
                ]
            );

            Flash::success('Featured image uploaded successfully.');
            $this->redirectTo(url('/admin/blogs'));
        } catch (Throwable $exception) {
            if ($uploadedMedia !== null) {
                $this->removeUploadedMedia($uploadedMedia);
            }

            error_log(
                'Blog featured image upload failed: '
                . $exception->getMessage()
            );

            $this->respondWithError(
                $request,
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Unable to upload the featured image.',
                $exception instanceof RuntimeException ? 422 : 500
            );

            Flash::error(
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Unable to upload the featured image.'
            );

            $this->redirectTo(url('/admin/blogs'));
        }
    }

    /**
     * Delete a blog article.
     */
    public function destroy(Request $request, int $id): void
    {
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            $this->respondWithError(
                $request,
                'Blog article not found.',
                404
            );

            Flash::error('Blog article not found.');
            $this->redirectTo(url('/admin/blogs'));
            return;
        }

        try {
            $blogModel->delete($id);

            $this->respondWithSuccess(
                $request,
                'Blog article deleted successfully.',
                ['id' => $id]
            );

            Flash::success('Blog article deleted successfully.');
        } catch (Throwable $exception) {
            error_log('Blog deletion failed: ' . $exception->getMessage());

            $this->respondWithError(
                $request,
                'Unable to delete the blog article.',
                500
            );

            Flash::error('Unable to delete the blog article.');
        }

        $this->redirectTo(url('/admin/blogs'));
    }

    /**
     * Save an uploaded featured image and create its media record.
     *
     * Files are stored in:
     * /uploads/blog/
     *
     * The database stores paths relative to the project root:
     * uploads/blog/filename.ext
     *
     * @return array{id:int, path:string, absolute_path:string, url:string}
     */
    private function storeFeaturedImage(Request $request): ?array
    {
        $file = $_FILES['featured_image'] ?? null;

        // An image is optional when creating a blog.
        if ($file === null || !is_array($file)) {
            return null;
        }

        $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($uploadError !== UPLOAD_ERR_OK) {
            $message = match ($uploadError) {
                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE => 'The image exceeds the allowed upload size.',
                UPLOAD_ERR_PARTIAL => 'The image was only partially uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'The server is missing its temporary upload directory.',
                UPLOAD_ERR_CANT_WRITE => 'The server could not save the uploaded image.',
                UPLOAD_ERR_EXTENSION => 'A server extension stopped the image upload.',
                default => 'An error occurred while uploading the image.',
            };

            throw new RuntimeException($message);
        }

        $temporaryPath = (string) ($file['tmp_name'] ?? '');
        $originalName = (string) ($file['name'] ?? '');
        $fileSize = (int) ($file['size'] ?? 0);

        if (
            $temporaryPath === ''
            || !is_uploaded_file($temporaryPath)
        ) {
            throw new RuntimeException('Invalid image upload.');
        }

        // Maximum image size: 5 MB.
        $maximumSize = 5 * 1024 * 1024;

        if ($fileSize <= 0 || $fileSize > $maximumSize) {
            throw new RuntimeException(
                'The featured image must not exceed 5 MB.'
            );
        }

        if (!class_exists(\finfo::class)) {
            throw new RuntimeException(
                'The server fileinfo extension is required for image uploads.'
            );
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($temporaryPath);

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (
            !is_string($mimeType)
            || !isset($allowedTypes[$mimeType])
        ) {
            throw new RuntimeException(
                'Invalid image format. Please use JPG, PNG, WebP, or GIF.'
            );
        }

        $extension = $allowedTypes[$mimeType];

        // Project root is three levels above src/Controller/Admin.
        $projectRoot = dirname(__DIR__, 3);

        $uploadDirectory = $projectRoot . DIRECTORY_SEPARATOR
            . 'uploads' . DIRECTORY_SEPARATOR . 'blog';

        if (
            !is_dir($uploadDirectory)
            && !mkdir($uploadDirectory, 0755, true)
            && !is_dir($uploadDirectory)
        ) {
            throw new RuntimeException(
                'Unable to create the blog image upload directory.'
            );
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

        $absolutePath = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;

        $relativePath = 'uploads/blog/' . $fileName;

        if (!move_uploaded_file($temporaryPath, $absolutePath)) {
            throw new RuntimeException(
                'Unable to save the uploaded featured image.'
            );
        }

        $safeOriginalName = basename($originalName);
        $safeOriginalName = substr($safeOriginalName, 0, 255);

        $altText = trim(
            (string) $request->input(
                'image_alt',
                $request->input('blogImageAlt', '')
            )
        );

        $altText = $altText !== ''
            ? mb_substr($altText, 0, 255)
            : null;

        try {
            $mediaId = (new Media())->create([
                'uploaded_by' => null,
                'disk' => 'public',
                'file_name' => $fileName,
                'original_name' => $safeOriginalName,
                'file_path' => $relativePath,
                'mime_type' => $mimeType,
                'size_bytes' => $fileSize,
                'alt_text' => $altText,
            ]);

            return [
                'id' => $mediaId,
                'path' => $relativePath,
                'absolute_path' => $absolutePath,
                'url' => url('/' . $relativePath),
            ];
        } catch (Throwable $exception) {
            if (is_file($absolutePath)) {
                unlink($absolutePath);
            }

            throw $exception;
        }
    }

    /**
     * Remove a newly uploaded file and its media record when
     * the associated blog operation fails.
     */
    private function removeUploadedMedia(array $media): void
    {
        try {
            if (
                isset($media['id'])
                && (int) $media['id'] > 0
            ) {
                (new Media())->delete((int) $media['id']);
            }

            if (
                !empty($media['absolute_path'])
                && is_file($media['absolute_path'])
            ) {
                unlink($media['absolute_path']);
            }
        } catch (Throwable $exception) {
            error_log(
                'Uploaded media cleanup failed: '
                . $exception->getMessage()
            );
        }
    }

    /**
     * Retrieve and prepare submitted blog form data.
     */
    private function getBlogData(Request $request): array
    {
        $title = trim((string) $request->input('title', ''));

        $submittedSlug = trim(
            (string) $request->input('slug', '')
        );

        return [
            'title' => $title,

            'slug' => $submittedSlug !== ''
                ? $submittedSlug
                : $title,

            'excerpt' => trim(
                (string) $request->input('excerpt', '')
            ),

            'content' => trim(
                (string) $request->input('content', '')
            ),

            'category_id' => (int) $request->input(
                'category_id',
                0
            ),

            'status' => trim(
                (string) $request->input('status', 'draft')
            ),

            'is_featured' => (int) $request->input(
                'is_featured',
                0
            ),

            'meta_title' => trim(
                (string) $request->input('meta_title', '')
            ),

            'meta_description' => trim(
                (string) $request->input('meta_description', '')
            ),

            'published_at' => $this->normalizePublishedAt(
                (string) $request->input('published_at', '')
            ),
        ];
    }

    /**
     * Validate blog form data.
     */
    private function validateBlogData(array $data): array
    {
        $errors = [];

        if (
            strlen($data['title']) < 3
            || strlen($data['title']) > 255
        ) {
            $errors[] = 'The title must be between 3 and 255 characters.';
        }

        if (strlen($data['excerpt']) > 1000) {
            $errors[] = 'The excerpt cannot exceed 1000 characters.';
        }

        if ($data['content'] === '') {
            $errors[] = 'Article content is required.';
        }

        if (!in_array($data['status'], ['draft', 'published'], true)) {
            $errors[] = 'Please select a valid publication status.';
        }

        if (!in_array($data['is_featured'], [0, 1], true)) {
            $errors[] = 'Invalid featured article selection.';
        }

        if (strlen($data['meta_title']) > 255) {
            $errors[] = 'The SEO title cannot exceed 255 characters.';
        }

        if (strlen($data['meta_description']) > 1000) {
            $errors[] = 'The SEO description cannot exceed 1000 characters.';
        }

        if ($data['category_id'] <= 0) {
            $errors[] = 'Please select a valid blog category.';
        } else {
            $category = (new BlogCategory())->find(
                $data['category_id']
            );

            if (!$category) {
                $errors[] = 'Please select a valid blog category.';
            }
        }

        return $errors;
    }

    /**
     * Normalize a datetime-local value for MySQL.
     */
    private function normalizePublishedAt(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        try {
            return (new DateTimeImmutable($value))->format(
                'Y-m-d H:i:s'
            );
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Generate a unique article slug.
     */
    private function makeUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = $this->makeSlug($value);

        if ($baseSlug === '') {
            $baseSlug = 'blog-post';
        }

        $slug = $baseSlug;
        $counter = 1;

        $blogModel = new Blog();

        while (true) {
            $existingBlog = $blogModel->firstWhere('slug', $slug);

            if (
                !$existingBlog
                || (
                    $ignoreId !== null
                    && (int) $existingBlog['id'] === $ignoreId
                )
            ) {
                break;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Determine whether the request expects a JSON response.
     */
    private function expectsJson(Request $request): bool
    {
        $accept = strtolower(
            (string) $request->header('Accept', '')
        );

        $requestedWith = strtolower(
            (string) $request->header('X-Requested-With', '')
        );

        return str_contains($accept, 'application/json')
            || $requestedWith === 'xmlhttprequest';
    }

    /**
     * Return a JSON success response for AJAX requests.
     */
    private function respondWithSuccess(
        Request $request,
        string $message,
        array $data = [],
        int $statusCode = 200
    ): void {
        if (!$this->expectsJson($request)) {
            return;
        }

        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        exit;
    }

    /**
     * Return a JSON error response for AJAX requests.
     */
    private function respondWithError(
        Request $request,
        string $message,
        int $statusCode = 400,
        array $errors = []
    ): void {
        if (!$this->expectsJson($request)) {
            return;
        }

        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        exit;
    }
}
