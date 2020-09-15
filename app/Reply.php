<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reply extends Model
{
    use Traits\Favorable, Traits\RecordActivity, Traits\Authorized;
    protected $fillable = ['user_id', 'thread_id', 'body'];
    protected $with = ['user', 'favorites'];
    protected $appends = [
        'favoritesCount', 'isFavorite', 'isAuth',
        'canUpdate', 'canDelete', 'date'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function getDateAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : null;
    }

    public function getCanUpdateAttribute()
    {
        if (Auth::check()) {
            return $this->user->id == auth()->id();
        }
        return false;
    }

    public function getCanDeleteAttribute()
    {
        if (Auth::check()) {
            return $this->user->id == auth()->id() ||
                $this->thread->user->id == auth()->id();
        }
        return false;
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profile/$1">$0</a>', $body);
    }
}
