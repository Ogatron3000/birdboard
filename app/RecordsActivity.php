<?php


namespace App;


use App\Models\Activity;
use Illuminate\Support\Arr;

trait RecordsActivity
{
    public $old = [];

    // this function allows us to get rid of Project and Task observers
    public static function bootRecordsActivity()
    {
        $recordableEvents = static::$recordableEvents ?? ['created', 'updated', 'deleted'];

        foreach ($recordableEvents as $description) {
            static::$description(function ($model) use ($description) {
                return $model->createActivity($description . '_' . strtolower(class_basename($model)));
            });

            if ($description === 'updated') {
                static::updating(function ($model) {
                    $model->old = $model->getOriginal();
                });
            }
        }
    }

    public function createActivity($description)
    {
        return $this->activity()->create([
            'description' => $description,
            'user_id' => ($this->project ?? $this)->user->id,
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
            'changes' => $this->recordChanges($description)
        ]);
    }

    protected function recordChanges($description)
    {
        if ($description === 'updated_project') {
            return [
                'old' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'new' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }
        return null;
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
