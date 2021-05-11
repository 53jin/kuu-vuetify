<?php

namespace App\GraphQL\Mutations\Organization;

use App\GraphQL\Inputs\Organization\UpdateEmployeeInput;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\User\Employee;
use Closure;
use GraphQL;
use Hash;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

class UpdateEmployeeMutation extends BaseMutation
{
    public $name = 'UpdateEmployee';

    public function type(): Type
    {
        return GraphQL::type(EmployeeType::$name);
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => Type::nonNull(GraphQL::type(UpdateEmployeeInput::$name)),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        // $select = $fields->getSelect();

        $input = collect($args['input']);
        $data = Employee::findOrFail($input['id']);
        static::mustCan($data, 'update');

        $data->fill($input->only('is_am', 'is_bd')->toArray())->saveOrFail();

        $relatedUserInput = $input->only('first_name', 'last_name', 'status', 'email', 'role_ids');
        if ($relatedUserInput->isNotEmpty()) {
            $relatedUser = $data->related_user;
            if ($input->get('password')) {
                $relatedUserInput->merge(['password' => Hash::make($input->get('password'))]);
            }

            $relatedUser->fill($relatedUserInput->except('role_ids')->toArray())->saveOrFail();


            if ($relatedUserInput->get('role_ids') !== null) {
                $data->syncRoles($relatedUserInput->get('role_ids'));
            }
        }

        return Employee::with($fields->getRelations())->find($data->id, $fields->getSelect());
    }
}
