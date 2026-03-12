<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\ApiTools\UI\DocHtmlController;
use Kuick\ApiTools\UI\DocJsonController;
use Kuick\ApiTools\UI\OpsController;
use Kuick\Framework\Config\RouteConfig;

return [
    // OPS route gives some insight of server environment
    new RouteConfig(
        '/api/ops',
        OpsController::class
    ),
    // OpenAPI JSON documentation
    new RouteConfig(
        '/api/doc.json',
        DocJsonController::class
    ),
    // OpenAPI HTML documentation
    new RouteConfig(
        '/api/doc',
        DocHtmlController::class
    ),
];
