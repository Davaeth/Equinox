<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElementRepository")
 */
class Element
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
     * @ORM\Column(type="text")
     */
    private $mechanics;

    /**
     * @ORM\Column(type="text")
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="elements")
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

    public function getMechanics(): ?string
    {
        return $this->mechanics;
    }

    public function setMechanics(string $mechanics)
    {
        $this->mechanics = $mechanics;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin)
    {
        $this->origin = $origin;
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
