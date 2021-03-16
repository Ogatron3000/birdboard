<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_project_generates_activity()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activities);
        $this->assertEquals('created', $project->activities[0]->description);
    }

    public function test_updating_project_generates_activity()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => 'updated title']);

        $this->assertCount(2, $project->activities);
        $this->assertEquals('updated', $project->activities[1]->description);
    }
}
