<?php

namespace App\Models;

use App\Models\Traits\GetPermissionNamesTrait;
use App\Models\User\Employee;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    public const PermissionName = 'user';

    use Notifiable, HasRoles, GetPermissionNamesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'status', 'email', 'im_type', 'im_contact', 'password', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'bool', 'email_verified_at' => 'datetime',
    ];
    public function getIsOwnerAttribute(): bool
    {
        return $this->isRoot();
    }

    public function getRoleIdsAttribute(): array
    {
        return $this->roles->pluck('id')->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne | Employee
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'related_user_id', 'id');
    }
    public function isRoot(): bool
    {
        return $this->hasPermissionTo('root');
    }

    public function can($ability, $arguments = [])
    {
        return $this->isRoot() || parent::can($ability, $arguments);
    }
}
