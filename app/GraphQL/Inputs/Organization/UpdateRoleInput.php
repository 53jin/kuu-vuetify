<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\Organization;

use App\GraphQL\Inputs\BaseInput;
use GraphQL\Type\Definition\Type;

class UpdateRoleInput extends BaseInput
{
    public static $name = 'UpdateRoleInput';

    public function fields(): array
    {
        return array_merge(
            $this->idField(),
            $this->createField('name', Type::string(), static function(array $args = []) {
                $table = config('permission.table_names.roles');
                return [
                    'nullable', 'max:15',
                    'unique:'.$table.',name,'.data_get($args, 'id'),
                ];
            }),
            [
                'permissions' => [
                    'type' => Type::listOf(Type::string()),
                    'rules' => ['nullable', 'array'],
                ],
            ]
        );
    }
}
