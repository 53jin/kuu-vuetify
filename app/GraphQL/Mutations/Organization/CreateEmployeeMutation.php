<?php

namespace App\GraphQL\Mutations\Organization;

use App\GraphQL\Inputs\Organization\CreateEmployeeInput;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Types\Organization\EmployeeType;
use App\Models\User;
use App\Models\User\Employee;
use App\Models\User\VerificationCode;
use Closure;
use DB;
use Hash;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Mail;
use App\Mail\InviteRegister;
use Rebing\GraphQL\Support\SelectFields;

class CreateEmployeeMutation extends BaseMutation
{
    public $name = 'CreateEmployee';

    public function type(): Type
    {
        return GraphQL::type(EmployeeType::$name);
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => Type::nonNull(GraphQL::type(CreateEmployeeInput::$name)),
            ],
        ];
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::can(Employee::class, 'create');
    }


    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        // $select = $fields->getSelect();

        $input = collect($args['input']);

        try {
            DB::beginTransaction();
            $userData = $input->only([
                'first_name', 'last_name', 'email'
            ])->toArray();
            $userData['password'] = Hash::make($input->get('password'));
            $user = User::create($userData);

            $employee = Employee::create($input->only(['is_am', 'is_bd'])->merge([
                'related_user_id' => $user->id
            ])->toArray());

            if ($input->get('role_ids')) {
                $employee->syncRoles($input['role_ids']);
            }

            DB::commit();
            return Employee::with($fields->getRelations())->find($employee->id, $fields->getSelect());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
