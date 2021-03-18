<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_users_cannot_invite_users()
    {
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->post($project->path() . '/invitations', ['email' => $userToInvite->email])
            ->assertRedirect('login');
        $this->assertFalse($project->members->contains($userToInvite));

        $this->signIn();

        $this->post($project->path() . '/invitations', ['email' => $userToInvite->email])
            ->assertStatus(403);
        $this->assertFalse($project->members->contains($userToInvite));
    }

    public function test_members_cannot_invite_users()
    {
        $project = ProjectFactory::create();

        $member = $this->signIn();

        $project->invite($member);

        $userToInvite = User::factory()->create();

        $this->post($project->path() . '/invitations', ['email' => $userToInvite->email])
            ->assertStatus(403);
        $this->assertFalse($project->members->contains($userToInvite));
    }

    public function test_project_user_can_invite_users()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $userToInvite = User::factory()->create();

        $this->post($project->path() . '/invitations', ['email' => $userToInvite->email])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function test_mail_must_be_linked_to_valid_birdboard_account()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->post($project->path() . '/invitations', ['email' => 'totallyrealemail@turst.me'])
            ->assertSessionHasErrors('email', 'Email must be linked to valid Birdboard account.');
    }

    public function test_invited_memebers_can_update_project()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = $this->signIn());

        $this->post(route('projectTasks.store', $project), ['body' => 'hey']);

        $this->assertDatabaseHas('tasks', ['body' => 'hey']);
    }
}
