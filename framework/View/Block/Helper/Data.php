<?php

namespace Framework\View\Block\Helper;
use App\ConfigProvider;

/**
 * Data class for templates blocks.
 * This class can be extended to provide additional helper methods for templates.
 */
class Data
{

    /**
     * Template Path Resolver
     *
     * This method can be used to resolve the path of a templates file based on a given identifier.
     * For example, it can be used to convert a templates identifier like 'admin/index/index' into a file path like 'app.php/view/admin/index/index.phtml'.
     * @param string $templateIdentifier The templates identifier.
     * @return string The resolved file path.
     */
    public function getTemplatePath(string $templateIdentifier): ?string
    {
        $templateParts = explode("::", $templateIdentifier);

        if (count($templateParts) < 2) {
            throw new \RuntimeException("Invalid templates path: {$templateIdentifier}");
        }

        $area = $templateParts[0];
        $basePath = \Framework\FileSystem\ViewFileSystem::getViewPath() . DIRECTORY_SEPARATOR . 'templates';
        $filePath = $area . DIRECTORY_SEPARATOR . $templateParts[1];
        return $basePath . DIRECTORY_SEPARATOR . $filePath;
    }

    /**
     * Render a templates with the given data.
     *
     * @param string $template The path to the templates file.
     * @param array $data The data to be passed to the templates.
     * @return string The rendered HTML content.
     */
    public function renderTemplate(string $template, array $data = []): string
    {
        if (!file_exists($template)) {
            return '';
        }

        ob_start();
        extract($data);
        require $template;
        $output = ob_get_clean() ?: '';
        return $this->escapeHtml($output);
    }

    /**
     * Escape a string for safe output in HTML.
     *
     * @param string $string The string to escape.
     * @return string The escaped string.
     */
    public function escapeHtml(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Escape a URL for safe output in HTML.
     *
     * @param string $url The URL to escape.
     * @return string The escaped URL.
     */
    public function escapeUrl(string $url): string
    {
        return htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}