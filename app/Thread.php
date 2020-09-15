<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use Traits\RecordActivity;
    protected $fillable = ['channel_id', 'user_id', 'title', 'body'];
    protected $with = ['channel'];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function ($builder) {
            $builder->with('user');
        });
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));
        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function getDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())->exists();
    }

    public function subscribe()
    {
        $this->subscriptions()->create([
            'user_id' => auth()->id()
        ]);
    }

    public function unsubscribe()
    {
        $this->subscriptions()->where('user_id', auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }
}
