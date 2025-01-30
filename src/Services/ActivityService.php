<?php

namespace App\Services;

use App\Models\ActivityDTO;
use App\Models\MonitorDTO;
use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\ActivityTypeDTO;
use DateTime;
use App\Entity\Monitor;
use App\Entity\ActivityTypes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivityService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllActivities(?DateTime $date): array
    {
        $activities = $date ? $this->entityManager->getRepository(Activity::class)->createQueryBuilder('a')
        ->where('a.dateStart >= :dateStart')
        ->andWhere('a.dateStart < :dateEnd')
        ->setParameter('dateStart', $date->setTimezone(new \DateTimeZone('Europe/Berlin'))->format('Y-m-d H:i:s.v'))
        ->setParameter('dateEnd', (clone $date)->modify('+90 minutes')->setTimezone(new \DateTimeZone('Europe/Berlin'))->format('Y-m-d H:i:s.v'))
        ->getQuery()
        ->getResult()
        : $this->entityManager->getRepository(Activity::class)->findAll();
        return array_map(fn($activity) => new ActivityDTO(
            $activity->getId(),
            new ActivityTypeDTO(
                $activity->getActivityType()->getId(),
                $activity->getActivityType()->getName(),
                $activity->getActivityType()->getRequiredMonitors()
            ),
            $activity->getDateStart(),
            $activity->getDateEnd(),
            array_map(fn($monitor) => new MonitorDTO(
                $monitor->getId(),
                $monitor->getName(),
                $monitor->getEmail(),
                $monitor->getPhoneNumber(),
                $monitor->getImage()
            ), $activity->getMonitors()->toArray())
        ), $activities);
    }

    public function createActivity(ActivityDTO $newActivity): ActivityDTO
    {
        $allowedTimes = ['09:00', '13:30', '17:30'];
        $dateTime = $newActivity->getDateStart()->format('d-m-Y H:i');
        
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $dateTime);
        
        if (!$dateTime || !in_array($dateTime->format('H:i'), $allowedTimes)) {
            throw new \InvalidArgumentException('Invalid start time. Allowed times: 09:00, 13:30, 17:30.');
        }

        $activityType = $this->entityManager->getRepository(ActivityTypes::class)->find($newActivity->getActivityType()->getId());
        if (!$activityType) {
            throw new NotFoundHttpException('Activity type not found');
        }

        $monitorIds = array_map(function ($monitor) {
            return $monitor['id'];
        }, $newActivity->getMonitors());
        
        $monitors = $this->entityManager->getRepository(Monitor::class)->findBy(['id' => $monitorIds]);
        if (count($monitors) < $activityType->getRequiredMonitors()) {
            throw new \InvalidArgumentException('Not enough monitors for this activity.');
        }

        $activity = new Activity();
        $activity->setActivityType($activityType)
            ->setDateStart($newActivity->getDateStart())
            ->setDateEnd((clone $newActivity->getDateStart())->modify('+90 minutes'));

        foreach ($monitors as $monitor) {
            $activity->addMonitor($monitor);
        }

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return new ActivityDTO($activity->getId(), new ActivityTypeDTO($activityType->getId(), $activityType->getName(), $activityType->getRequiredMonitors()), $activity->getDateStart(), $activity->getDateEnd(), $monitors);
    }
    public function updateActivity(ActivityDTO $updateActivity): Activity
    {
        $activity = $this->entityManager->getRepository(Activity::class)->find($updateActivity->getId());
        if (!$activity) {
            throw new NotFoundHttpException('Activity not found');
        }
        $activityTypeDTO = $updateActivity->getActivityType();
        $activityType = $this->entityManager->getRepository(ActivityTypes::class)->find($activityTypeDTO->id);

        $activity->setActivityType($activityType ?? $activity->getActivityType())
            ->setDateStart($updateActivity->getDateStart() ?? $activity->getDateStart())
            ->setDateEnd((clone $updateActivity->getDateStart())->modify('+90 minutes') ?? $activity->getDateEnd());

        foreach (($updateActivity->getMonitors() ?? $activity->getMonitors()) as $monitorData) {
            if ($monitorData instanceof Monitor) {
                $activity->addMonitor($monitorData);
            } else {
                // Convert to Monitor entity
                $monitor = $this->entityManager->getRepository(Monitor::class)->find($monitorData['id'] ?? $monitorData->id);
        
                if (!$monitor) {
                    throw new \Exception("Monitor not found with ID: " . ($monitorData['id'] ?? $monitorData->id));
                }
        
                $activity->addMonitor($monitor);
            }
        }

        $this->entityManager->flush();


        return $activity;
    }

    public function deleteActivity(int $id): void
    {
        $activity = $this->entityManager->getRepository(Activity::class)->find($id);
        if (!$activity) {
            throw new NotFoundHttpException('Activity not found');
        }

        $this->entityManager->remove($activity);
        $this->entityManager->flush();
    }
}