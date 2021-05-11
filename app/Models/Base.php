<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
abstract class Base extends Model
{
    protected $connection = 'mysql';
}
