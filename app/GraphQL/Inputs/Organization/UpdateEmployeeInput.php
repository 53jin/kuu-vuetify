<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\Organization;

use App\GraphQL\Inputs\BaseInput;
use App\Models\User\Employee;
use GraphQL\Type\Definition\Type;

class UpdateEmployeeInput extends BaseInput
{
    public static $name = 'UpdateEmployeeInput';

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            [
                'first_name' => [
                    'type' => Type::string(),
                    'rules' => ['nullable', 'string', 'min:1', 'max:25'],
                ],
                'last_name' => [
                    'type' => Type::string(),
                    'rules' => ['nullable', 'string', 'min:1', 'max:25'],
                ],
                'email' => [
                    'type' => Type::string(),
                    'rules' => static function(array $args = []) {
                        $id = Employee::findOrFail(data_get($args, 'id'))->related_user_id;
                        return ['nullable', 'string', 'email', 'min:4', 'max:80', 'unique:users,email,'.$id];
                    },
                ],
                'password' => [
                    'type' => Type::string(),
                    'rules' => ['nullable', 'string', 'min:8'],
                ],
                'status' => [
                    'type' => Type::boolean(),
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
