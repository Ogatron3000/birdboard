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

    public function test_project_can_invite_users()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = $this->signIn());

        $this->post(route('projectTasks.store', $project), ['body' => 'hey']);

        $this->assertDatabaseHas('tasks', ['body' => 'hey']);
    }
}
