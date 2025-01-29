<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;

use App\Services\MonitorService;

final class MonitorController extends AbstractController
{
    public function __construct(private MonitorService $service){
    }

    #[Route('/monitors', name: 'get_monitors', methods: ['GET'])]
    public function getMonitors(): JsonResponse
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
    public function updateMonitor(#[MapRequestPayload(validationFailedStatusCode: Response::HTTP_NOT_FOUND)] MonitorUpdateDTO $monitorUpdateDto): JsonResponse
    {
        $monitor = new MonitorDTO($monitorUpdateDto->id, $monitorUpdateDto->name, $monitorUpdateDto->email, $monitorUpdateDto->phoneNumber, $monitorUpdateDto->image);
        $monitor = $this->service->updateMonitor($monitor);
        return $this->json($monitor);
    }

    #[Route('/monitors/{id}', name: 'delete_monitors', methods: ['DELETE'])]
    public function deleteMonitor(int $id): JsonResponse
    {
        $this->service->deleteMonitor($id);
        return $this->json(['message' => 'Monitor deleted successfully']);
    }
}
