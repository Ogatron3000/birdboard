<?php


namespace Tests\Setup;


use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    protected int $tasks = 0;

    protected User $user;

    public function withTasks($tasks): ProjectFactory
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function ownedBy(User $user): ProjectFactory
    {
        $this->user = $user;

        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create(['user_id' => $this->user ?? User::factory()]);

        Task::factory($this->tasks)->create(['project_id' => $project->id]);

        return $project;
    }
}
