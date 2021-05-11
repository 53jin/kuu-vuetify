<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait GetPermissionNamesTrait
{
    public function getPermissionNamesAttribute(): Collection
    {
        return $this->getAllPermissions()->pluck('name');
    }
}
