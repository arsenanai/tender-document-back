<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function testAdminExistance()
    {
        $object = User::factory()->create();
        $this->assertTrue($object !== null);
        $object->delete();
    }

    public function testAdminPasswordIsCorrect()
    {
        $object = User::factory()->create();
        $this->assertTrue(Hash::check(env('ADMIN_INITIAL_PASSWORD', 'Entry_2023'), $object->password));
        $object->delete();
    }
}
