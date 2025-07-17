<?php

namespace Framework\Schema;

use Opis\JsonSchema\Parsers\SchemaParser;
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\SchemaLoader;
use Opis\JsonSchema\Uri;
use Framework\Schema\Helper\Data as SchemaHelper;

class Loader extends SchemaLoader
{
    private SchemaHelper $helper;

    private array $schemaRegistry = [];

    public function __construct(
        SchemaResolver $resolver = new SchemaResolver(),
        ?SchemaParser $parser = null,
        bool $decodeJsonString = true
    ) {
        parent::__construct($parser, $resolver, $decodeJsonString);
        $this->helper = new SchemaHelper();
    }

    /**
     * Loads a schema from a given source.
     *
     * @param string $source The source of the schema (e.g., file path, URL).
     * @return mixed The loaded schema.
     */
    public function loadFrameworkSchema() : void
    {
        $schemas = $this->helper->diccoverSchemaFromFrameworkDir();

        foreach ($schemas as $name => $path) {
            if (!is_file($path)) {
                throw new \RuntimeException("Failed to Load $name Schema. file not found at: $path");
            }

            // Register the schema file with the resolver
            $this->resolver()->registerFile("#" . $name, $path);
        }
    }

    public function getLoadedSchemas(): array
    {
        return array_keys($this->schemaRegistry);
    }

}