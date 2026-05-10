<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Caregiver\Response\CreateInviteOutputDTO;
use App\Domain\Caregiver\Service\InviteService;
use App\Infrastructure\Http\Security\CurrentDeviceProvider;
use DomainException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @implements ProcessorInterface<CreateInviteOutputDTO, CreateInviteOutputDTO>
 */
final readonly class CreateInviteProcessor implements ProcessorInterface
{
    public function __construct(
        private InviteService $inviteService,
        private CurrentDeviceProvider $currentDeviceProvider,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CreateInviteOutputDTO
    {
        try {
            $invite = $this->inviteService->createInvite(
                $this->currentDeviceProvider->requireDevice(),
            );
        } catch (DomainException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage(), $e);
        }

        return CreateInviteOutputDTO::fromInviteData($invite->getCode(), $invite->getExpiresAt());
    }
}
