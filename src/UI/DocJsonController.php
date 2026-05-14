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
use OpenApi\Attributes\Info;
use OpenApi\Generator;

#[OA\Get(
    path: '/api/doc.json',
    description: 'Returns the OpenAPI documentation in JSON format',
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
    private const string VENDOR_SOURCE_PATH = '/vendor/kuick/api-tools/src';

    public function __construct(
        #[Inject('app.projectDir')] private string $projectDir,
        #[Inject('api.openapi.title')] private string $openApiTitle,
        #[Inject('api.openapi.description')] private string $openApiDescription,
        #[Inject('api.openapi.version')] private string $openApiVersion,
    ) {
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
        // override info from attributes with values from configuration
        if (!($openapi->info instanceof Info)) {
            $openapi->info = new Info(title: $this->openApiTitle, version: $this->openApiVersion);
        }
        $openapi->info->title = $this->openApiTitle;
        $openapi->info->description = $this->openApiDescription;
        $openapi->info->version = $this->openApiVersion;
        return new JsonResponse(json_decode($openapi->toJson(), true));
    }
}
