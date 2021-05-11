<?php

namespace App\GraphQL\Types\Organization;

use App\GraphQL\Types\BaseType;
use App\Models\User\Employee;
use GraphQL\Type\Definition\Type;
use GraphQL;

class EmployeeType extends BaseType
{
    public static $name = 'Employee';

    public static $model = Employee::class;

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            $this->userIdField(),
            $this->relatedUserIdField(),
            $this->createField('is_am', Type::boolean()),
            $this->createField('is_bd', Type::boolean()),
            $this->userField(),
            $this->relatedUserField(),
            [
                'permissions' => [
                    'type' => Type::listOf(Type::string()),
                    'selectable' => false,
                    'alias' => 'permission_names',
                ],
                'roles' => [
                    'type' => Type::listOf(GraphQL::type(RoleType::$name)),
                ],
                'role_ids' => [
                    'type' => Type::listOf(Type::int()),
                    'selectable' => false,
                ],
            ],
            $this->timestampField()
        );
    }
}
