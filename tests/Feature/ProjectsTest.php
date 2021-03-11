<?php

namespace Tests\Feature;

use App\Models\Project;
use Database\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_project(): void
    {
        $attributes = [
            'title' => $this->faker->title,
            'description' => $this->faker->sentence
        ];

        $this->post(route('projects.store'), $attributes)->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', $attributes);

        $this->get(route('projects.index'))->assertSee($attributes['title']);
    }

    public function test_user_can_view_project(): void
    {
        $project = Project::factory()->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_project_must_have_title(): void
    {
        $project = Project::factory()->raw(['title' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('title');
    }

    public function test_project_must_have_description(): void
    {
        $project = Project::factory()->raw(['description' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('description');
    }
}
