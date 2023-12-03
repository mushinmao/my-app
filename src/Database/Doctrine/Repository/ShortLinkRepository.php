<?php

namespace App\Database\Doctrine\Repository;

use App\Database\Doctrine\Trait\SimpleSaveMethodRepository;
use Doctrine\ORM\EntityRepository;

class ShortLinkRepository extends EntityRepository
{
    use SimpleSaveMethodRepository;
}