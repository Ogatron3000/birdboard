<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('created', $activity->description);

        $this->assertNull($activity->changes);
    }

    public function test_updating_project()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => 'updated title']);

        $this->assertCount(2, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('updated', $activity->description);

        $expected = [
            'old' => Arr::except(array_diff($project->old, $project->getAttributes()), 'updated_at'),
            'new' => Arr::except($project->getChanges(), 'updated_at'),
        ];

        $this->assertEquals($expected, $activity->changes);
    }

    public function test_creating_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $project->addTask('new task');

        $this->assertCount(2, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('created_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
        $this->assertEquals('new task', $activity->subject->body);
    }

    public function test_completing_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => true]);

        $this->assertCount(3, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('completed_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    }

    public function test_uncompleting_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => true]);

        $this->patch($project->tasks[0]->path(), ['body' => 'irrelevant', 'completed' => false]);

        $project->fresh();

        $this->assertCount(4, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('uncompleted_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    }

    public function test_deleting_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);

        $activity = $project->activity->last();

        $this->assertEquals('deleted_task', $activity->description);
        $this->assertNull($activity->subject);
    }
}
