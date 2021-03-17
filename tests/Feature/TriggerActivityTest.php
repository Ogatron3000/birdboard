<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activities);
        $this->assertEquals('created', $project->activities[0]->description);
    }

    public function test_updating_project()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => 'updated title']);

        $this->assertCount(2, $project->activities);
        $this->assertEquals('updated', $project->activities[1]->description);
    }

    public function test_creating_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $project->addTask('new task');

        $this->assertCount(2, $project->activities);
        $this->assertEquals('created task', $project->activities[1]->description);
    }

    public function test_completing_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => true]);

        $this->assertCount(3, $project->activities);
        $this->assertEquals('completed task', $project->activities[2]->description);
    }

    public function test_uncompleting_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => true]);

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => false]);

        $project->fresh();

        $this->assertCount(4, $project->activities);
        $this->assertEquals('uncompleted task', $project->activities[3]->description);
    }

    public function test_deleting_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activities);
        $this->assertEquals('deleted task', $project->activities[2]->description);
    }
}
