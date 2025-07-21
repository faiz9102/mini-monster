<?php

namespace Framework\View\Block\Helper;

/**
 * Data class for templates blocks.
 * This class can be extended to provide additional helper methods for templates.
 */
class Data
{
    /**
     * Generate the path to the template file based on the template identifier.
     * Template identifiers format : '<area>::template_file'.
     *
     * @param string $templateIdentifier
     * @return string
     */
    public static function getTemplatePath(string $templateIdentifier): string
    {
        $templateParts = explode("::", $templateIdentifier );

        if (count($templateParts) !== 2) {
            throw new \RuntimeException("Invalid templates identifier format: $templateIdentifier. Expected format is 'area::template_file'.");
        }

        $area = $templateParts[0];
        $basePath = \Framework\FileSystem\ViewFileSystem::getViewPath() . DIRECTORY_SEPARATOR . 'templates';
        $filePath = $area . DIRECTORY_SEPARATOR . $templateParts[1];
        return $basePath . DIRECTORY_SEPARATOR . $filePath;
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