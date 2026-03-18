<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\ApiTools\UI;

use DI\Container;
use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/ops',
    description: 'Returns environment variables',
    tags: ['API'],
    security: [['Bearer Token' => []]],
    responses: [
        new OA\Response(
            response: JsonResponse::HTTP_OK,
            description: 'Array with environment variables',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'request', type: 'object'),
                new OA\Property(property: 'environment', type: 'object'),
                new OA\Property(property: 'di-config', type: 'object'),
                new OA\Property(property: 'opcache-status', type: 'object'),
                new OA\Property(property: 'apcu-status', type: 'object'),
                new OA\Property(property: 'php-version'),
                new OA\Property(property: 'php-config'),
                new OA\Property(property: 'php-loaded-extensions'),
            ])
        ),
        new OA\Response(
            response: JsonResponse::HTTP_UNAUTHORIZED,
            description: 'Token is not present',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "error", type: "string"),
            ])
        ),
        new OA\Response(
            response: JsonResponse::HTTP_FORBIDDEN,
            description: 'Token is invalid',
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "error", type: "string"),
            ])
        ),
    ]
)]
final class OpsController
{
    public function __construct(private Container $container)
    {
    }

    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse([
            'request' => [
                'method' => $request->getMethod(),
                'uri' => $request->getUri()->__toString(),
                'headers' => $request->getHeaders(),
                'path' => $request->getUri()->getPath(),
                'queryParams' => $request->getUri()->getQuery(),
                'body' => $request->getBody()->getContents(),
            ],
            'environment' => getenv(),
            'di-config' => $this->getConfigDefinitions(),
            'opcache-status' => opcache_get_status(),
            'apcu-status' => function_exists('apcu_enabled') && apcu_enabled() ? apcu_cache_info() : 'disabled',
            'php-version' => phpversion(),
            'php-config' => ini_get_all(null, false),
            'php-loaded-extensions' => implode(', ', get_loaded_extensions()),
        ]);
    }

    private function getConfigDefinitions(): array
    {
        $configValues = [];
        //iterating DI keys
        foreach ($this->container->getKnownEntryNames() as $entryName) {
            //getting value from container
            $configValues[$entryName] = $this->container->get($entryName);
        }
        return $configValues;
    }
}
