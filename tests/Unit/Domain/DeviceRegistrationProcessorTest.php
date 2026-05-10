<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use ApiPlatform\Metadata\Operation;
use App\Domain\Device\Processor\DeviceRegistrationProcessor;
use App\Domain\Device\Request\DeviceRegistrationInputDTO;
use App\Domain\Device\Response\DeviceRegistrationOutputDTO;
use App\Domain\Device\Service\DeviceRegistrationService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DeviceRegistrationProcessorTest extends TestCase
{
    private DeviceRegistrationService&MockObject $service;

    private DeviceRegistrationProcessor $processor;

    protected function setUp(): void
    {
        $this->service = $this->createMock(DeviceRegistrationService::class);
        $this->processor = new DeviceRegistrationProcessor($this->service);
    }

    #[Test]
    public function itDelegatesToServiceAndReturnsDTO(): void
    {
        $output = new DeviceRegistrationOutputDTO('device-id', 'plain-token');
        $this->service->method('register')->willReturn($output);

        $data = new DeviceRegistrationInputDTO();
        $data->platform = 'ios';
        $data->appVersion = '1.0.0';
        $data->deviceType = 'protected_person';

        $result = $this->processor->process($data, $this->createMock(Operation::class));

        $this->assertSame('device-id', $result->deviceId);
        $this->assertSame('plain-token', $result->deviceToken);
    }
}
