<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'register.email_in_use')]
class User implements
    UserInterface,
    PasswordAuthenticatedUserInterface
{

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;


    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $firstName;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $lastName;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $logo;

    #[ORM\Column(type:"datetime", nullable: true)]
    private $lastLogin;

    #[ORM\Column(type: 'boolean')]
    private $isAdmin = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $companyName;

    #[ORM\OneToMany(targetEntity: Survey::class, mappedBy: 'creator')]
    private Collection $surveys;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $firstLogin = true;

    #[ORM\Column]
    private ?bool $adminCanSee = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastAdminAccess = null;

    public function __construct()
    {
        $this->surveys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = User::ROLE_USER;

        if ($this->isAdmin()) {
            $roles[] = User::ROLE_ADMIN;
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRoles(string $role) : static {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     */
    public function setLastLogin($lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return Collection<int, Survey>
     */
    public function getSurveys(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(Survey $survey): static
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys->add($survey);
            $survey->setCreator($this);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): static
    {
        // set the owning side to null (unless already changed)
        if ($this->surveys->removeElement($survey) && $survey->getCreator() === $this) {
            $survey->setCreator(null);
        }

        return $this;
    }

    public function isAdminCanSee(): ?bool
    {
        return $this->adminCanSee;
    }

    public function setAdminCanSee(bool $adminCanSee): static
    {
        $this->adminCanSee = $adminCanSee;
        if ($adminCanSee) {
            $this->setLastAdminAccess(new \DateTime("now"));
        }

        return $this;
    }

    public function getLastAdminAccess(): ?\DateTimeInterface
    {
        return $this->lastAdminAccess;
    }

    public function setLastAdminAccess(?\DateTimeInterface $lastAdminAccess): static
    {
        $this->lastAdminAccess = $lastAdminAccess;

        return $this;
    }

    public function isFirstLogin(): bool
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(bool $firstLogin): void
    {
        $this->firstLogin = $firstLogin;
    }
}
