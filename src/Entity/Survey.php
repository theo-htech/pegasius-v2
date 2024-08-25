<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use UnexpectedValueException;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
#[Broadcast]
class Survey
{
    const STATUS_NEW = 'new';
    const STATUS_CONFIRM = 'confirm';

    const STATUS_MANAGER_ASKED = 'manager_asked';
    const STATUS_IDLE = 'idle';
    const STATUS_CANCEL = 'cancel';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_OVER = 'over';
    const VIEW_RESULT = array(self::STATUS_OVER, self::STATUS_ONGOING);

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'surveys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: Poll::class, mappedBy: 'survey')]
    private Collection $polls;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validationDate = null;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable("now");
        $this->status = self::STATUS_NEW;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Poll>
     */
    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(Poll $poll): static
    {
        if ($this->polls->isEmpty() || !$this->polls->contains($poll)) {
            $this->polls->add($poll);
            $poll->setSurvey($this);
        }

        return $this;
    }

    public function removePoll(Poll $poll): static
    {
        if ($this->polls->removeElement($poll)) {
            // set the owning side to null (unless already changed)
            if ($poll->getSurvey() === $this) {
                $poll->setSurvey(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getValidationDate(): ?\DateTimeInterface
    {
        return $this->validationDate;
    }

    public function setValidationDate(?\DateTimeInterface $validationDate): static
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    public function getPollByType($type)
    {
        return $this->polls->filter(function (Poll $poll) use ($type) {
            return $poll->getType() === $type;
        })->first();
    }

    public function getAllPollHasNoRequest(): bool
    {
        foreach ($this->polls as $poll) {
            if (!$poll->getSurveyFillUpRequests()->isEmpty()) {
                return false;
            }
        }
        return true;
    }

    public function getPourcentFillUp()
    {
        $totalPolls = $this->count;
        $filledPolls = 0;
        foreach ($this->polls as $poll) {
            if ($poll->getCountFillUp() > 0) {
                $filledPolls = $filledPolls + $poll->getCountFillUp();
            }
        }
        if ($totalPolls > 0) {
            return ($filledPolls / $totalPolls) * 100;
        } else {
            return 0;
        }
    }
}
