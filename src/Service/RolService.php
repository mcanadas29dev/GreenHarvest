<?php

// src/Service/RolService.php
namespace App\Service;

use App\Entity\Rol;
use Doctrine\ORM\EntityManagerInterface;

class RolService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createRol(Rol $rol): void
    {
        $this->em->persist($rol);
        $this->em->flush();
    }

    public function updateRol(): void
    {
        $this->em->flush();
    }

    public function deleteRol(Rol $rol): void
    {
        $this->em->remove($rol);
        $this->em->flush();
    }
}
