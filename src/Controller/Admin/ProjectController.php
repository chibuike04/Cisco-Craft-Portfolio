<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Helpers\Flash;
use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\View\View;
use DateTimeImmutable;
use RuntimeException;
use Throwable;

final class ProjectController extends ControllerSupport
{
    /**
     * Display all projects in the admin dashboard.
     */
    public function index(Request $request): void
    {
        $projects = (new Project())->all(
            500,
            0,
            'created_at',
            'DESC'
        );

        $categories = (new ProjectCategory())->all(
            100,
            0,
            'name',
            'ASC'
        );

        $categoryLookup = [];

        foreach ($categories as $category) {
            $categoryLookup[(int) $category['id']] = $category;
        }

        foreach ($projects as &$project) {
            $category = $categoryLookup[
                (int) ($project['category_id'] ?? 0)
            ] ?? [];

            $project['category_name'] = $category['name'] ?? 'Uncategorized';

            $project['thumbnail_url'] = $this->getThumbnailUrl(
                $project['thumbnail_media_id'] ?? null
            );
        }

        unset($project);

       View::render('admin/projects', [
            'projects' => $projects,
            'categories' => $categories,
            'pageTitle' => 'Manage Projects',
        ]);
    }

    /**
     * The project form is handled by the modal.
     */
    public function create(Request $request): void
    {
        $this->redirectTo(url('/admin/projects'));
    }

    /**
     * Store a new project.
     *
     * Supports an optional thumbnail upload.
     */
    public function store(Request $request): void
    {
        $data = $this->getProjectData($request);

        $errors = $this->validateProjectData($data);

        if (!empty($errors)) {
            $this->respondWithError(
                $request,
                implode(' ', $errors),
                422,
                $errors
            );

            Flash::error(implode(' ', $errors));
            $this->redirectTo(url('/admin/projects'));
        }

        $uploadedMedia = null;

        try {
            $data['slug'] = $this->makeUniqueSlug($data['title']);

            $data['published_at'] = $data['status'] === 'published'
                ? ($data['published_at'] ?? date('Y-m-d H:i:s'))
                : null;

            $uploadedMedia = $this->storeProjectThumbnail($request);

            if ($uploadedMedia !== null) {
                $data['thumbnail_media_id'] = $uploadedMedia['id'];
            }

            $projectId = (new Project())->create($data);

            $this->respondWithSuccess(
                $request,
                'Project created successfully.',
                [
                    'id' => $projectId,
                    'slug' => $data['slug'],
                    'thumbnail_media_id' => $uploadedMedia['id'] ?? null,
                    'thumbnail_url' => $uploadedMedia['url'] ?? null,
                ],
                201
            );

            Flash::success('Project created successfully.');
            $this->redirectTo(url('/admin/projects'));
        } catch (Throwable $exception) {
            if ($uploadedMedia !== null) {
                $this->removeUploadedMedia($uploadedMedia);
            }

            error_log('Project creation failed: ' . $exception->getMessage());

            $message = $exception instanceof RuntimeException
                ? $exception->getMessage()
                : 'Unable to create the project.';

            $this->respondWithError(
                $request,
                $message,
                $exception instanceof RuntimeException ? 422 : 500
            );

            Flash::error($message);
            $this->redirectTo(url('/admin/projects'));
        }
    }

    /**
     * The edit form is handled by the modal.
     */
    public function edit(Request $request, int $id): void
    {
        $this->redirectTo(url('/admin/projects'));
    }

    /**
     * Update an existing project.
     *
     * Thumbnail replacement is handled separately by
     * uploadThumbnail().
     */
    public function update(Request $request, int $id): void
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);

        if (!$project) {
            $this->respondWithError(
                $request,
                'Project not found.',
                404
            );

            Flash::error('Project not found.');
            $this->redirectTo(url('/admin/projects'));
        }

        $data = $this->getProjectData($request);

        $errors = $this->validateProjectData($data);

        if (!empty($errors)) {
            $this->respondWithError(
                $request,
                implode(' ', $errors),
                422,
                $errors
            );

            Flash::error(implode(' ', $errors));
            $this->redirectTo(url('/admin/projects'));
        }

        try {
            if ($data['title'] !== $project['title']) {
                $data['slug'] = $this->makeUniqueSlug(
                    $data['title'],
                    $id
                );
            }

            if ($data['status'] === 'published') {
                $data['published_at'] = $data['published_at']
                    ?? $project['published_at']
                    ?? date('Y-m-d H:i:s');
            } else {
                $data['published_at'] = null;
            }

            $projectModel->update($id, $data);

            $this->respondWithSuccess(
                $request,
                'Project updated successfully.',
                [
                    'id' => $id,
                    'slug' => $data['slug'] ?? $project['slug'],
                ]
            );

            Flash::success('Project updated successfully.');
            $this->redirectTo(url('/admin/projects'));
        } catch (Throwable $exception) {
            error_log('Project update failed: ' . $exception->getMessage());

            $this->respondWithError(
                $request,
                'Unable to update the project.',
                500
            );

            Flash::error('Unable to update the project.');
            $this->redirectTo(url('/admin/projects'));
        }
    }

    /**
     * Upload or replace a project's thumbnail.
     *
     * This endpoint must receive multipart/form-data
     * through a POST request.
     */
    public function uploadThumbnail(Request $request, int $id): void
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);

        if (!$project) {
            $this->respondWithError(
                $request,
                'Project not found.',
                404
            );

            Flash::error('Project not found.');
            $this->redirectTo(url('/admin/projects'));
        }

        $uploadedMedia = null;

        try {
            $uploadedMedia = $this->storeProjectThumbnail($request);

            if ($uploadedMedia === null) {
                throw new RuntimeException(
                    'Please select a thumbnail image to upload.'
                );
            }

            $updated = $projectModel->update($id, [
                'thumbnail_media_id' => $uploadedMedia['id'],
            ]);

            if (!$updated) {
                throw new RuntimeException(
                    'Unable to attach the uploaded thumbnail to this project.'
                );
            }

            $this->respondWithSuccess(
                $request,
                'Project thumbnail uploaded successfully.',
                [
                    'id' => $id,
                    'thumbnail_media_id' => $uploadedMedia['id'],
                    'thumbnail_url' => $uploadedMedia['url'],
                ]
            );

            Flash::success('Project thumbnail uploaded successfully.');
            $this->redirectTo(url('/admin/projects'));
        } catch (Throwable $exception) {
            if ($uploadedMedia !== null) {
                $this->removeUploadedMedia($uploadedMedia);
            }

            error_log(
                'Project thumbnail upload failed: '
                . $exception->getMessage()
            );

            $message = $exception instanceof RuntimeException
                ? $exception->getMessage()
                : 'Unable to upload the project thumbnail.';

            $this->respondWithError(
                $request,
                $message,
                $exception instanceof RuntimeException ? 422 : 500
            );

            Flash::error($message);
            $this->redirectTo(url('/admin/projects'));
        }
    }

    /**
     * Delete a project using the model's soft-delete feature.
     */
    public function delete(Request $request, int $id): void
    {
        $projectModel = new Project();
        $project = $projectModel->find($id);

        if (!$project) {
            $this->respondWithError(
                $request,
                'Project not found.',
                404
            );

            Flash::error('Project not found.');
            $this->redirectTo(url('/admin/projects'));
        }

        try {
            $projectModel->delete($id);

            $this->respondWithSuccess(
                $request,
                'Project deleted successfully.',
                ['id' => $id]
            );

            Flash::success('Project deleted successfully.');
        } catch (Throwable $exception) {
            error_log('Project deletion failed: ' . $exception->getMessage());

            $this->respondWithError(
                $request,
                'Unable to delete the project.',
                500
            );

            Flash::error('Unable to delete the project.');
        }

        $this->redirectTo(url('/admin/projects'));
    }

    /**
     * Retrieve and prepare submitted project form data.
     */
    private function getProjectData(Request $request): array
    {
        $categoryId = $request->input('category_id', '');

        return [
            'category_id' => $categoryId === '' || $categoryId === null
                ? null
                : (int) $categoryId,

            'title' => trim(
                (string) $request->input('title', '')
            ),

            'summary' => trim(
                (string) $request->input('summary', '')
            ),

            'description' => trim(
                (string) $request->input('description', '')
            ),

            'client_name' => trim(
                (string) $request->input('client_name', '')
            ),

            'role' => trim(
                (string) $request->input('role', '')
            ),

            'project_url' => trim(
                (string) $request->input('project_url', '')
            ),

            'repository_url' => trim(
                (string) $request->input('repository_url', '')
            ),

            'status' => trim(
                (string) $request->input('status', 'draft')
            ),

            'is_featured' => (int) $request->input(
                'is_featured',
                0
            ),

            'sort_order' => max(
                0,
                (int) $request->input('sort_order', 0)
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
     * Validate project form data.
     */
    private function validateProjectData(array $data): array
    {
        $errors = [];

        if (
            strlen($data['title']) < 3
            || strlen($data['title']) > 200
        ) {
            $errors[] = 'Project title must be between 3 and 200 characters.';
        }

        if (strlen($data['summary']) > 500) {
            $errors[] = 'Project summary cannot exceed 500 characters.';
        }

        if (!in_array($data['status'], ['draft', 'published'], true)) {
            $errors[] = 'Please select a valid project status.';
        }

        if (!in_array($data['is_featured'], [0, 1], true)) {
            $errors[] = 'Invalid featured project selection.';
        }

        if (
            $data['project_url'] !== ''
            && !filter_var($data['project_url'], FILTER_VALIDATE_URL)
        ) {
            $errors[] = 'Please provide a valid project URL.';
        }

        if (
            $data['repository_url'] !== ''
            && !filter_var($data['repository_url'], FILTER_VALIDATE_URL)
        ) {
            $errors[] = 'Please provide a valid repository URL.';
        }

        if (
            $data['category_id'] !== null
            && !(new ProjectCategory())->find($data['category_id'])
        ) {
            $errors[] = 'Please select a valid project category.';
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
     * Generate a unique project slug.
     */
    private function makeUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = $this->makeSlug($value);

        if ($baseSlug === '') {
            $baseSlug = 'project';
        }

        $slug = $baseSlug;
        $counter = 1;

        $projectModel = new Project();

        while (true) {
            $existingProject = $projectModel->firstWhere(
                'slug',
                $slug
            );

            if (
                !$existingProject
                || (
                    $ignoreId !== null
                    && (int) $existingProject['id'] === $ignoreId
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
     * Save an uploaded project thumbnail and create its media record.
     *
     * Files are stored in /uploads/projects/.
     *
     * @return array{id:int,path:string,absolute_path:string,url:string}|null
     */
    private function storeProjectThumbnail(Request $request): ?array
    {
        $file = $_FILES['projectImage'] ?? null;

        if ($file === null || !is_array($file)) {
            return null;
        }

        $uploadError = (int) (
            $file['error'] ?? UPLOAD_ERR_NO_FILE
        );

        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($uploadError !== UPLOAD_ERR_OK) {
            $message = match ($uploadError) {
                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE =>
                    'The thumbnail exceeds the allowed upload size.',

                UPLOAD_ERR_PARTIAL =>
                    'The thumbnail was only partially uploaded.',

                UPLOAD_ERR_NO_TMP_DIR =>
                    'The server is missing its temporary upload directory.',

                UPLOAD_ERR_CANT_WRITE =>
                    'The server could not save the uploaded thumbnail.',

                UPLOAD_ERR_EXTENSION =>
                    'A server extension stopped the thumbnail upload.',

                default =>
                    'An error occurred while uploading the thumbnail.',
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
            throw new RuntimeException('Invalid thumbnail upload.');
        }

        // Maximum thumbnail size: 5 MB.
        $maximumSize = 5 * 1024 * 1024;

        if ($fileSize <= 0 || $fileSize > $maximumSize) {
            throw new RuntimeException(
                'The project thumbnail must not exceed 5 MB.'
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
                'Invalid thumbnail format. Use JPG, PNG, WebP, or GIF.'
            );
        }

        $extension = $allowedTypes[$mimeType];

        // Project root is three levels above src/Controller/Admin.
        $projectRoot = dirname(__DIR__, 3);

        $uploadDirectory = $projectRoot
            . DIRECTORY_SEPARATOR . 'uploads'
            . DIRECTORY_SEPARATOR . 'projects';

        if (
            !is_dir($uploadDirectory)
            && !mkdir($uploadDirectory, 0755, true)
            && !is_dir($uploadDirectory)
        ) {
            throw new RuntimeException(
                'Unable to create the project thumbnail upload directory.'
            );
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

        $absolutePath = $uploadDirectory
            . DIRECTORY_SEPARATOR . $fileName;

        $relativePath = 'uploads/projects/' . $fileName;

        if (!move_uploaded_file($temporaryPath, $absolutePath)) {
            throw new RuntimeException(
                'Unable to save the uploaded project thumbnail.'
            );
        }

        $safeOriginalName = substr(
            basename($originalName),
            0,
            255
        );

        $altText = trim(
            (string) $request->input(
                'image_alt',
                $request->input('projectImageAlt', '')
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
     * Retrieve the public URL of a project's thumbnail.
     */
    private function getThumbnailUrl(mixed $mediaId): ?string
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
            $filePath === ''
            || str_contains($filePath, '..')
            || preg_match('/^[a-z][a-z0-9+.-]*:/i', $filePath)
        ) {
            return null;
        }

        return url('/' . $filePath);
    }

    /**
     * Remove a newly uploaded file and its media record
     * when the associated project operation fails.
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
                'Project media cleanup failed: '
                . $exception->getMessage()
            );
        }
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

        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
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

        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
