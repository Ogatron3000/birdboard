<?php

namespace App\Http\Controllers;

use App\Models\Project;

use App\Models\User;
use Illuminate\Http\Request;

class ProjectInviteController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate(
            ['email' => ['required', 'exists:users,email']],
            ['email.exists' => 'Email must be linked to valid Birdboard account.']
        );

        $user = User::where('email', request('email'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
