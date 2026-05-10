<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Caregiver\Processor\AcceptInviteProcessor;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/invites/{code}/accept',
        input: false,
        output: false,
        read: false,
        processor: AcceptInviteProcessor::class,
    ),
])]
final class AcceptInviteInputDTO
{
    // code comes from the URI variable, not the body — body is intentionally empty
}
