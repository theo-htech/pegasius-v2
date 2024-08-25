<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PollRepository::class)]
#[Broadcast]
class Poll
{
    const MANAGER = 'manager';
    const SALARY = 'salary';

    const TYPES = array(
        self::MANAGER,
        self::SALARY
    );
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'polls')]
    #[ORM\OrderBy(["id" => "ASC"])]
    private ?Survey $survey = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $countFillUp = 0;

    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'poll', cascade: ['persist'])]
    private Collection $answers;

    #[ORM\OneToMany(targetEntity: SurveyFillUpRequest::class, mappedBy: 'poll')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $surveyFillUpRequests;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->surveyFillUpRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): static
    {
        $this->survey = $survey;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCountFillUp(): ?int
    {
        return $this->countFillUp;
    }

    public function setCountFillUp(int $countFillUp): static
    {
        $this->countFillUp = $countFillUp;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setPoll($this);
        }

        return $this;
    }

    public function addAnswers($answers): static
    {
        foreach ($answers as $answer) {
            $this->addAnswer($answer);
        }
        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getPoll() === $this) {
                $answer->setPoll(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SurveyFillUpRequest>
     */
    public function getSurveyFillUpRequests(): Collection
    {
        return $this->surveyFillUpRequests;
    }

    public function addSurveyFillUpRequest(SurveyFillUpRequest $surveyFillUpRequest): static
    {
        if (!$this->surveyFillUpRequests->contains($surveyFillUpRequest)) {
            $this->surveyFillUpRequests->add($surveyFillUpRequest);
            $surveyFillUpRequest->setPoll($this);
        }

        return $this;
    }

    public function removeSurveyFillUpRequest(SurveyFillUpRequest $surveyFillUpRequest): static
    {
        // set the owning side to null (unless already changed)
        if ($this->surveyFillUpRequests->removeElement($surveyFillUpRequest) &&
            $surveyFillUpRequest->getPoll() === $this) {
            $surveyFillUpRequest->setPoll(null);
        }

        return $this;
    }

    public function getAnswerByBlocAndQuestion($bloc, $question)
    {
        foreach ($this->answers as $answer) {
            if ($answer->getBloc() == $bloc && $answer->getQuestion() == $question) {
                return $answer;
            }
        }
        return null;
    }

    public function getResponsesArray()
    {
      $responses = [];
        foreach ($this->answers as $answer) {

        }
        return $responses;
    }

}
