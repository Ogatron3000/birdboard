<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_tasks()
    {
        $this->signIn();

        $project = $this->createProject('auth_user');

        // or do it manually -- without addTask? Better?
        // reduced test dependency on unit test
        $task = $project->addTask('Watch out, new task, coming through!');

        $this->get($project->path())->assertSee($task->body);
    }

    public function test_project_task_must_have_body(): void
    {
        $this->signIn();

        $project = $this->createProject('auth_user');

        $task = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }
}
