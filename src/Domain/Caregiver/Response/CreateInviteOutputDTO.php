<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Response;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Caregiver\Processor\CreateInviteProcessor;
use DateTimeImmutable;
use DateTimeInterface;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/invites',
        input: false,
        output: self::class,
        read: false,
        processor: CreateInviteProcessor::class,
    ),
])]
final class CreateInviteOutputDTO
{
    public string $code = '';

    public string $expiresAt = '';

    public static function fromInviteData(string $code, DateTimeImmutable $expiresAt): self
    {
        $output = new self();
        $output->code = $code;
        $output->expiresAt = $expiresAt->format(DateTimeInterface::ATOM);

        return $output;
    }
}
