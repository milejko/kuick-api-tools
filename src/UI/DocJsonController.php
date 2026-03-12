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
use OpenApi\Attributes as OAA;
use OpenApi\Generator;

#[OAA\Info(title: 'Kuick Framework API', version: '1.2')]
#[OAA\Get(
    path: '/api/doc.json',
    description: 'Returns OpenApi Documentation JSON',
    tags: ['API'],
    responses: [
        new OAA\Response(
            response: JsonResponse::HTTP_OK,
            description: 'Array with environment variables',
            content: new OAA\JsonContent()
        ),
    ]
)]
final class DocJsonController
{
    private const SOURCE_PATH = '/src';

    public function __construct(#[Inject('app.projectDir')] private string $projectDir)
    {
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function __invoke(): JsonResponse
    {
        $openapi = (new Generator())->generate([$this->projectDir . self::SOURCE_PATH]);
        return new JsonResponse(json_decode($openapi->toJson(), true));
    }
}
