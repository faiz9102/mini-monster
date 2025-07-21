<?php

namespace Framework\View\Layout\Helper;
use Framework\FileSystem\ViewFileSystem;
use Framework\Utils\Json\Serializer;
use JetBrains\PhpStorm\ArrayShape;

class Data
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

    /**
     * Get the layout file based on the layout name.
     * layout name is in the format 'area:controllerName_ActionName'. (default)
     * area can be 'adminhtml', 'frontend' or empty for default area.
     * which should resolve to 'view/layout/area/controllername_actioname.json'.
     *
     * @param string $layoutName
     * @return string
     */
    public static function getLayoutFile(string $layoutName): string
    {
        $layoutInfo = self::getLayoutInfo($layoutName);
        $viewPath = ViewFileSystem::getViewPath();

        return $viewPath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR
            . $layoutInfo['area'] . DIRECTORY_SEPARATOR
            . $layoutInfo['filePath'] . '.json';
    }

    /**
     * @param string $layoutName
     * @return array
     */
    #[ArrayShape(['area' => "string", 'filePath' => "string", 'parts' => "string[]", 'partsCount' => "int|mixed", 'filePathComponents' => "array|string[]"])]
    public static function getLayoutInfo(string $layoutName): array
    {
        $parts = explode(':', $layoutName);
        $partsCount = count($parts);

        if ($partsCount < 1 || $partsCount > 2) {
            throw new \InvalidArgumentException("Layout identifier must be in the format 'area:controllerName_actionName'.");
        }

        if ($partsCount === 2) {
            $area = strtolower($parts[0]) ?: 'base';
            $identifier = $parts[1];
        } else {
            $area = 'base';
            $identifier = $parts[0];
        }

        $fileNameParts = explode('_', $identifier);
        $filePathComponents = array_map(function ($component) {
            return strtolower(trim($component));
        }, $fileNameParts);
        $filePath = implode("_", $filePathComponents);

        return [
            'area' => $area,
            'filePath' => $filePath,
            'parts' => $parts,
            'partsCount' => $partsCount,
            'filePathComponents' => $filePathComponents,
        ];
    }
}