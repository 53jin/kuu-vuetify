<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Queries\Traits\RepositoryQuery;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

class EmployeesQuery extends BaseQuery
{
    use RepositoryQuery;

    public static $name = 'Employees';

    public function getFieldSearchable(): array
    {
        return [
            'related_user_id' => '=',
        ];
    }

    public function type(): Type
    {
        return GraphQL::paginate(EmployeeType::$name);
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::canAny(Employee::class, 'list');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $model = Employee::query();
        $queryInput = collect($args['query'] ?? []);
        $table = $model->getModel()->getTable();
        $model = $model->Join('users as related_user', static function ($join) use ($table) {
            return $join->on('related_user.id', $table . '.related_user_id');
        });

        if(isset($queryInput['params'])){
            $params = $queryInput['params'];
            if (isset($params['status']) && $params['status'] !== '') {

                $model = $model->where('related_user.status', $params['status']);
                unset($args['query']['params']['status']);
            }
        }

        return $this->paginate($model, $args, $fields);
    }
}
