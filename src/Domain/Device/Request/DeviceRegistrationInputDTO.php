<?php

declare(strict_types=1);

namespace App\Domain\Device\Request;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Domain\Device\Processor\DeviceRegistrationProcessor;
use App\Domain\Device\Response\DeviceRegistrationOutputDTO;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/api/v1/devices/register',
        output: DeviceRegistrationOutputDTO::class,
        read: false,
        processor: DeviceRegistrationProcessor::class,
    ),
])]
final class DeviceRegistrationInputDTO
{
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['ios', 'android'])]
    public string $platform = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    public string $appVersion = '';

    #[Assert\Choice(choices: ['protected_person', 'caregiver'])]
    public string $deviceType = 'protected_person';
}
