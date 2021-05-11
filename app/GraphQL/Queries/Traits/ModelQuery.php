<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Traits;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;

trait ModelQuery
{
    protected $primaryKey = 'id';

    protected function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    protected function getPrimaryKeyType()
    {
        return Type::nonNull(Type::int());
    }

    public function args(): array
    {
        return array_merge([
            $this->getPrimaryKey() => [
                'type' => $this->getPrimaryKeyType(),
            ],
        ], $this->addArgs());
    }

    protected function addArgs(): array
    {
        return [];
    }

    protected function find($query, array $args, SelectFields $fields)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */

        $data = $query->with($fields->getRelations())
                     ->findOrFail($args[$this->getPrimaryKey()] ?? null, $fields->getSelect());
        self::mustCan($data, 'read');
        return $data;
    }
}
