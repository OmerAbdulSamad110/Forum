<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Authorized
{
    public function getIsAuthAttribute()
    {
        return Auth::check() ? true : false;
    }
}
