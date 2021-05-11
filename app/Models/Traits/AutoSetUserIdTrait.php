<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Auth;

trait AutoSetUserIdTrait
{
    protected static function boot()
    {
        parent::boot();
        static::addAutoSetUserIdObserver();
    }

    protected static function addAutoSetUserIdObserver()
    {
        static::creating(static function (Model $model) {
            if (empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }
}
