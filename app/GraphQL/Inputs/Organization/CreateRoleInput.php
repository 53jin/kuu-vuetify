<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\Organization;

use App\GraphQL\Inputs\BaseInput;
use GraphQL\Type\Definition\Type;

class CreateRoleInput extends BaseInput
{
    public static $name = 'CreateRoleInput';

    public function fields(): array
    {
        return array_merge(
            $this->nameField(true, 15, ['unique:'.config('permission.table_names.roles').',name'])
        );
    }
}
