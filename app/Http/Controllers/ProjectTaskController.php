<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{

    public function store(Project $project)
    {
        if ($project->user()->isNot(auth()->user())) {
            return abort(403);
        }

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        if ($project->user()->isNot(auth()->user())) {
            return abort(403);
        }

        request()->validate(['body' => 'required']);

        $task->update(['body'      => request()->get('body'),
                       'completed' => request()->has('completed'),
        ]);

        return redirect($project->path());
    }

}
