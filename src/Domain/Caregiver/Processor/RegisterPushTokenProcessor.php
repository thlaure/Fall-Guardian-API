<?php

declare(strict_types=1);

namespace App\Domain\Caregiver\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Caregiver\Request\RegisterPushTokenInputDTO;
use App\Domain\Caregiver\Service\InviteService;
use App\Infrastructure\Http\Security\CurrentDeviceProvider;

use function assert;

use DomainException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @implements ProcessorInterface<RegisterPushTokenInputDTO, null>
 */
final readonly class RegisterPushTokenProcessor implements ProcessorInterface
{
    public function __construct(
        private InviteService $inviteService,
        private CurrentDeviceProvider $currentDeviceProvider,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        assert($data instanceof RegisterPushTokenInputDTO);

        try {
            $this->inviteService->registerPushToken(
                $this->currentDeviceProvider->requireDevice(),
                $data->fcmToken,
            );
        } catch (DomainException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage(), $e);
        }

        return null;
    }
}
