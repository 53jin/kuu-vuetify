<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

abstract class BasicEnum extends Enum
{
    public static function getSelectArray(): array
    {
        $selectArray = [];
        foreach (static::asArray() as $text => $value) {
            $selectArray[] = [
                'text' => $text,
                'value' => $value,
            ];
        }

        return $selectArray;
    }
}
