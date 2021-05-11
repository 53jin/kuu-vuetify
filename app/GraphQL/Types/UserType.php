<?php

namespace App\GraphQL\Types;

use App\GraphQL\Privacies\IsRootPrivacy;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UserType extends BaseType
{
    public static $name = 'User';

    public static $model = User::class;

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            $this->statusField(),
            $this->nameField(),
            [
                'email' => [
                    'type' => Type::string(),
                    'resolve' => static function($root, $args) {
                        return strtolower($root->email);
                    }
                ],
                'first_name' => [
                    'type' => Type::string(),
                ],
                'last_name' => [
                    'type' => Type::string(),
                ],
                'im_type' => [
                    'type' => Type::int(),
                ],
                'im_contact' => [
                    'type' => Type::string(),
                ],
                'email_verified_at' => [
                    'type' => Type::string(),
                ],
                'is_owner' => [
                    'type' => Type::boolean(),
                    'selectable' => false,
                    'privacy' => IsRootPrivacy::class
                ],
                'employee' => [
                    'type' => GraphQL::type(EmployeeType::$name),
                ],
            ]
        );
    }
}
