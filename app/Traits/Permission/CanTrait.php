<?php

namespace App\Traits\Permission;

use App\Models\User;
use Auth;
use Rebing\GraphQL\Error\AuthorizationError;

trait CanTrait
{
    public static function can($model, $action, $ownerKey = 'user_id'): bool
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return false;
        }

        if ($user->isRoot()) {
            return true;
        }

        return $user->employee && $user->employee->authorize($model, $action, $ownerKey);
    }

    public static function canAny($model, $action): bool
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return false;
        }

        if ($user->isRoot()) {
            return true;
        }

        return  $user->employee && $user->employee->authorizeAny($model, $action);
    }

    public static function mustCan($model, $action, $ownerKey = 'user_id'): void
    {
        if (!static::can($model, $action, $ownerKey)) {
            throw new AuthorizationError('Unauthorized');
        }
    }

    public static function mustCanAny($model, $action): void
    {
        if (!static::canAny($model, $action)) {
            throw new AuthorizationError('Unauthorized');
        }
    }
}
