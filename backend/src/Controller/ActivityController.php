<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ActivityController extends AbstractController
{
    /**
     * @Route("/api/activities", name="activity_list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns activites",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Activity::class, groups={"recreation_park:read"}))
     *     )
     * )
     */
    public function listActivity(
        ActivityRepository $activityRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $recreationParks = $activityRepository->findAll();
        $jsonData = $serializer->serialize($recreationParks, 'json', ['groups' => 'recreation_park:read']);
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }
}
