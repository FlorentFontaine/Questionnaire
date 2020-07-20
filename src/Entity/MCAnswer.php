<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MCAnswerRepository")
 */
class MCAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $text;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $valid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MCQuestion", inversedBy="answers")
     */
    protected $mCQuestion;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getMCQuestion(): ?MCQuestion
    {
        return $this->mCQuestion;
    }

    public function setMCQuestion(?MCQuestion $mCQuestion): self
    {
        $this->mCQuestion = $mCQuestion;

        return $this;
    }

    public function __toString(): string
    {
        return $this->mCQuestion;
    }
}
