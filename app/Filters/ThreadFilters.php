<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];
    public function by($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        return $this->builder->having('replies_count', '>', 0)->orderBy('replies_count', 'DESC');
    }

    public function unanswered()
    {
        return $this->builder->having('replies_count', 0);
    }
}
