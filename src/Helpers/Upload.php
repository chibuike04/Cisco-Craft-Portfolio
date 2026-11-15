<?php

declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

final class Upload
{
    private const DEFAULT_MAX_BYTES = 5_242_880; // 5 MB

    /**
     * Store a validated raster image and return its generated filename.
     * The destination directory should be an application-controlled upload directory.
     */
    public static function storeImage(
        array $file,
        string $destinationDirectory,
        int $maxBytes = self::DEFAULT_MAX_BYTES
    ): string {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid upload request.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::uploadErrorMessage((int) $file['error']));
        }

        $temporaryPath = (string) ($file['tmp_name'] ?? '');
        $fileSize = (int) ($file['size'] ?? 0);

        if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
            throw new RuntimeException('The uploaded file could not be verified.');
        }

        if ($fileSize <= 0 || $fileSize > $maxBytes) {
            throw new RuntimeException('The image is empty or exceeds the permitted upload size.');
        }

        $imageInfo = @getimagesize($temporaryPath);
        if ($imageInfo === false) {
            throw new RuntimeException('The uploaded file is not a valid image.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($temporaryPath);
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!is_string($mimeType) || !isset($allowedTypes[$mimeType]) || ($imageInfo['mime'] ?? '') !== $mimeType) {
            throw new RuntimeException('Only JPG, PNG, GIF and WebP images are allowed.');
        }

        $directory = rtrim($destinationDirectory, DIRECTORY_SEPARATOR);
        if ($directory === '') {
            throw new RuntimeException('An upload destination directory is required.');
        }

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('The upload directory could not be created.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
        $destination = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            throw new RuntimeException('The uploaded image could not be saved.');
        }

        return $filename;
    }

    /** Delete a file by basename only, preventing directory traversal. */
    public static function delete(string $filename, string $directory): bool
    {
        $safeFilename = basename($filename);
        $path = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeFilename;

        return is_file($path) ? unlink($path) : false;
    }

    private static function uploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The uploaded file is too large.',
            UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please choose a file to upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'The server is missing its temporary upload directory.',
            UPLOAD_ERR_CANT_WRITE => 'The server could not write the uploaded file.',
            UPLOAD_ERR_EXTENSION => 'A server extension stopped the upload.',
            default => 'An unknown upload error occurred.',
        };
    }
}
