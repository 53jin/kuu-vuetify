<?php

namespace App\GraphQL\Types\Organization;

use App\GraphQL\Types\BaseType;
use App\Models\Role;
use GraphQL\Type\Definition\Type;
use GraphQL;

class RoleType extends BaseType
{
    public static $name = 'Role';

    public static $model = Role::class;

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            $this->nameField(),
            [
                'permissions' => [
                    'type' => Type::listOf(GraphQL::type(PermissionType::$name))
                ],
            ],
            $this->timestampField()
        );
    }
}
