<?php

declare(strict_types=1);

namespace App\Domain\Alert\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Alert\Processor\CreateFallAlertProcessor;
use App\Domain\Alert\Response\FallAlertOutputDTO;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/fall-alerts',
        output: FallAlertOutputDTO::class,
        read: false,
        processor: CreateFallAlertProcessor::class,
    ),
])]
final class CreateFallAlertInputDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $clientAlertId = '';

    #[Assert\NotNull]
    public ?DateTimeImmutable $fallTimestamp = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $locale = 'en';

    public ?float $latitude = null;

    public ?float $longitude = null;
}
