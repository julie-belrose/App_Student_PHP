<?php

require './vendor/autoload.php';
require 'bootstrap.php';
require 'menu.php';
require 'CLI_router.php';

try {
    [$studentService, $logger] = initApp();
    echo "Connected to MongoDB successfully!" . PHP_EOL;
    handleUserInput($studentService, $logger);
} catch (Exception $e) {
    echo "MongoDB Error: " . $e->getMessage(), PHP_EOL;
}
