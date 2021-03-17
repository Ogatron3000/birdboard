<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'notes'];

    public $old = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        // add with to solve N+1 problem
        return $this->hasMany(Activity::class)->with('subject')->latest();
    }

    public function createActivity($description)
    {
        return $this->activity()->create([
            'description' => $description,
            'changes' => $this->recordChanges($description)
        ]);
    }

    protected function recordChanges($description)
    {
        if ($description === 'updated') {
            return [
                'old' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'new' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }

        return null;
    }
}
