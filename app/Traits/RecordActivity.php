<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activities()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activities()->create([
            'user_id' => $this->user_id,
            'type' => $event . '_' . strtolower((new \ReflectionClass($this))->getShortName())
        ]);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subjectable');
    }
}
