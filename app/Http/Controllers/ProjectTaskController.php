<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{

    public function store(Project $project)
    {
        // if ($project->user()->isNot(auth()->user())) {
        //     return abort(403);
        // }

        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        // if ($task->project->user()->isNot(auth()->user())) {
        //     return abort(403);
        // }

        // $task->project instead of $project!
        // or restructure routes to /tasks/{task} - dedicated tasks route
        $this->authorize('update', $task->project);

        $task->update(request()->validate(['body' => 'required']));

        request('completed') ? $task->complete() : $task->uncomplete();

        return redirect($project->path());
    }

}
