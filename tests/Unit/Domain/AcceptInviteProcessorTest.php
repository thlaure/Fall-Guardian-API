<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use ApiPlatform\Metadata\Operation;
use App\Domain\Caregiver\Processor\AcceptInviteProcessor;
use App\Domain\Caregiver\Service\InviteServiceInterface;
use App\Entity\CaregiverLink;
use App\Entity\Device;
use App\Infrastructure\Http\Security\DeviceContextInterface;
use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class AcceptInviteProcessorTest extends TestCase
{
    private InviteServiceInterface&MockObject $inviteService;

    private DeviceContextInterface&MockObject $currentDeviceProvider;

    private AcceptInviteProcessor $processor;

    protected function setUp(): void
    {
        $this->inviteService = $this->createMock(InviteServiceInterface::class);
        $this->currentDeviceProvider = $this->createMock(DeviceContextInterface::class);
        $this->processor = new AcceptInviteProcessor($this->inviteService, $this->currentDeviceProvider);
    }

    #[Test]
    public function itAcceptsInviteAndReturnsNull(): void
    {
        $device = $this->createMock(Device::class);
        $this->currentDeviceProvider->method('requireDevice')->willReturn($device);
        $this->inviteService->method('acceptInvite')->willReturn($this->createMock(CaregiverLink::class));

        $result = $this->processor->process(null, $this->createMock(Operation::class), ['code' => 'ABCD1234']);

        $this->assertNull($result);
    }

    #[Test]
    public function itThrowsNotFoundWhenInviteNotFound(): void
    {
        $device = $this->createMock(Device::class);
        $this->currentDeviceProvider->method('requireDevice')->willReturn($device);
        $this->inviteService->method('acceptInvite')->willThrowException(new RuntimeException('Not found.'));

        $this->expectException(NotFoundHttpException::class);

        $this->processor->process(null, $this->createMock(Operation::class), ['code' => 'BADCODE']);
    }

    #[Test]
    public function itThrowsUnprocessableWhenDomainViolation(): void
    {
        $device = $this->createMock(Device::class);
        $this->currentDeviceProvider->method('requireDevice')->willReturn($device);
        $this->inviteService->method('acceptInvite')->willThrowException(new DomainException('Revoked.'));

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->processor->process(null, $this->createMock(Operation::class), ['code' => 'ABCD1234']);
    }
}
