<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

use Kuick\ApiTools\Security\OpsGuard;
use Kuick\Framework\Config\GuardConfig;

return [
    // OPS guard protects /api/ops* routes with OpsGuard
    // the token can be defined via environment variable
    // @see config/di/app.di.php and config/di/app.di@dev.php
    new GuardConfig(
        '/api/ops(.+)?',
        OpsGuard::class
    ),
];
