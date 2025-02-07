<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Models\MonitorDTO;
use App\Entity\Monitor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MonitorService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllMonitors(): array
    {
        return $this->entityManager->getRepository(Monitor::class)->findAll();
    }

    public function createMonitor(MonitorDTO $newMonitor): MonitorDTO
    {
        $newMonitorEntity = new Monitor();
        $newMonitorEntity->setName($newMonitor->name)
            ->setEmail($newMonitor->email)
            ->setPhonenumber($newMonitor->phoneNumber)
            ->setImage($newMonitor->image);

        $this->entityManager->persist($newMonitorEntity);

        $this->entityManager->flush();
    
        $newMonitor->id = $newMonitorEntity->getId();
        return $newMonitor;
    }

    public function updateMonitor(MonitorDTO $updateMonitor): Monitor
    {
        $monitor = $this->entityManager->getRepository(Monitor::class)->find($updateMonitor->getId());
        if (!$monitor) {
            throw new NotFoundHttpException('Monitor not found');
        }

        $monitor->setName($updateMonitor->getName() ?? $monitor->getName())
            ->setEmail($updateMonitor->getEmail() ?? $monitor->getEmail())
            ->setPhonenumber($updateMonitor->getPhonenumber() ?? $monitor->getPhonenumber())
            ->setImage($updateMonitor->getImage() ?? $monitor->getImage());

        $this->entityManager->flush();

        return $monitor;
    }

    public function deleteMonitor(int $id): void
    {
        $monitor = $this->entityManager->getRepository(Monitor::class)->find($id);
        if (!$monitor) {
            throw new NotFoundHttpException('Monitor not found');
        }

        $this->entityManager->remove($monitor);
        $this->entityManager->flush();
    }
}
