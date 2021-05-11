<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Queries\Traits\ModelQuery;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class EmployeeQuery extends BaseQuery
{
    use ModelQuery;

    public static $name = 'Employee';

    public function type(): Type
    {
        return Graphql::type(EmployeeType::$name);
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();

        return $this->find(Employee::query(), $args, $fields);
    }
}
