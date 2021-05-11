<?php

namespace App\Models\Traits;

use App\Models\User;

/**
 * @property User $user
 */
trait BelongUserTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
