<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = ['body', 'completed'];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];

    public static $recordableEvents = ['created', 'deleted'];

    // protected static function boot()
    // {
    //     parent::boot();
    //
    //     static::created(function ($task) {
    //         return $task->project->createActivity('added task');
    //     });
    //
    //     // static::updated(function ($task) {
    //     //     if ( ! $task->completed) {
    //     //         Activity::create(['description' => 'completed task', 'project_id' => $task->project->id]);
    //     //     }
    //     // });
    //
    //     static::deleted(function ($task) {
    //         return $task->project->createActivity('deleted task');
    //     });
    // }

    public function complete()
    {
        $this->update(['completed' => true]);

        // moved from boot, which means now we have to hit the controller to test it
        $this->createActivity('completed_task');
    }

    public function uncomplete()
    {
        $this->update(['completed' => false]);

        $this->createActivity('uncompleted_task');
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
