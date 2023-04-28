<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp() :void
    {
        parent::setUp();
        $this->seed();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testAdminExistance()
    {
        $object = User::where('email', config('cnf.ADMIN_EMAIL'))->first();
        $this->assertTrue($object !== null);
        // $object->delete();
    }

    public function testAdminPasswordIsCorrect()
    {
        $object = User::where('email', config('cnf.ADMIN_EMAIL'))->first();
        $this->assertTrue(Hash::check(config('cnf.ADMIN_INITIAL_PASSWORD'), $object->password));
        //$object->delete();
    }
}
