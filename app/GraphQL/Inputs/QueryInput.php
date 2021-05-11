<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use GraphQL;

class QueryInput extends BaseInput
{
    public static $name = 'QueryInput';

    public function fields(): array
    {
        return [
            'limit' => [
                'type' => Type::int(),
                'rules' => ['numeric', 'min:1'],
            ],
            'page' => [
                'type' => Type::int(),
                'rules' => ['numeric', 'min:1'],
            ],
            'q' => [
                'type' => Type::string(),
            ],
            'sort' => [
                'type' => Type::listOf(Type::string()),
                'rules' => ['array', 'max:3'],
            ],
            'params' => [
                'type' => GraphQL::type('Any'),
            ],
        ];
    }
}
