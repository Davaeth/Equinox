<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="nickname", message="Nickname already taken")
 */
class User
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
    private $username;

    /**
     * @ORM\Column(type="string", length=4096)
     * @Assert\Length(min=5)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Spell", mappedBy="creator", orphanRemoval=false)
     */
    private $spells;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hero", mappedBy="creator", orphanRemoval=false)
     */
    private $heroes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="creator")
     */
    private $elements;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return Collection|Spell[]
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell)
    {
        if (!$this->spells->contains($spell)) {
            $this->spells[] = $spell;
            $spell->setCreator($this);
        }
    }

    public function removeSpell(Spell $spell)
    {
        if ($this->spells->contains($spell)) {
            $this->spells->removeElement($spell);
            // set the owning side to null (unless already changed)
            if ($spell->getCreator() === $this) {
                $spell->setCreator(null);
            }
        }
    }

    /**
     * @return Collection|Hero[]
     */
    public function getHeroes(): Collection
    {
        return $this->heroes;
    }

    public function addHero(Hero $hero)
    {
        if (!$this->heroes->contains($hero)) {
            $this->heroes[] = $hero;
            $hero->setCreator($this);
        }
    }

    public function removeHero(Hero $hero)
    {
        if ($this->heroes->contains($hero)) {
            $this->heroes->removeElement($hero);
            // set the owning side to null (unless already changed)
            if ($hero->getCreator() === $this) {
                $hero->setCreator(null);
            }
        }
    }

    /**
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element)
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->setCreator($this);
        }
    }

    public function removeElement(Element $element)
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getCreator() === $this) {
                $element->setCreator(null);
            }
        }
    }
}
