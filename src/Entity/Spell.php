<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpellRepository")
 */
class Spell
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
    private $element;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rarity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $damage;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shield;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hitType;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="spells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resistance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $heal;

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

    public function getElement(): ?string
    {
        return $this->element;
    }

    public function setElement(string $element)
    {
        $this->element = $element;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity)
    {
        $this->rarity = $rarity;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(int $damage)
    {
        $this->damage = $damage;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count)
    {
        $this->count = $count;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(string $speed)
    {
        $this->speed = $speed;
    }

    public function getShield(): ?int
    {
        return $this->shield;
    }

    public function setShield(?int $shield)
    {
        $this->shield = $shield;
    }

    public function getHitType(): ?string
    {
        return $this->hitType;
    }

    public function setHitType(string $hitType)
    {
        $this->hitType = $hitType;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator)
    {
        $this->creator = $creator;
    }

    public function getResistance(): ?int
    {
        return $this->resistance;
    }

    public function setResistance(?int $resistance)
    {
        $this->resistance = $resistance;
    }

    public function getHeal(): ?int
    {
        return $this->heal;
    }

    public function setHeal(?int $heal)
    {
        $this->heal = $heal;
    }
}
