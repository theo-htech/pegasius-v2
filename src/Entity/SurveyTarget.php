<?php

namespace App\Entity;



class SurveyTarget
{
    public const EMAIL = 'email';
    public const TARGET_TYPE = 'target_type';
    public function __construct($email, $type)
    {
        $this->setEmail($email);
        $this->setTargetType($type);
    }


    private ?string $email = null;


    private ?string $targetType = null;

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

    public function getTargetType(): ?string
    {
        return $this->targetType;
    }

    public function setTargetType(string $targetType): static
    {
        $this->targetType = $targetType;

        return $this;
    }
}
