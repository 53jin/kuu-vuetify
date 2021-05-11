<?php

namespace App\GraphQL\Mutations\User;

use App\GraphQL\Inputs\User\UserInput;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Types\UserType;
use App\Models\User;
use Auth;
use Closure;
use GraphQL;
use Hash;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UpdateUserMutation extends BaseMutation
{
    public $name = 'UpdateUser';

    public function type(): Type
    {
        return GraphQL::type(UserType::$name);
    }

    public function args(): array
    {
        return [
            'data' => [
                'type' => GraphQL::type(UserInput::$name),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();

        $user = User::query()->select($select)->find(Auth::id());
        if (!$user) {
            throw new NotFoundResourceException('not found');
        }
        if( !empty($args['data']['password'])){
            $args['data']['password'] = Hash::make($args['data']['password']);
        }

        $user->fill($args['data'])->saveOrFail();

        return $user;
    }
}
