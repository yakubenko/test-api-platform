<?php
declare(strict_types=1);

namespace App\Event;

use App\Entity\Price;

interface PriceEventInterface
{
    /**
     * Get the value of price
     *
     * @return Price
     */
    public function getPrice(): Price;
}
