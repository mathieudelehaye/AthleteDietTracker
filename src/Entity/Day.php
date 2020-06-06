<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DayRepository")
 */
class Day
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $equivalent_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $athlete_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEquivalentName(): ?string
    {
        return $this->equivalent_name;
    }

    public function setEquivalentName(string $equivalent_name): self
    {
        $this->equivalent_name = $equivalent_name;

        return $this;
    }

    public function getAthleteId(): ?int
    {
        return $this->athlete_id;
    }

    public function setAthleteId(int $athlete_id): self
    {
        $this->athlete_id = $athlete_id;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
