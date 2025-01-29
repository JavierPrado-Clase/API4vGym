<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Services\MonitorService;
use App\Models\MonitorNewDTO;
use App\Models\MonitorDTO;

final class MonitorController extends AbstractController
{
    public function __construct(private MonitorService $service){
    }

    #[Route('/monitors', name: 'get_monitors', methods: ['GET'])]
    public function getAllMonitors(): JsonResponse
    {
        return $this->json($this->service->getAllMonitors());
    }

    #[Route('/monitors', name: 'post_monitors', methods: ['POST'])]
    public function createMonitor(#[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] MonitorNewDTO $monitorNewDto): JsonResponse
    {
        $monitor = new MonitorDTO($monitorNewDto->id, $monitorNewDto->name, $monitorNewDto->email, $monitorNewDto->phoneNumber, $monitorNewDto->image);
        $monitor = $this->service->createMonitor($monitor);
        return $this->json($monitor);
    }

    #[Route('/monitors/{id}', name: 'put_monitors', methods: ['PUT'])]
    public function updateMonitor(#[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] MonitorDTO $monitorDto): JsonResponse
    {
        try {
            $monitor = new MonitorDTO($monitorDto->id, $monitorDto->name, $monitorDto->email, $monitorDto->phoneNumber, $monitorDto->image);
            $monitor = $this->service->updateMonitor($monitor);
            return $this->json($monitor);
        } catch (NotFoundHttpException $e) {
            return $this->json(["error" => $e], 400);
        }
        
    }

    #[Route('/monitors/{id}', name: 'delete_monitors', methods: ['DELETE'])]
    public function deleteMonitor(int $id): JsonResponse
    {
        try {
            $this->service->deleteMonitor($id);
        return $this->json(['message' => 'Monitor deleted successfully']);
        } catch (NotFoundHttpException $e) {
            return $this->json(["error" => $e], 400);
        }
    }
}
