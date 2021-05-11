<?php

namespace App\GraphQL\Mutations\Organization;

use App\GraphQL\Inputs\Organization\CreateRoleInput;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Types\Organization\RoleType;
use App\Models\Role;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

class CreateRoleMutation extends BaseMutation
{
    public $name = 'CreateRole';

    public function type(): Type
    {
        return GraphQL::type(RoleType::$name);
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => Type::nonNull(GraphQL::type(CreateRoleInput::$name)),
            ],
        ];
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::can(Role::class, 'create');
    }


    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // /** @var SelectFields $fields */
        // $fields = $getSelectFields();
        // $select = $fields->getSelect();

        return Role::firstOrCreate(collect($args['input'])->merge([
            'guard_name' => Employee::$guard_name,
        ])->toArray());
    }
}
