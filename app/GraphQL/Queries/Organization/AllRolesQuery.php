<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\Organization\RoleType;
use App\Models\Role;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class AllRolesQuery extends BaseQuery
{
    public static $name = 'AllRoles';

    public function type(): Type
    {
        return Type::listOf(GraphQL::type(RoleType::$name));
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::canAny(Role::class, 'list');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();

        return Role::whereGuardName(Employee::$guard_name)
                   ->with($fields->getRelations())
                   ->select($fields->getSelect())
                   ->get();
    }
}
