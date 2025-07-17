<?php

namespace Framework\Schema;

use Framework\Schema\Loader;
use Framework\Schema\Validator;
use Framework\Schema\Helper\Data as SchemaHelper;
class SchemaFacade
{
    private static ?SchemaFacade $instance = null;
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Loader
     */
    protected Loader $loader;

    protected SchemaHelper $helper;

    private function __construct()
    {
        $this->helper = new SchemaHelper();
        $this->loader = new Loader();
        $this->validator = new Validator($this->loader);
    }

    public function getHelper(): SchemaHelper
    {
        return $this->helper;
    }

    public function getLoader() : Loader
    {
        return $this->loader;
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}