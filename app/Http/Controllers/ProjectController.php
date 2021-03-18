<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = auth()->user()->allProjects();

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
        $attributes = $this->validateInput();

        // $attributes['user_id'] = auth()->id();
        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        // if ($project->user()->isNot(auth()->user())) {
        //     return abort(403);
        // }

        $this->authorize('update', $project);

        $attributes = $this->validateInput();

        $project->update($attributes);

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect(route('projects.index'));
    }

    // we could extract this into UpdateProjectRequest
    protected function validateInput(): array
    {
        return request()->validate([
            'title'       => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }

}
