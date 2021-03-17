<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'completed'];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($task) {
            Activity::create(['description' => 'added task', 'project_id' => $task->project->id]);
        });

        // static::updated(function ($task) {
        //     if ( ! $task->completed) {
        //         Activity::create(['description' => 'completed task', 'project_id' => $task->project->id]);
        //     }
        // });
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        // moved from boot, which means now we have to hit the controller to test it
        $this->project->createActivity('completed task');
    }

    public function incomplete()
    {
        return $this->update(['completed' => false]);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }
}
