<?php

namespace App\Enums\Traits;

trait EnumToComaSeparated
{
    /**
     * Create coma separated string
     *
     * @param self ...$values
     * @return string
     */
    public static function join(self ...$values)
    {
        return implode(',', array_map(fn($val) => $val->value,$values));
    }
}
