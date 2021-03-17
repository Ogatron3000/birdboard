<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TaskTest extends TestCase
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

    public function test_task_can_be_completed()
    {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_task_can_be_uncompleted()
    {
        $task = Task::factory()->create(['completed' => true]);

        $this->assertTrue($task->completed);

        $task->uncomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}
