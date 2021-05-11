<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\Role;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class AllEmployeesQuery extends BaseQuery
{
    public static $name = 'AllEmployees';

    public function type(): Type
    {
        return Type::listOf(GraphQL::type(EmployeeType::$name));
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::canAny(Employee::class, 'list');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();

        return Employee::query()
            ->with($fields->getRelations())
            ->select($fields->getSelect())
            ->get();
    }
}
