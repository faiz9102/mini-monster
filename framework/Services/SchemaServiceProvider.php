<?php

namespace App\Services;

use Framework\DI\AbstractServiceProvider;
use Framework\Schema\SchemaFacade;
use Framework\Schema\Validator;
use Framework\Schema\Loader;
use Framework\Schema\Resolver;
use Framework\Schema\Helper\Data as SchemaHelper;

class SchemaServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // Register SchemaFacade as a singleton
        $this->container->bind(SchemaFacade::class, function () {
            $resolver = $this->container->get(Resolver::class);
            $loader = new Loader(resolver: $resolver);
            $validator = new Validator($loader);

            $helper = $this->container->get(SchemaHelper::class);

            return new SchemaFacade($helper, $validator);
        });
    }
}