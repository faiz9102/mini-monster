<?php

namespace App\Services;

use Framework\DI\ServiceProvider;
use Framework\Schema\Helper\Data as SchemaHelper;
use Framework\Schema\Loader;
use Opis\JsonSchema\Parsers\SchemaParser;
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Parsers\DefaultVocabulary as Vocabulary;

class SchemaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }
}