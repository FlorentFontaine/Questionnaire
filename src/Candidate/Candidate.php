<?php

namespace App\Candidate;

class Candidate
{
    private $id;
    private $firstName;
    private $lastName;
    private $result;
    private $createdAt;

    public function __construct()
    {
        $theDate = new \DateTime();
        $this->createdAt = $theDate->format('Y-m-d H:i:s');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function __toString()
    {
        return $this->createdAt;
    }
}
