<?php

namespace App\Models\Traits;

use App\Models\User;

/**
 * @property User $related_user
 */
trait BelongRelatedUserTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function related_user()
    {
        return $this->belongsTo(User::class, 'related_user_id', 'id');
    }
}
