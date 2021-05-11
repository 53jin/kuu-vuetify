<?php

namespace App\GraphQL\Mutations\Organization;

use App\GraphQL\Mutations\BaseMutation;
use App\Mail\InviteRegister;
use App\Models\User\Employee;
use App\Models\User\VerificationCode;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Mail;
use Rebing\GraphQL\Support\SelectFields;

class SendInviteEmailMutation extends BaseMutation
{
    public $name = 'SendInviteEmail';

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return array_merge(
            $this->idField()
        );
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // /** @var SelectFields $fields */
        // $fields = $getSelectFields();
        // $select = $fields->getSelect();

        $data = Employee::findOrFail($args['id']);
        static::mustCan($data, 'update');

        if ($data->related_user->email_verified_at) {
            return false;
        }

        $email = $data->related_user->email;
        $verificationCode = VerificationCode::fromEmail($email, VerificationCode::TypeInvite);
        $verificationCode->sendCode();

        try {
            Mail::to($email)->send(new InviteRegister($verificationCode));
        } catch (\Exception $e) {
            $verificationCode->clearCode();
            throw $e;
        }

        return true;
    }
}
