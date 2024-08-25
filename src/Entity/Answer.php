<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Poll $poll = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column]
    private ?float $response = 0;

    #[ORM\Column(length: 255)]
    private ?string $bloc = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getResponse(): ?float
    {
        return $this->response;
    }

    public function setResponse(float $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getBloc(): ?string
    {
        return $this->bloc;
    }

    public function setBloc(string $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function updateResponse($newValue)
    {
        $count = $this->getPoll()->getCountFillUp();
        $total = $this->response * $count;
        $newTotal = $total + $newValue;
        $this->response = $newTotal / ($count + 1);
    }
}
