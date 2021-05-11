<?php

namespace App\GraphQL\Types;

use App\GraphQL\Traits\FieldHelperTrait;
use App\Traits\Permission\CanTrait;
use Rebing\GraphQL\Support\Type as GraphQLType;

abstract class BaseType extends GraphQLType
{
    use FieldHelperTrait;
    use CanTrait;

    public static $name = '';

    public static $model = '';

    public function attributes(): array
    {
        $ret = [];
        if (static::$name) {
            $ret['name'] = static::$name;
        }
        if (static::$model) {
            $ret['model'] = static::$model;
        }

        return $ret;
    }
}
