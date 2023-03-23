<?php
declare(strict_types=1);

namespace App\Enum;

trait EnumHelperTrait
{
    /**
     * Lowercase
     *
     * @return string
     */
    public function lowerVal(): string
    {
        return strtolower($this->name);
    }
}
