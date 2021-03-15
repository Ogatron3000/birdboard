<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // if ($project->user()->isNot(auth()->user())) {
        //     return abort(403);
        // }

        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);

        $attributes['notes'] = request('notes');

        // $attributes['user_id'] = auth()->id();
        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function update(Project $project)
    {
        // if ($project->user()->isNot(auth()->user())) {
        //     return abort(403);
        // }

        $this->authorize('update', $project);

        $project->update(request(['notes']));

        return redirect($project->path());
    }

}
