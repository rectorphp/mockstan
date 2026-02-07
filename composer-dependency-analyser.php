<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())

    // already in phpstan/phpstan
    ->ignoreErrorsOnPackage('nikic/php-parser', [ErrorType::DEV_DEPENDENCY_IN_PROD]);
