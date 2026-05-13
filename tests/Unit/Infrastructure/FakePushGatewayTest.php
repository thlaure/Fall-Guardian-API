<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure;

use App\Infrastructure\Push\FakePushGateway;
use App\Infrastructure\Push\FakePushStore;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FakePushGatewayTest extends TestCase
{
    private FakePushStore $store;

    private FakePushGateway $gateway;

    protected function setUp(): void
    {
        $this->store = new FakePushStore(sys_get_temp_dir(), 'fall-guardian-fake-push-test');
        $this->store->clear();
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
        $result = $this->gateway->send('fcm-token', 'alert-id', '2025-01-01T00:00:00+00:00', 48.8, 2.3);

        $this->assertArrayHasKey('providerMessageId', $result);
        $this->assertStringStartsWith('fake-push-', $result['providerMessageId']);
        $this->assertSame('sent', $result['status']);

        $entries = $this->store->all();
        $this->assertCount(1, $entries);
        $this->assertSame($result['providerMessageId'], $entries[0]['providerMessageId']);
        $this->assertSame('fcm-token', $entries[0]['fcmToken']);
        $this->assertSame('alert-id', $entries[0]['alertId']);
        $this->assertSame('48.8', $entries[0]['latitude']);
        $this->assertSame('2.3', $entries[0]['longitude']);
    }
}
