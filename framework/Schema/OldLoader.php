<?php

namespace Framework\Schema;

use Opis\JsonSchema\SchemaLoader;
use Opis\JsonSchema\Resolvers\SchemaResolver;

/**
 * Class Loader
 *
 * This class extends the Opis\JsonSchema\SchemaLoader to provide schema loading functionality.
 * It can be used to load JSON schemas from various sources, such as files or URLs.
 * It loads all the schema files in an array "schema name" => "schema file path".
 */
class OldLoader extends SchemaLoader
{
    private static ?OldLoader $instance = null;
    private array $schemaRegistry = [];
    private string $cacheDir;
    private string $frameworkDir;
    private bool $initialized = false;

    private function __construct()
    {
        parent::__construct();
        $this->cacheDir = dirname(__DIR__, 2) . '/var/cache';
        $this->frameworkDir = dirname(__DIR__);
        
        // Ensure cache directory exists
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register method to be called during app boot
     */
    public function register(): void
    {
        if ($this->initialized) {
            return;
        }

        $this->discoverAndRegisterSchemas();
        $this->setupCaching();
        $this->initialized = true;
    }

    /**
     * Discover all schema files recursively in framework directory
     */
    private function discoverAndRegisterSchemas(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->frameworkDir)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && 
                $file->getExtension() === 'json' && 
                str_ends_with($file->getBasename('.json'), '-schema')) {
                $this->registerSchemaFile($file->getPathname());
            }
        }
    }

    /**
     * Register a single schema file
     */
    private function registerSchemaFile(string $filePath): void
    {
        $fileName = basename($filePath, '.json');
        $schemaName = str_replace('-schema', '', $fileName);
        
        $this->schemaRegistry[$schemaName] = $filePath;
        
        // Load schema with Opis SchemaLoader
        $schemaContent = file_get_contents($filePath);
        if ($schemaContent !== false) {
            $schemaData = json_decode($schemaContent);
            if ($schemaData !== null) {
                // Create a URI for the schema
                $schemaUri = "schema:///{$schemaName}.json";
                $this->loadObjectSchema($schemaData, $schemaUri);
            }
        }
    }

    /**
     * Setup caching for schema validation
     */
    private function setupCaching(): void
    {
        // Set up file-based caching in var/cache directory
        $cacheFile = $this->cacheDir . '/schema_cache.json';
        
        // Load existing cache if available
        if (file_exists($cacheFile)) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if ($cachedData && is_array($cachedData)) {
                foreach ($cachedData as $schemaName => $schemaData) {
                    $schemaUri = "schema:///{$schemaName}.json";
                    $schemaObject = json_decode(json_encode($schemaData));
                    $this->loadObjectSchema($schemaObject, $schemaUri);
                }
            }
        }
    }

    /**
     * Get registered schema names
     */
    public function getRegisteredSchemas(): array
    {
        return array_keys($this->schemaRegistry);
    }

    /**
     * Get schema file path by name
     */
    public function getSchemaPath(string $schemaName): ?string
    {
        return $this->schemaRegistry[$schemaName] ?? null;
    }

    /**
     * Save current schemas to cache
     */
    public function saveCache(): void
    {
        $cacheData = [];
        foreach ($this->schemaRegistry as $schemaName => $filePath) {
            $schemaContent = file_get_contents($filePath);
            if ($schemaContent !== false) {
                $cacheData[$schemaName] = json_decode($schemaContent, true);
            }
        }
        
        $cacheFile = $this->cacheDir . '/schema_cache.json';
        file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT));
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        // Clear Opis internal cache
        parent::clearCache();
        
        // Clear our file cache
        $cacheFile = $this->cacheDir . '/schema_cache.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
    
    /**
     * Get schema by name
     */
    public function getSchemaByName(string $schemaName): ?\Opis\JsonSchema\Schema
    {
        $schemaUri = \Opis\JsonSchema\Uri::parse("schema:///{$schemaName}.json");
        return $this->loadSchemaById($schemaUri);
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}