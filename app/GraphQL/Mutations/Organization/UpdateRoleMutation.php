<?php

namespace App\GraphQL\Mutations\Organization;

use App\GraphQL\Inputs\Organization\UpdateRoleInput;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Types\Organization\RoleType;
use App\Models\Role;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

class UpdateRoleMutation extends BaseMutation
{
    public $name = 'UpdateRole';

    public function type(): Type
    {
        return GraphQL::type(RoleType::$name);
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => Type::nonNull(GraphQL::type(UpdateRoleInput::$name)),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // /** @var SelectFields $fields */
        // $fields = $getSelectFields();
        // $select = $fields->getSelect();

        $input = $args['input'];
        $data = Role::whereGuardName(Employee::$guard_name)->findOrFail($input['id']);
        static::mustCan($data, 'update');
        $data->fill($input)->saveOrFail();

        if (isset($input['permissions'])) {
            $permissions = [];
            foreach ($input['permissions'] as $permission) {
                if (auth_user()->isRoot() || auth_employee()->checkPermissionTo($permission)) {
                    $permissions[] = $permission;
                }
            }
            $data->syncPermissions($permissions);
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $data;
    }
}
