<?php

declare(strict_types=1);

namespace App\Domain\Alert\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Alert\Request\CreateFallAlertInputDTO;
use App\Domain\Alert\Response\FallAlertOutputDTO;
use App\Domain\Alert\Service\AlertIngestionService;
use App\Infrastructure\Http\Security\CurrentDeviceProvider;

use function assert;

use DateTimeImmutable;

/**
 * @implements ProcessorInterface<CreateFallAlertInputDTO, FallAlertOutputDTO>
 */
final readonly class CreateFallAlertProcessor implements ProcessorInterface
{
    public function __construct(
        private AlertIngestionService $alertIngestionService,
        private CurrentDeviceProvider $currentDeviceProvider,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FallAlertOutputDTO
    {
        assert($data instanceof CreateFallAlertInputDTO);

        $alert = $this->alertIngestionService->createAlert(
            $this->currentDeviceProvider->requireDevice(),
            $data->clientAlertId,
            $data->fallTimestamp ?? new DateTimeImmutable(),
            $data->locale,
            $data->latitude,
            $data->longitude,
        );

        return FallAlertOutputDTO::fromEntity($alert);
    }
}
