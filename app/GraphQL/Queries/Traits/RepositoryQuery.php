<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Traits;

use App\GraphQL\Inputs\QueryInput;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL;

trait RepositoryQuery
{
    protected $defaultLimit = 25;

    protected $maxLimit = 200;

    protected $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
    ];

    /**
     * @return array
     */
    public function getFieldSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function setFieldSearchable($field)
    {
        $this->fieldSearchable = $field;
    }

    public function args(): array
    {
        return array_merge([
            'query' => [
                'type' => GraphQL::type(QueryInput::$name)
            ],
        ], $this->addArgs());
    }

    protected function addArgs(): array
    {
        return [];
    }

    public function paginate($query, array $args, SelectFields $fields, $isGrouping = false)
    {
        $queryInput = collect($args['query'] ?? []);
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $query->with($fields->getRelations())->select($fields->getSelect());
        $table = $query->getModel()->getTable();
        if (!empty($queryInput['q']) && ($fieldSearchable = $this->getFieldSearchable())) {
            $query->where(static function() use ($fieldSearchable, $table, $query, $queryInput) {
                foreach ($fieldSearchable as $field => $condition) {
                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = '=';
                    }

                    if (!Str::contains($field, '.')) {
                        $field = $table.'.'.$field;
                    }

                    $condition = strtolower(trim($condition));
                    $value = $condition === 'like' ? "%{$queryInput['q']}%" : $queryInput['q'];
                    $query = $query->orWhere($field, $condition, $value);
                }
            });
        }

        if (isset($queryInput['params'])) {
            $params = $queryInput['params'];
            if (isset($params['status']) && $params['status'] !== '') {
                $query = $query->where($table . '.status', $params['status']);
            }
        }

        foreach ($queryInput->get('sort', []) as $sortValue) {
            $sortArray = explode(':', trim($sortValue), 2);
            if (empty($sortArray)) {
                continue;
            }
            $field = $sortArray[0];
            if (!$isGrouping && !Str::contains($field, '.')) {
                $field = $table.'.'.$field;
            }
            $sortDesc = false;
            if (isset($sortArray['1'])) {
                $sortDesc = $sortArray[1] === 'desc';
            }
            $query = $query->orderBy($field, $sortDesc ? 'desc' : 'asc');
        }

        return $query->paginate(
            $queryInput->get('limit', $this->defaultLimit),
            $fields->getSelect(), 'page',
            $queryInput->get('page', 1)
        );
    }
}
