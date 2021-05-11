<?php

namespace App\Models\User;

use App\Models\Base;
use App\Models\Traits\AutoSetUserIdTrait;
use App\Models\Traits\BelongRelatedUserTrait;
use App\Models\Traits\BelongUserTrait;
use App\Models\Traits\GetPermissionNamesTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User\Employee
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $related_user_id
 * @property int $is_am
 * @property int $is_bd
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\User $related_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereIsAm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereIsBd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereRelatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Employee whereUserId($value)
 * @mixin \Eloquent
 */
class Employee extends Base
{
    use AutoSetUserIdTrait;
    use BelongRelatedUserTrait;
    use BelongUserTrait;
    use HasRoles;
    use GetPermissionNamesTrait;

    public static string $guard_name = 'employee';

    public const PermissionName = 'employee';

    public $fillable = [
        'is_am', 'is_bd', 'related_user_id',
    ];

    public function getRoleIdsAttribute(): array
    {
        return $this->roles->pluck('id')->toArray();
    }

    /**
     * @param Model|string $model
     * @param string       $action
     * @param string       $ownerKey
     *
     * @return bool
     */
    public function authorize($model, $action, $ownerKey = 'user_id'): bool
    {
        if (!$model || !$action) {
            return false;
        }
        $fullAction = $model::PermissionName.'-'.$action;
        if ($this->checkPermissionTo($fullAction) || $this->checkPermissionTo($fullAction.'-all')) {
            return true;
        }

        if (!$this->checkPermissionTo($fullAction.'-own')) {
            return false;
        }

        return $model instanceof Model && !empty($model->{$ownerKey}) && $model->{$ownerKey} === $this->getKey();
    }

    /**
     * @param Model|string $model
     * @param string       $action
     *
     * @return bool
     */
    public function authorizeAny($model, $action): bool
    {
        if (!$model || !$action) {
            return false;
        }
        $fullAction = $model::PermissionName.'-'.$action;
        return $this->checkPermissionTo($fullAction)
            || $this->checkPermissionTo($fullAction.'-all')
            || $this->checkPermissionTo($fullAction.'-own');
    }
}
