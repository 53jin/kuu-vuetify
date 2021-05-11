<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\Organization;

use App\GraphQL\Inputs\BaseInput;
use GraphQL\Type\Definition\Type;

class CreateEmployeeInput extends BaseInput
{
    public static $name = 'CreateEmployeeInput';

    public function fields(): array
    {
        return array_merge(
            [
                'first_name' => [
                    'type' => Type::nonNull(Type::string()),
                    'rules' => ['required', 'string', 'max:25'],
                ],
                'last_name' => [
                    'type' => Type::string(),
                    'rules' => ['nullable', 'string', 'max:25'],
                ],
                'password' => [
                    'type' => Type::string(),
                    'rules' => ['required', 'string', 'min:8'],
                ],
                'email' => [
                    'type' => Type::nonNull(Type::string()),
                    'rules' => ['required', 'string', 'email', 'max:80', 'unique:users,email'],
                ],
                'is_am' => [
                    'type' => Type::boolean(),
                ],
                'is_bd' => [
                    'type' => Type::boolean(),
                ],
                'role_ids' => [
                    'type' => Type::listOf(Type::int()),
                    'rules' => ['nullable', 'array'],
                ],
            ]
        );
    }
}
