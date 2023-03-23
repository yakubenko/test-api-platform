<?php
declare(strict_types=1);

namespace App\Enum;

enum ApiOperationsEnum
{
    use EnumHelperTrait;

    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;
}
