<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'subjectable_id', 'subjectable_type', 'type'];

    public function subjectable()
    {
        return $this->morphTo();
    }

    public static function feed($user)
    {
        return static::where('user_id', $user->id)->latest()->get()->groupBy(function ($activities) {
            return $activities->created_at->format('d-m-y');
        });
    }
}
