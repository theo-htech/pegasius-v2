<?php

namespace App\Entity;

use App\Repository\SurveyFillUpRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SurveyFillUpRequestRepository::class)]
class SurveyFillUpRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'surveyFillUpRequests')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Poll $poll = null;

    #[ORM\Column(length: 100)]
    private ?string $hashedToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvey(): ?Survey
    {
        return $this->getPoll()->getSurvey();
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

    public function getPoll(): ?Poll
    {
        return $this->poll;
    }

    public function setPoll(?Poll $poll): static
    {
        $this->poll = $poll;

        return $this;
    }

    public function getHashedToken(): ?string
    {
        return $this->hashedToken;
    }

    public function setHashedToken(string $hashedToken): static
    {
        $this->hashedToken = $hashedToken;

        return $this;
    }
}
