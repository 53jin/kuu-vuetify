<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;

class AppType extends BaseType
{
    public static $name = 'App';

    public function fields(): array
    {
        return array_merge(
            $this->nameField(),
            [
                'logo_image_url' => [
                    'type' => Type::string(),
                ]
            ]
        );
    }
}
