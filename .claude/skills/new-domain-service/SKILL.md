---
name: new-domain-service
description: Use this skill whenever the user asks to create a domain service, extract business logic from a handler, isolate a domain rule, or mentions creating a new Service class inside a domain. Also trigger when the user says things like "move this logic to a service", "add a service for this rule", or asks how to reuse handler logic across multiple handlers.
version: 1.0.0
argument-hint: "[Domain] [RuleName]"
allowed-tools: Read, Glob, Grep, Edit, Write, Bash
---

Available domains: !`ls src/Domain/`


# New Domain Service

Domain services encapsulate a single domain rule that is too complex to live inline in a handler, or that needs to be reused across multiple handlers. The handler remains the orchestrator — the service is a named, testable piece of domain logic.

## Location

```
src/Domain/{Feature}/Service/{Rule}Service.php
tests/Unit/Domain/{Feature}/Service/{Rule}ServiceTest.php
```

## Rules

- **No infrastructure dependencies** — receive already-resolved values as parameters. No repository calls, no entity managers, no HTTP clients.
- **No Port calls** — services receive values, not entities fetched from repositories.
- **Single responsibility** — one rule, one class.
- **Descriptive name** — verb+noun describing the rule: `AlertEscalationResolver`, `PushTokenValidator`. Never generic suffixes.
- **`final readonly`** — mark the class `final readonly` unless there is a concrete reason not to.
- **Stateless** — no mutable state; all inputs come through method parameters.

## Steps

1. Look for an existing service in the same domain to absorb naming and style.
2. Create the service class following the pattern below.
3. Create the unit test — no mocks needed for a stateless service; pass concrete values and assert the output.
4. Run `make lint && make analyse && make rector` — fix any errors before reporting done.

## Service Class Pattern

```php
<?php

declare(strict_types=1);

namespace App\Domain\Alert\Service;

final readonly class AlertEscalationResolver
{
    public function shouldEscalate(string $status, \DateTimeInterface $detectedAt): bool
    {
        // pure domain rule — no I/O, no side effects
    }
}
```

## Unit Test Pattern

```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Alert\Service;

use App\Domain\Alert\Service\AlertEscalationResolver;
use PHPUnit\Framework\TestCase;

final class AlertEscalationResolverTest extends TestCase
{
    private AlertEscalationResolver $service;

    protected function setUp(): void
    {
        $this->service = new AlertEscalationResolver();
    }

    public function testShouldEscalateWithActiveStatusReturnsTrue(): void
    {
        $result = $this->service->shouldEscalate('active', new \DateTimeImmutable('-5 minutes'));

        $this->assertTrue($result);
    }

    public function testShouldEscalateWithCancelledStatusReturnsFalse(): void
    {
        $result = $this->service->shouldEscalate('cancelled', new \DateTimeImmutable());

        $this->assertFalse($result);
    }
}
```
