<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

final class Price extends Constraint
{
    public $message = 'The value price is not valid.';
}
