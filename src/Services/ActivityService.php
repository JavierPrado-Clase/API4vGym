<?php

namespace App\Services;

use App\DTO\ActivityDTO;
use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivityService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllActivities(?DateTime $date): array
    {
        $activities = $dateFilter ? $this->entityManager->getRepository(Monitor::class)->findBy(['dateStart' => $date]) : $this->entityManager->getRepository(Monitor::class)->findAll();
        return array_map(fn($activity) => new ActivityDTO(
            $activity->getId(),
            $activity->getActivityType(),
            $activity->getDateStart(),
            $activity->getDateEnd(),
            $activity->getMonitors()->toArray()
        ), $activities);
    }

    public function createActivity(ActivityDTO $newActivity): ActivityDTO
    {
        $allowedTimes = ['09:00', '13:30', '17:30'];
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $newActivity->getDateStart());

        if (!$dateTime || !in_array($dateTime->format('H:i'), $allowedTimes)) {
            throw new \InvalidArgumentException('Invalid start time. Allowed times: 09:00, 13:30, 17:30.');
        }

        $activityType = $this->entityManager->getRepository(ActivityTypes::class)->find($newActivity->getActivityType());
        if (!$activityType) {
            throw new NotFoundHttpException('Activity type not found');
        }

        $monitorIds = $newActivity->getMonitors()->map(function ($monitor) {
            return $monitor->getId();
        })->toArray();
        
        $monitors = $this->entityManager->getRepository(Monitor::class)->findByIds($monitorIds);
        if (count($monitors) < $activityType->getRequiredMonitors()) {
            throw new \InvalidArgumentException('Not enough monitors for this activity.');
        }

        $activity = new Activity();
        $activity->setActivityType($activityType)
            ->setDateStart($dateTime)
            ->setDateEnd((clone $dateTime)->modify('+90 minutes'));

        foreach ($monitors as $monitor) {
            $activity->addMonitor($monitor);
        }

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return new ActivityDTO($activity->getId(), $activityType, $activity->getDateStart(), $activity->getDateEnd(), $monitors);
    }
    public function updateActivity(ActivityDTO $updateActivity): ActivityDTO
    {
        $activity = $this->entityManager->getRepository(Activity::class)->find($updateActivity->getId());
        if (!$activity) {
            throw new NotFoundHttpException('Activity not found');
        }

        $activity->setActivityType($updateActivity->getActivityType() ?? $activity->getActivityType())
            ->setDateStart($updateActivity->getDateStart() ?? $activity->getDateStart())
            ->setDateEnd((clone $updateActivity->getDateStart())->modify('+90 minutes') ?? $activity->getDateEnd());

        foreach (($updateActivity->getMonitors() ?? $activity->getMonitors()) as $monitor) {
            $activity->addMonitor($monitor);
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