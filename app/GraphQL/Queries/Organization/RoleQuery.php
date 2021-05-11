<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Queries\Traits\ModelQuery;
use App\GraphQL\Types\Organization\RoleType;
use App\Models\Role;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class RoleQuery extends BaseQuery
{
    use ModelQuery;

    public static $name = 'Role';

    public function type(): Type
    {
        return Graphql::type(RoleType::$name);
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();

        return $this->find(Role::whereGuardName(Employee::$guard_name), $args, $fields);
    }
}
