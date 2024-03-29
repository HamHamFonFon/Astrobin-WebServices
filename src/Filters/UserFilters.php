<?php

declare(strict_types=1);

namespace AstrobinWs\Filters;

/**
 * Class UserFilters
 * @package AstrobinWs\Filters
 */
enum UserFilters: string
{
    use EnumToArray;

    case USERNAME_FILTER = 'username';
}
