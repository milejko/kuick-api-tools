<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

use function DI\env;

return [
    // there is no valid token by default, you should provide one through environment variables
    'api.security.ops.guard.token' => env('API_SECURITY_OPS_GUARD_TOKEN', ''),
];
