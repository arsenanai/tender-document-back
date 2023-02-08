<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    public function testAdminExistance()
    {
        $object = User::where('name', 'Admin')
            ->where('email', env('ADMIN_EMAIL'))
            ->firstOrFail();
        $this->assertTrue($object !== null);
    }

    public function testAdminPasswordIsCorrect()
    {
        $object = User::where('name', 'Admin')
            ->where('email', env('ADMIN_EMAIL'))
            ->firstOrFail();
        $this->assertTrue(Hash::check(env('ADMIN_INITIAL_PASSWORD'), $object->password));
    }
}
