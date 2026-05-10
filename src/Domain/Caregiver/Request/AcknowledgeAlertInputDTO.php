<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Caregiver\Processor\AcknowledgeAlertProcessor;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/fall-alerts/{id}/acknowledge',
        input: false,
        output: false,
        read: false,
        processor: AcknowledgeAlertProcessor::class,
    ),
])]
final class AcknowledgeAlertInputDTO
{
    // alertId comes from the URI variable {id}, body is intentionally empty
}
