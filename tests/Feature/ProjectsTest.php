<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_only_auth_user_can_create_project()
    {
        $project = Project::factory()->raw();

        $this->post(route('projects.store'), $project)->assertRedirect(route('login'));
    }

    public function test_user_can_create_project(): void
    {
        $this->actingAs(User::factory()->create());

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
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->raw(['title' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('title');
    }

    public function test_project_must_have_description(): void
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->raw(['description' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('description');
    }
}
