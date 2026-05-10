<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Response;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Domain\Caregiver\Provider\CaregiverAlertsProvider;
use App\Entity\FallAlert;
use DateTimeInterface;

#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/api/v1/caregiver/alerts',
        output: self::class,
        provider: CaregiverAlertsProvider::class,
    ),
])]
final readonly class CaregiverAlertOutputDTO
{
    public function __construct(
        public string $id,
        public string $status,
        public string $fallDetectedAt,
        public ?float $latitude,
        public ?float $longitude,
        public bool $acknowledged,
    ) {
    }

    public static function fromEntity(FallAlert $alert, bool $acknowledged = false): self
    {
        return new self(
            $alert->getId()->toRfc4122(),
            $alert->getStatus()->value,
            $alert->getFallDetectedAt()->format(DateTimeInterface::ATOM),
            $alert->getLatitude(),
            $alert->getLongitude(),
            $acknowledged,
        );
    }
}
