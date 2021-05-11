<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Traits\FieldHelperTrait;
use App\Traits\Permission\CanTrait;
use Rebing\GraphQL\Support\Mutation;

abstract class BaseMutation extends Mutation
{
    use CanTrait, FieldHelperTrait;
}
