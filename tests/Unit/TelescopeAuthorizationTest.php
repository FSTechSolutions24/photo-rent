<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Telescope\Telescope;
use Tests\TestCase;

class TelescopeAuthorizationTest extends TestCase
{
    /** @test */
    public function a_guest_cannot_access_telescope()
    {
        $this->assertFalse(Telescope::check($this->requestFor()));
    }

    /** @test */
    public function a_non_admin_cannot_access_telescope()
    {
        $this->assertFalse(Telescope::check($this->requestFor('photographer')));
    }

    /** @test */
    public function an_admin_can_access_telescope()
    {
        $this->assertTrue(Telescope::check($this->requestFor('admin')));
    }

    private function requestFor(?string $userType = null): Request
    {
        $request = Request::create('/telescope');
        $user = $userType === null
            ? null
            : (new User())->forceFill(['type' => $userType]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $request;
    }
}
