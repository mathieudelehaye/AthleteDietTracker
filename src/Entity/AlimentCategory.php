<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlimentCategoryRepository")
 */
class AlimentCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $g_pro;

    /**
     * @ORM\Column(type="float")
     */
    private $g_hyd;

    /**
     * @ORM\Column(type="float")
     */
    private $g_fat;

    /**
     * @ORM\Column(type="float")
     */
    private $g_fib;

    /**
     * @ORM\Column(type="float")
     */
    private $ene;

    public function getId()
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGPro(): ?float
    {
        return $this->g_pro;
    }

    public function setGPro(float $g_pro): self
    {
        $this->g_pro = $g_pro;

        return $this;
    }

    public function getGHyd(): ?float
    {
        return $this->g_hyd;
    }

    public function setGHyd(float $g_hyd): self
    {
        $this->g_hyd = $g_hyd;

        return $this;
    }

    public function getGFat(): ?float
    {
        return $this->g_fat;
    }

    public function setGFat(float $g_fat): self
    {
        $this->g_fat = $g_fat;

        return $this;
    }

    public function getGFib(): ?float
    {
        return $this->g_fib;
    }

    public function setGFib(float $g_fib): self
    {
        $this->g_fib = $g_fib;

        return $this;
    }

    public function getEne(): ?float
    {
        return $this->ene;
    }

    public function setEne(float $ene): self
    {
        $this->ene = $ene;

        return $this;
    }
}
