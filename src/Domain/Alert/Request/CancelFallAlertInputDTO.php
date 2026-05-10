<?php

declare(strict_types=1);

namespace App\Domain\Alert\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Alert\Processor\CancelFallAlertProcessor;
use App\Domain\Alert\Response\FallAlertOutputDTO;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/fall-alerts/{clientAlertId}/cancel',
        output: FallAlertOutputDTO::class,
        read: false,
        deserialize: false,
        processor: CancelFallAlertProcessor::class,
    ),
])]
final class CancelFallAlertInputDTO
{
}
