<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Traits\FieldHelperTrait;
use App\Traits\Permission\CanTrait;
use Rebing\GraphQL\Support\Query;

abstract class BaseQuery extends Query
{
    use CanTrait;
    use FieldHelperTrait;

    public static $name = '';

    public function attributes(): array
    {
        return [
            'name' => static::$name,
        ];
    }
}
