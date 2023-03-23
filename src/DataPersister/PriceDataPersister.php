<?php
declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Price;
use App\Enum\ApiOperationsEnum;
use App\Event\PriceAddedEvent;
use App\Event\PriceUpdatedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class PriceDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @param ContextAwareDataPersisterInterface $decorated decorated
     * @param EventDispatcherInterface $dispatcher events
     */
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    /**
     * @inheritDoc
     */
    public function persist($data, array $context = [])
    {
        if (!$data instanceof Price) {
            return $this->decorated->persist($data, $context);
        }

        $result = $this->decorated->persist($data, $context);
        $this->fireEvents($data, $result, $context);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function remove($data, array $context = []): mixed
    {
        return $this->decorated->remove($data, $context);
    }

    /**
     * @param Price $data Price
     * @param mixed $result Operation result
     * @param array $context Operation context
     * @return void
     */
    private function fireEvents($data, $result, array $context = [])
    {
        $this->fireCollectionOperationEvents(
            $context['collection_operation_name'] ?? null,
            $data,
            $context
        );

        $this->fireItemOperationEvents(
            $context['item_operation_name'] ?? null,
            $data,
            $context
        );
    }

    /**
     * @param string $operation Operation
     * @param Price $data data
     * @param array $context context
     * @return mixed
     */
    protected function fireCollectionOperationEvents(?string $operation, Price $data, array $context): mixed
    {
        return match ($operation) {
            ApiOperationsEnum::POST->lowerVal() =>
                $this->dispatcher->dispatch(new PriceAddedEvent($data), PriceAddedEvent::NAME),
            default => null
        };
    }

    /**
     * @param string $operation Operation
     * @param Price $data data
     * @param array $context context
     * @return mixed
     */
    protected function fireItemOperationEvents(?string $operation, Price $data, array $context): mixed
    {
        return match ($operation) {
            ApiOperationsEnum::PUT->lowerVal() =>
                $this->dispatcher->dispatch(new PriceUpdatedEvent($data), PriceUpdatedEvent::NAME),
            default => null
        };
    }
}
