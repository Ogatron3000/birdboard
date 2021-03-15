<?php

namespace Tests;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function createProject($user = 'guest'): Project
    {
        return Project::factory()->create($user === 'auth_user' ? ['user_id' => auth()->id()] : null);
    }

    protected function createRawProject($attributes = [])
    {
        return Project::factory()->raw($attributes);
    }
}
