<?php

namespace App\Tests\Twig\Runtime;

use App\Twig\Runtime\UserDisplayExtensionRuntime;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserDisplayExtensionRuntimeTest extends TestCase
{
    private $userDisplayExtensionRuntime;

    protected function setUp(): void
    {
        $this->userDisplayExtensionRuntime = new UserDisplayExtensionRuntime();
    }

    public function testEmptyUserDisplay(): void
    {
        //Créez des objets factices pour les tests.
        $userEmpty = new User();
        //Fournir les objets utilisateur aux méthodes et vérifier que les sorties correspondent à vos attentes.
        $this->assertEquals("", $this->userDisplayExtensionRuntime->displayUser($userEmpty));
    }

    public function testDisplayEmptyFirstName() : void {
        $userEmptyFirstName = new User();
        $userEmptyFirstName->setEmail("test@example.com");
        $this->assertEquals("test@example.com", $this->userDisplayExtensionRuntime->displayUser($userEmptyFirstName));

    }

    public function testDisplayWithCompanyName() : void {
        $userWithCompanyName = new User();
        $userWithCompanyName->setCompanyName("My Company");
        $userWithCompanyName->setFirstName('FirstName');
        $userWithCompanyName->setLastName('LastName');

        $this->assertEquals("My Company - FirstName LastName", $this->userDisplayExtensionRuntime->displayUser($userWithCompanyName));

    }

    public function testDisplayWithoutCompany() : void {
        $userWithoutCompany = new User();
        $userWithoutCompany->setFirstName('FirstName');
        $userWithoutCompany->setLastName('LastName');

        $this->assertEquals("FirstName LastName", $this->userDisplayExtensionRuntime->displayUser($userWithoutCompany));
    }

}
