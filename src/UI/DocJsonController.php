<?php

/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\ApiTools\UI;

use DI\Attribute\Inject;
use Kuick\Http\Message\JsonResponse;
use OpenApi\Attributes as OA;
use OpenApi\Generator;

#[OA\Info(title: 'Kuick Framework API', version: '2.8')]
#[OA\Get(
    path: '/api/doc.json',
    description: 'Returns OpenApi Documentation JSON',
    tags: ['API'],
    responses: [
        new OA\Response(
            response: JsonResponse::HTTP_OK,
            description: 'Current OpenAPI documentation in JSON format',
            content: new OA\JsonContent()
        ),
    ]
)]
final class DocJsonController
{
    private const string SOURCE_PATH = '/src';
    private const string VENDOR_SOURCE_PATH = '/vendor/kuick-api-tools/src';

    public function __construct(#[Inject('app.projectDir')] private string $projectDir)
    {
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function __invoke(): JsonResponse
    {
        $openapi = (new Generator())->generate([
            $this->projectDir . self::SOURCE_PATH,
            $this->projectDir . self::VENDOR_SOURCE_PATH,
        ]);
        return new JsonResponse(json_decode($openapi->toJson(), true));
    }
}
