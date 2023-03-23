<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\PriceAddedEvent;
use App\Event\PriceEventInterface;
use App\Event\PriceUpdatedEvent;
use App\Repository\PriceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PriceEventsSubscriber implements EventSubscriberInterface
{
    /**
     * @param PriceRepository $priceRepository Repo
     */
    public function __construct(private PriceRepository $priceRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            PriceAddedEvent::NAME => 'onPriceAddedOrUpdated',
            PriceUpdatedEvent::NAME => 'onPriceAddedOrUpdated',
        ];
    }

    /**
     * @param PriceEventInterface $event Event
     * @return void
     */
    public function onPriceAddedOrUpdated(PriceEventInterface $event)
    {
        $this->priceRepository->handlePriceChange($event->getPrice());
    }
}
