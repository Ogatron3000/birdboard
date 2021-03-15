<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Task extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_project(): void
    {
        $project = $this->createProject();

        $task = $project->addTask('new task!');

        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function test_path(): void
    {
        $project = $this->createProject();

        $task = $project->addTask('new task!');

        $this->assertEquals('/projects/' . $project->id . '/tasks/' . $task->id, $task->path());
    }
}
