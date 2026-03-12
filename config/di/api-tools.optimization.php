<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\ApiTools\Security\OpsGuard;
use Kuick\ApiTools\UI\DocHtmlController;
use Kuick\ApiTools\UI\DocJsonController;
use Kuick\ApiTools\UI\OpsController;

use function DI\autowire;

return [
    // UI & security guard
    DocHtmlController::class => autowire(),
    DocJsonController::class => autowire(),
    OpsController::class => autowire(),
    OpsGuard::class => autowire(),
];
