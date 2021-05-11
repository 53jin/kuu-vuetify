<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use App\GraphQL\Traits\FieldHelperTrait;
use Rebing\GraphQL\Support\InputType;

abstract class BaseInput extends InputType
{
    use FieldHelperTrait;

    public static $name = '';

    public function attributes(): array
    {
        return [
            'name' => static::$name,
        ];
    }
}
