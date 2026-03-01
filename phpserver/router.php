<?php

// router.php - A simple router for PHP's built-in web server
// Usage: php -S localhost:8000 ./phpsever/router.php (from root of the project)

// route everything through index.php
require_once realpath(__DIR__ . '/../public/index.php');