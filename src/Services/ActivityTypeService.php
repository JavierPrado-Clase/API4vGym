<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Models\ActivityTypeDTO;

class ActivityTypeService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllActivityTypes(): array
    {
        return $this->entityManager->getRepository(ActivityTypeDTO::class)->findAll();
    }
}
