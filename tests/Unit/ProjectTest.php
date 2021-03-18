<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_path(): void
    {
        $project = $this->createProject();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function test_belongs_to_user(): void
    {
        $project = $this->createProject();

        $this->assertInstanceOf(User::class, $project->user);
    }

    public function test_can_add_tasks(): void
    {
        $project = $this->createProject();

        $task = $project->addTask('new task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    public function test_has_many_tasks(): void
    {
        $project = $this->createProject();

        $project->addTask('new task');

        $tasks = $project->tasks()->first();

        $this->assertInstanceOf(Task::class, $tasks);
    }

    public function test_can_invite_users(): void
    {
        $project = $this->createProject();

        $project->invite($newUser = $this->signIn());

        $this->assertTrue($project->members->contains($newUser));
    }
}
