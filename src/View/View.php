<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;

/**
 * Small PHP template renderer.
 *
 * Views receive data from controllers. Database queries belong in Models,
 * never in this directory.
 */
final class View
{
    private static function basePath(): string
    {
        return __DIR__;
    }

    public static function render(string $template, array $data = [], ?string $layout = null): void
    {
        $templateFile = self::resolve($template);
        extract($data, EXTR_SKIP);

        ob_start();
        require $templateFile;
        $content = (string) ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = self::resolve($layout);
        require $layoutFile;
    }

    public static function partial(string $template, array $data = []): void
    {
        $templateFile = self::resolve($template);
        extract($data, EXTR_SKIP);
        require $templateFile;
    }

    private static function resolve(string $template): string
    {
        $template = str_replace('\\', '/', trim($template));
        if ($template === '' || str_contains($template, '..')) {
            throw new RuntimeException('Invalid view template path.');
        }

        if (!str_ends_with($template, '.php')) {
            $template .= '.php';
        }

        $file = self::basePath() . '/' . ltrim($template, '/');
        if (!is_file($file)) {
            throw new RuntimeException('View template not found: ' . $template);
        }

        return $file;
    }
}
