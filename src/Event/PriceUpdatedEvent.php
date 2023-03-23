<?php
declare(strict_types=1);

namespace App\Event;

use App\Entity\Price;
use Symfony\Contracts\EventDispatcher\Event;

final class PriceUpdatedEvent extends Event implements PriceEventInterface
{
    public const NAME = 'price.updated';

    /**
     * @param Price $price Price
     */
    public function __construct(private Price $price)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): Price
    {
        return $this->price;
    }
}
