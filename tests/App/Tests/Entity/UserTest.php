<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Survey;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetId(): void
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testEmail(): void
    {
        $user = new User();
        $user->setEmail('user@test.com');
        $this->assertEquals('user@test.com', $user->getEmail());
    }

    // Continue testing other "get" and "set" methods...

    public function testGetRoles(): void
    {
        $user = new User();
        $this->assertContains(User::ROLE_USER, $user->getRoles());
    }

    public function testGetPassword(): void
    {
        $user = new User();
        $user->setPassword('mypassword');
        $this->assertEquals('mypassword', $user->getPassword());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        // Test that erasing credentials does not throw an error.
        $user->eraseCredentials();
        // Or you could also explicitly assert that nothing happens, even though this does not make much sense
        $this->assertNull($user->eraseCredentials());
    }

    // ... continue with rest public methods of the User class ...

    public function testAddRemoveSurvey(): void
    {
        $user    = new User();
        $survey  = new Survey();

        $this->assertCount(0, $user->getSurveys());

        $user->addSurvey($survey);
        $this->assertCount(1, $user->getSurveys());
        $this->assertSame($survey, $user->getSurveys()[0]);

        $user->removeSurvey($survey);
        $this->assertCount(0, $user->getSurveys());
    }
}