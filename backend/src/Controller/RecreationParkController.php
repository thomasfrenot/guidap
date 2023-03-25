<?php

namespace App\Controller;

use App\Entity\RecreationPark;
use App\Repository\ActivityRepository;
use App\Repository\RecreationParkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecreationParkController extends AbstractController
{
    /**
     * @Route("/api/recreation-parks", name="recreation_park_list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns recreation parks",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=RecreationPark::class, groups={"recreation_park:read"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search in name or description",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="activities",
     *     in="query",
     *     description="Search by one or more activities, put slug separated by a comma, ex: `canoe,wakboard`",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Get results for page",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Limit results returned, default limit is 2 items",
     *     @OA\Schema(type="integer")
     * )
     */
    public function listRecreationPark(
        Request $request,
        RecreationParkRepository $recreationParkRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $defaultLimit = 2;

        $page = $request->get('page', 1) - 1; // remove 1 for human page 1 to page 0 sql
        $limit = $request->get('limit', $defaultLimit);
        $search = $request->get('search');
        $activities = $request->get('activities') ? explode(',', $request->get('activities')) : [];

        $recreationParks = $recreationParkRepository->findWithSearchAndPaginator($page, $limit, $search, $activities);

        $jsonData = $serializer->serialize([
                'totalPages' => round(count($recreationParks) / $limit),
                'totalResults' => count($recreationParks),
                'results' => $recreationParks,
            ],
            'json',
            ['groups' => 'recreation_park:read']
        );

        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/recreation-parks", name="recreation_park_create", methods={"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Create new recreation park",
     *     @Model(type=RecreationPark::class, groups={"recreation_park:read"})
     * )
     * @OA\Post(
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="activityIds", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="zipcode", type="integer"),
     *             @OA\Property(property="website", type="string")
     *         )
     *     )
     * )
     * @Security(name="Bearer")
     */
    public function createRecreationPark(
        Request $request,
        ActivityRepository $activityRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $recreationPark = $serializer->deserialize($request->getContent(), RecreationPark::class, 'json');

        $errors = $validator->validate($recreationPark);
        if (0 < count($errors)) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new HttpException(JsonResponse::HTTP_BAD_REQUEST, $serializer->serialize($messages, 'json'));
        }

        $body = $request->toArray();
        if (true === array_key_exists('activityIds', $body)) {
            foreach ($body['activityIds'] as $activityId) {
                $activity = $activityRepository->find($activityId);
                $recreationPark->addActivity($activity);
            }
        }

        $em->persist($recreationPark);
        $em->flush();

        $jsonData = $serializer->serialize($recreationPark, 'json', ['groups' => 'recreation_park:read']);

        return new JsonResponse($jsonData, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/recreation-parks/{id}", name="recreation_park_update", methods={"PUT"})
     * @OA\Response(
     *     response=201,
     *     description="Create new recreation park",
     *     @Model(type=RecreationPark::class, groups={"recreation_park:read"})
     * )
     * @OA\Put(
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="activityIds", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="zipcode", type="integer"),
     *             @OA\Property(property="website", type="string")
     *         )
     *     )
     * )
     * @Security(name="Bearer")
     */
    public function updateRecreationPark(
        RecreationPark $recreationPark,
        Request $request,
        SerializerInterface $serializer,
        ActivityRepository $activityRepository,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $updatedRecreationPark = $serializer->deserialize($request->getContent(), RecreationPark::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $recreationPark]);
        $body = $request->toArray();

        $errors = $validator->validate($updatedRecreationPark);
        if (0 < count($errors)) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new HttpException(JsonResponse::HTTP_BAD_REQUEST, $serializer->serialize($messages, 'json'));
        }

        if (true === array_key_exists('activityIds', $body)) {
            // remove old activities
            foreach ($recreationPark->getActivities() as $activity) {
                $recreationPark->removeActivity($activity);
            }
            $em->persist($recreationPark);

            // add new activities
            foreach ($body['activityIds'] as $activityId) {
                $activity = $activityRepository->find($activityId);
                $updatedRecreationPark->addActivity($activity);
            }
        }

        $em->persist($updatedRecreationPark);
        $em->flush();

        $jsonData = $serializer->serialize($recreationPark, 'json', ['groups' => 'recreation_park:read']);
        return new JsonResponse($jsonData, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/recreation-parks/{id}", name="recreation_park_delete", methods={"DELETE"})
     * @Security(name="Bearer")
     */
    public function deleteRecreationPark(RecreationPark $recreationPark, EntityManagerInterface $em)
    {
        $em->remove($recreationPark);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
