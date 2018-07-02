<?php

namespace ZXVentures\Tests\CodeChallenge\Controllers;

use ZXVentures\CodeChallenge\Controllers\Health\HealthController;
use PHPUnit\Framework\TestCase;

final class HealthControllerTest extends TestCase
{
    public function testHealthIsWorking()
    {
        $configMock = [
            'api' => [
                'name' => 'HealthControllerTest',
                'version' => 1
            ]
        ];

        $controller = new HealthController($configMock);
        $actual = $controller->index();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $actual);
    }
}
