<?php

namespace App\Enums\Traits;

/**
 * Trait for enum class
 * ___
 * for Enum::arrayCases()
 */
trait ArrayCases
{
    /**
     * Returns every cases inside enum as array
     *
     * @return string[] every cases inside enum
     */
    public static function arrayCases()
    {
        return array_column(static::cases(), 'value');
    }
}
