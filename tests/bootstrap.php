<?php

declare(strict_types=1);

$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';

if (! file_exists($autoloadPath)) {
    fwrite(
        STDERR,
        "Composer dependencies are missing. Run: composer install\n"
    );
    exit(1);
}

require $autoloadPath;
