<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    // GUEST

    public function test_guest_cannot_manipulate_projects(): void
    {
        $project = $this->createProject();

        $this->post(route('projects.store'), array($project))->assertRedirect(route('login'));
        $this->get(route('projects.index'))->assertRedirect(route('login'));
        $this->get($project->path())->assertRedirect(route('login'));
        $this->get($project->path() . '/edit')->assertRedirect(route('login'));
        $this->get(route('projects.create'))->assertRedirect(route('login'));
        $this->patch($project->path(), [])->assertRedirect('login');
        $this->delete($project->path())->assertRedirect('login');
    }


    // AUTHENTICATED USER

    public function test_user_can_create_project(): void
    {
        $this->signIn();

        $this->get(route('projects.create'))->assertStatus(200);

        $attributes = [
            'title' => $this->faker->title,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->text
        ];

        $response = $this->post(route('projects.store'), $attributes);

        $project = Project::where($attributes)->get()->first();

        $response->assertRedirect($project->path());

        // $this->assertDatabaseHas('projects', $attributes);

        $this->get(route('projects.index'))->assertSee($attributes['title']);
    }

    public function test_user_can_update_project(): void
    {
        // $this->signIn();
        //
        // $project = $this->createProject('auth_user');

        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get($project->path() . '/edit')->assertOk();

        $this->patch($project->path(), $updated = [
            'title'       => 'brand new title',
            'description' => 'new desc yo',
            'notes'       => 'new notes, right here!',
        ]);

        $this->assertDatabaseHas('projects', $updated);
    }

    public function test_unauthorized_user_cannot_delete_project()
    {
        $project = ProjectFactory::create();

        $this->signIn();

        $this->delete($project->path())->assertStatus(403);
    }

    public function test_user_can_delete_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete($project->path());

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function test_user_can_update_project_notes(): void
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch($project->path(), $updated = [
            'notes' => 'new notes, right here!',
        ]);

        $this->assertDatabaseHas('projects', $updated);
    }

    public function test_user_cannot_update_project_of_other_user(): void
    {
        $this->signIn();

        $project = $this->createProject();

        $this->get($project->path() . '/edit')->assertStatus(403);

        $this->patch($project->path(), $updated = [
            'title'       => 'brand new title',
            'description' => 'new desc yo',
            'notes'       => 'new notes, right here!',
        ])->assertStatus(403);

        $this->assertDatabaseMissing('projects', $updated);
    }

    public function test_user_can_view_their_projects(): void
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $otherProject = $this->createProject();

        $this->get(route('projects.index'))
            ->assertSee($project->title)
            ->assertDontSee($otherProject->title);
    }

    public function test_user_can_view_projects_he_is_invited_to(): void
    {
        $project = ProjectFactory::create();

        $user = $this->signIn();

        $this->get(route('projects.index'))
            ->assertDontSee($project->title);

        $project->invite($user);

        $this->get(route('projects.index'))
            ->assertSee($project->title);
    }

    public function test_user_can_view_their_single_project(): void
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_user_cannot_view_single_project_of_other_user(): void
    {
        $this->signIn();

        $project = $this->createProject();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_project_must_have_title(): void
    {
        $this->signIn();

        $project = $this->createRawProject(['title' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('title');
    }

    public function test_project_must_have_description(): void
    {
        $this->signIn();

        $project = $this->createRawProject(['description' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('description');
    }
}
