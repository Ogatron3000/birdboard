<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_path(): void
    {
        $project = Project::factory()->create();

        $this->assertEquals('http://birdboard.test/projects/' . $project->id, $project->path());
    }

    public function test_belongs_to_user(): void
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->user);
    }
}
