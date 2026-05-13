<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use ApiPlatform\Metadata\Operation;
use App\Domain\Device\Port\DeviceRepositoryInterface;
use App\Domain\Device\Processor\DeviceRegistrationProcessor;
use App\Domain\Device\Request\DeviceRegistrationInputDTO;
use App\Domain\Device\Service\DeviceRegistrationService;
use App\Entity\Device;
use App\Infrastructure\Http\Security\DeviceTokenHasher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DeviceRegistrationProcessorTest extends TestCase
{
    private DeviceRepositoryInterface&MockObject $deviceRepository;

    private DeviceRegistrationProcessor $processor;

    protected function setUp(): void
    {
        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);
        $service = new DeviceRegistrationService(new DeviceTokenHasher(), $this->deviceRepository);
        $this->processor = new DeviceRegistrationProcessor($service);
    }

    #[Test]
    public function itDelegatesToServiceAndReturnsDTO(): void
    {
        $savedDevice = null;
        $this->deviceRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(static function (Device $device) use (&$savedDevice): bool {
                $savedDevice = $device;

                return true;
            }));

        $data = new DeviceRegistrationInputDTO();
        $data->platform = 'ios';
        $data->appVersion = '1.0.0';
        $data->deviceType = 'protected_person';

        $result = $this->processor->process($data, $this->createMock(Operation::class));

        $this->assertInstanceOf(Device::class, $savedDevice);
        $this->assertSame($savedDevice->getPublicId(), $result->deviceId);
        $this->assertSame('ios', $savedDevice->getPlatform());
        $this->assertSame('1.0.0', $savedDevice->getAppVersion());
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $result->deviceToken);
    }
}
