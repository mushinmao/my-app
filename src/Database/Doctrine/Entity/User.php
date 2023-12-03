<?php

namespace App\Database\Doctrine\Entity;

use App\Database\Doctrine\Repository\UserRepository;
use App\ORM\DataMapper\VO\PrivateProperties;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]

class User
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 32)]
    private string $username;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ShortLink::class)]
    private Collection $shortLinks;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->shortLinks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function changeUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getShortLinks(): Collection
    {
        return $this->shortLinks;
    }

    public function addShortLink(ShortLink $item): User
    {
        $item->setUser($this);

        $this->shortLinks->add($item);

        return $this;
    }
}