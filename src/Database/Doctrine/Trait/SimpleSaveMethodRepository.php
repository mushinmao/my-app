<?php

namespace App\Database\Doctrine\Trait;

trait SimpleSaveMethodRepository
{
    public function save(?object $entity = null): self
    {
        if (is_object($entity))
        {
            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush($entity);

        return $this;
    }
}