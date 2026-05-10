<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure;

use App\Infrastructure\Push\FakePushGateway;
use App\Infrastructure\Push\FakePushStore;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FakePushGatewayTest extends TestCase
{
    private FakePushStore&MockObject $store;

    private FakePushGateway $gateway;

    protected function setUp(): void
    {
        $this->store = $this->createMock(FakePushStore::class);
        $this->gateway = new FakePushGateway($this->store);
    }

    #[Test]
    public function itReturnsProviderName(): void
    {
        $this->assertSame('fake', $this->gateway->getProviderName());
    }

    #[Test]
    public function itAppendsToStoreAndReturnsProviderMessageId(): void
    {
        $this->store->expects($this->once())->method('append');

        $result = $this->gateway->send('fcm-token', 'alert-id', '2025-01-01T00:00:00+00:00', 48.8, 2.3);

        $this->assertArrayHasKey('providerMessageId', $result);
        $this->assertStringStartsWith('fake-push-', $result['providerMessageId']);
        $this->assertSame('sent', $result['status']);
    }
}
