<?php
namespace App\Database\Doctrine\Entity;

use App\Database\Doctrine\Repository\ShortLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: ShortLinkRepository::class)]
#[ORM\Table(name: 'short_links')]
class ShortLink
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 256)]
    private string $hash;

    #[ORM\Column(length: 64)]
    private string $code;

    #[ORM\Column(length: 512)]
    private string $url;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'shortLinks')]
    private User $user;
    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->url = $data['url'];
        $this->hash = $data['hash'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function changeHash(string $hash): ShortLink
    {
        $this->hash = $hash;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function changeCode(string $code): ShortLink
    {
        $this->code = $code;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function changeUrl(string $url): ShortLink
    {
        $this->url = $url;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): ShortLink
    {
        $this->user = $user;
        return $this;
    }
}