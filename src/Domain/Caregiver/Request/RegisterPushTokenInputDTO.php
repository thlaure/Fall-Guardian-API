<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Caregiver\Processor\RegisterPushTokenProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/caregiver/push-token',
        output: false,
        read: false,
        processor: RegisterPushTokenProcessor::class,
    ),
])]
final class RegisterPushTokenInputDTO
{
    #[Assert\NotBlank]
    public string $fcmToken = '';
}
