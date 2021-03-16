<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_add_tasks()
    {
        $project = $this->createProject();

        $task = Task::factory()->raw(['body' => 'new task, coming through']);

        $this->post($project->path() . '/tasks', $task)->assertRedirect(route('login'));
    }

    public function test_user_cannot_add_tasks_to_not_his_project()
    {
        $this->signIn();

        $project = $this->createProject();

        $task = Task::factory()->raw(['body' => 'new task, coming through']);

        $this->post($project->path() . '/tasks', $task)->assertStatus(403);
    }

    public function test_user_can_add_tasks()
    {
        $this->signIn();

        $project = $this->createProject('auth_user');

        $this->post($project->path() . '/tasks', $task = ['body' => 'New task, coming through!']);

        $this->get($project->path())->assertSee($task);
    }

    public function test_user_can_update_tasks()
    {
        // $this->signIn();
        //
        // $project = $this->createProject('auth_user');
        //
        // $task = $project->addTask('Watch out, new task, coming through!');

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed']);

        $this->assertDatabaseHas('tasks', ['body' => 'changed']);
    }

    public function test_user_can_complete_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'not testing body', 'completed' => true]);

        $this->assertDatabaseHas('tasks', ['completed' => true]);
    }

    public function test_user_can_mark_tasks_as_incomplete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'not testing body', 'completed' => true]);

        $this->patch($project->tasks[0]->path(), ['body' => 'not testing body', 'completed' => false]);

        $this->assertDatabaseHas('tasks', ['completed' => false]);
    }

    public function test_user_cannot_update_tasks_of_other_user_project()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed', 'completed' => true])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed', 'completed' => true]);
    }

    public function test_project_task_must_have_body(): void
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $task = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }
}
