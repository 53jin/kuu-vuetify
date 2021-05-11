<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\User;

use App\Enums\ImType;
use App\GraphQL\Inputs\BaseInput;
use GraphQL\Type\Definition\Type;

class UserInput extends BaseInput
{
    public static $name = 'UserInput';

    public function fields(): array
    {
        return [
            'first_name' => [
                'type' => Type::string(),
                'rules' => ['max:25'],
            ],
            'last_name' => [
                'type' => Type::string(),
                'rules' => ['max:25'],
            ],
            'password' => [
                'type' => Type::string(),
                'rules' => ['min:8'],
            ],
        ];
    }
}
