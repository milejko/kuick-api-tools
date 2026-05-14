<?php

namespace Tests\Unit\Kuick\ApiTools\UI;

use Kuick\ApiTools\UI\DocJsonController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DocJsonController::class)]
class DocJsonControllerTest extends TestCase
{
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = realpath(dirname(__DIR__) . '/../../../');
    }

    public function testIfDocJsonIsReturned(): void
    {
        $docController = new DocJsonController($this->projectDir, 'Test API', 'This is a test API', '1.0.0');
        $response = $docController();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }

    public function testIfJsonContainsDefaultInfoValues(): void
    {
        $docController = new DocJsonController($this->projectDir, 'Test API', 'This is a test API', '1.0.0');
        $body = json_decode((string) $docController()->getBody(), true);
        $this->assertEquals('Test API', $body['info']['title']);
        $this->assertEquals('This is a test API', $body['info']['description']);
        $this->assertEquals('1.0.0', $body['info']['version']);
    }
}
