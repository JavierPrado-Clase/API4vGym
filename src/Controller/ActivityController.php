<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Services\ActivityService;
use App\Models\ActivityNewDTO;
use App\Models\ActivityDTO;

final class ActivityController extends AbstractController
{
    public function __construct(private ActivityService $service){
    }
    #[Route('/activities', name: 'get_activities', methods: ['GET'])]
    public function getAllActivities(#[RequestParam(type: DateTime::class)] ?DateTime $date = null): JsonResponse
    {
        return $this->json($this->service->getAllActivities($date));
    }

    #[Route('/activities', name: 'post_activities', methods: ['POST'])]
    public function createActivity(#[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] ActivityNewDTO $activityNewDto): JsonResponse
    {
        try {
            $activity = new ActivityDTO($activityNewDto->id, $activityNewDto->activityType, $activityNewDto->dateStart, $activityNewDto->dateEnd, $activityNewDto->monitors);
            $activity = $this->service->createActivity($activity);
            return $this->json($activity);
        } catch (\InvalidArgumentException $e) {
            return $this->json(["error" => $e], 400);
        } catch (\NotFoundHttpException $e) {
            return $this->json(["error" => $e], 400);
        }
        
    }

    #[Route('/activities/{id}', name: 'put_activities', methods: ['PUT'])]
    public function updateActivity(#[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] ActivityDTO $activityDto): JsonResponse
    {
        try {
            $activity = new ActivityDTO($activityDto->id, $activityDto->activityType, $activityDto->dateStart, $activityDto->dateEnd, $activityDto->monitors);
            $activity = $this->service->updateActivity($activity);
            return $this->json($activity);
        } catch (NotFoundHttpException $e) {
            return $this->json(["error" => $e], 400);
        }
        
    }

    #[Route('/activities/{id}', name: 'delete_activities', methods: ['DELETE'])]
    public function deleteActivity(int $id): JsonResponse
    {
        try {
            $this->service->deleteActivity($id);
            return $this->json(['message' => 'Activity deleted successfully']);
        } catch (NotFoundHttpException $e) {
            return $this->json(["error" => $e], 400);
        }
        
    }
}
