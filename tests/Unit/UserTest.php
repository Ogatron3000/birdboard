<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    public function test_has_projectsInvitedTo()
    {
        $clark = $this->signIn();
        $clarkProject = ProjectFactory::ownedBy($clark)->create();

        $bruce = User::factory()->create();
        $barry = User::factory()->create();

        $bruceProject = ProjectFactory::ownedBy($bruce)->create();
        $bruceProject->invite($barry);

        $this->assertCount(1, $clark->allProjects());
        $this->assertTrue($clark->allProjects()->contains($clarkProject));

        $bruceProject->invite($clark);

        $this->assertTrue($clark->allProjects()->contains($bruceProject));
        $this->assertCount(2, $clark->allProjects());
    }

}
