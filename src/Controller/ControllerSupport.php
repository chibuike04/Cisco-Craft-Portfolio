<?php
namespace App\Controller;

/**
 * ControllerSupport
 *
 * Base controller class.
 * Provides shared helper methods that can be reused
 * by all public and admin controllers.
 */
abstract class ControllerSupport
{
    /**
     * Return a successful JSON response.
     *
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        exit;
    }

    /**
     * Redirect the user to another URL.
     *
     * @param string $url
     * @return void
     */
    protected function redirectTo(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Escape output to prevent HTML injection (XSS).
     *
     * @param mixed $value
     * @return string
     */
    protected function escape(mixed $value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }

    /**
     * Return a value or a fallback when the value is empty.
     *
     * @param mixed $value
     * @param mixed $fallback
     * @return mixed
     */
    protected function valueOr(mixed $value, mixed $fallback = ''): mixed
    {
        return ($value !== null && $value !== '') ? $value : $fallback;
    }

    /**
     * Generate a URL-friendly slug.
     *
     * @param string $value
     * @return string
     */
    protected function makeSlug(string $value): string
    {
        $value = strtolower(trim($value));

        $value = preg_replace('/[^a-z0-9]+/', '-', $value);

        return trim($value ?? '', '-');
    }

    /**
     * Calculate estimated reading time for an article.
     *
     * Uses an average reading speed of 200 words per minute.
     *
     * @param string|null $content
     * @return int
     */
    protected function readingTime(?string $content): int
    {
        if (empty($content)) {
            return 1;
        }

        $plainText = strip_tags($content);

        $wordCount = str_word_count($plainText);

        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Format a date for display.
     *
     * @param string|null $date
     * @param string $format
     * @return string
     */
    protected function formatDate(
        ?string $date,
        string $format = 'M d, Y'
    ): string {
        if (empty($date)) {
            return '';
        }

        try {
            return (new \DateTime($date))->format($format);
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Get a pagination data structure.
     *
     * @param int $totalItems
     * @param int $currentPage
     * @param int $perPage
     * @return array
     */
    protected function pagination(
        int $totalItems,
        int $currentPage = 1,
        int $perPage = 9
    ): array {
        $perPage = max(1, $perPage);

        $totalPages = (int) ceil($totalItems / $perPage);

        $currentPage = max(
            1,
            min($currentPage, max(1, $totalPages))
        );

        return [
            'total_items' => $totalItems,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'offset' => ($currentPage - 1) * $perPage,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => max(1, $currentPage - 1),
            'next_page' => min(
                max(1, $totalPages),
                $currentPage + 1
            ),
        ];
    }

    /**
     * Retrieve a query parameter from the URL.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Retrieve the current request method.
     *
     * @return string
     */
    protected function requestMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Check whether the current request is an AJAX request.
     *
     * @return bool
     */
    protected function isAjax(): bool
    {
        return strtolower(
            $_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''
        ) === 'xmlhttprequest';
    }
}
