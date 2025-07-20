<?php

namespace Framework\View\Layout;

use Framework\Utils\Json\Serializer;

class LayoutParser
{
    /**
     * Parse the JSON layout file and return the layout config as Associative array
     *
     * @param string $layoutFile The path to the layout file.
     * @return array The layout config array.
     */
    public static function parse(string $layoutFile): array
    {
        if (!file_exists($layoutFile)) {
            throw new \InvalidArgumentException("Layout file does not exist: $layoutFile");
        }

        $content = file_get_contents($layoutFile);
        if ($content === false) {
            throw new \RuntimeException("Failed to read layout file: $layoutFile");
        }

        try {
            $layoutConfig = Serializer::decode($content);
        }
        catch (\RuntimeException $e) {
            throw new \RuntimeException("Failed to parse layout file: $layoutFile. Error: " . $e->getMessage());
        }

        return $layoutConfig;
    }
}