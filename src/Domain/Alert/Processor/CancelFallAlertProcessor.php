<?php

declare(strict_types=1);

namespace App\Domain\Alert\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Alert\Request\CancelFallAlertInputDTO;
use App\Domain\Alert\Response\FallAlertOutputDTO;
use App\Domain\Alert\Service\AlertIngestionServiceInterface;
use App\Infrastructure\Http\Security\DeviceContextInterface;

use function is_string;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<CancelFallAlertInputDTO, FallAlertOutputDTO>
 */
final readonly class CancelFallAlertProcessor implements ProcessorInterface
{
    public function __construct(
        private AlertIngestionServiceInterface $alertIngestionService,
        private DeviceContextInterface $currentDeviceProvider,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FallAlertOutputDTO
    {
        $clientAlertId = $uriVariables['clientAlertId'] ?? null;

        if (!is_string($clientAlertId) || '' === $clientAlertId) {
            throw new NotFoundHttpException('Alert not found.');
        }

        $alert = $this->alertIngestionService->cancelAlert(
            $this->currentDeviceProvider->requireDevice(),
            $clientAlertId,
        );

        if (!$alert instanceof \App\Entity\FallAlert) {
            throw new NotFoundHttpException('Alert not found.');
        }

        return FallAlertOutputDTO::fromEntity($alert);
    }
}
