<?php

$services = require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../CLI_router.php';

handleCli($services);
