<?php

namespace App\GraphQL\Types\Organization;

use App\GraphQL\Types\BaseType;
use App\Models\Permission;
use GraphQL\Type\Definition\Type;

class PermissionType extends BaseType
{
    public static $name = 'Permission';

    public static $model = Permission::class;

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            $this->nameField(),
            $this->timestampField()
        );
    }
}
