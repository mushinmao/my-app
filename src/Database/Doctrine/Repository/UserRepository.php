<?php

namespace App\Database\Doctrine\Repository;

use App\Database\Doctrine\Entity\User;
use App\Database\Doctrine\Trait\SimpleSaveMethodRepository;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    use SimpleSaveMethodRepository;

    public function findByUsername(string $username): User
    {
        return $this->findOneBy(['username' => $username]);
    }
}