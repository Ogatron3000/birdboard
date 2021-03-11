<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_path(): void
    {
        $project = Project::factory()->create();

        $this->assertEquals('http://birdboard.test/projects/' . $project->id, $project->path());
    }
}
