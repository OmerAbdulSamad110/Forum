<?php

namespace App\Traits;

use App\Favorite;
use Illuminate\Database\Eloquent\Model;

trait Favorable
{
    protected static function bootFavorable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }
    public function favorite()
    {
        $attribue = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attribue)->exists()) {
            return $this->favorites()->create($attribue);
        }
    }

    public function unfavorite()
    {
        $attribue = ['user_id' => auth()->id()];
        if ($this->favorites()->where($attribue)->exists()) {
            return $this->favorites()->where($attribue)->get()->each->delete();
        }
    }

    public function getIsFavoriteAttribute()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }
}
