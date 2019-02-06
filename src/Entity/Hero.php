<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeroRepository")
 */
class Hero
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstElement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secondElement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $appearance;

    /**
     * @ORM\Column(type="text")
     */
    private $personality;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="heroes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getFirstElement(): ?string
    {
        return $this->firstElement;
    }

    public function setFirstElement(string $firstElement)
    {
        $this->firstElement = $firstElement;
    }

    public function getSecondElement(): ?string
    {
        return $this->secondElement;
    }

    public function setSecondElement(string $secondElement)
    {
        $this->secondElement = $secondElement;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAppearance(): ?string
    {
        return $this->appearance;
    }

    public function setAppearance(string $appearance)
    {
        $this->appearance = $appearance;
    }

    public function getPersonality(): ?string
    {
        return $this->personality;
    }

    public function setPersonality(string $personality)
    {
        $this->personality = $personality;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator)
    {
        $this->creator = $creator;
    }
}
