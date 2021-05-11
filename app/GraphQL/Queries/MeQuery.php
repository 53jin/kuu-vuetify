<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Types\UserType;
use App\Models\User;
use Auth;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class MeQuery extends Query
{
    public static $name = 'Me';

    public function attributes(): array
    {
        return [
            'name' => static::$name,
        ];
    }

    public function type(): Type
    {
        return GraphQL::type(UserType::$name);
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return User::with($with)->select($select)->find(Auth::id());
    }
}
