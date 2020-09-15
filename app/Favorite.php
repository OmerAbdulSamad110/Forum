<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use Traits\RecordActivity;
    protected $fillable = ['user_id', 'favorable_id', 'favorable_type'];

    public function favorable()
    {
        return $this->morphTo();
    }
}
