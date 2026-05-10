<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure;

use App\Infrastructure\Push\DelegatingPushGateway;
use App\Infrastructure\Push\FakePushGateway;
use App\Infrastructure\Push\FcmPushGateway;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DelegatingPushGatewayTest extends TestCase
{
    private FcmPushGateway&MockObject $fcmGateway;

    private FakePushGateway&MockObject $fakeGateway;

    protected function setUp(): void
    {
        $this->fcmGateway = $this->createMock(FcmPushGateway::class);
        $this->fakeGateway = $this->createMock(FakePushGateway::class);
    }

    #[Test]
    public function itDelegatesToFakeGateway(): void
    {
        $this->fakeGateway->method('getProviderName')->willReturn('fake');
        $this->fakeGateway->method('send')->willReturn(['providerMessageId' => 'fake-001', 'status' => 'sent']);

        $gateway = new DelegatingPushGateway('fake', $this->fcmGateway, $this->fakeGateway);

        $this->assertSame('fake', $gateway->getProviderName());
        $result = $gateway->send('token', 'alert-id', '2025-01-01T00:00:00+00:00', null, null);
        $this->assertSame('fake-001', $result['providerMessageId']);
    }

    #[Test]
    public function itDelegatesToFcmGateway(): void
    {
        $this->fcmGateway->method('getProviderName')->willReturn('fcm');
        $this->fcmGateway->method('send')->willReturn(['providerMessageId' => 'fcm-001', 'status' => 'sent']);

        $gateway = new DelegatingPushGateway('fcm', $this->fcmGateway, $this->fakeGateway);

        $this->assertSame('fcm', $gateway->getProviderName());
    }

    #[Test]
    public function itThrowsForUnknownProvider(): void
    {
        $gateway = new DelegatingPushGateway('unknown', $this->fcmGateway, $this->fakeGateway);

        $this->expectException(InvalidArgumentException::class);

        $gateway->send('token', 'alert-id', '2025-01-01T00:00:00+00:00', null, null);
    }
}
