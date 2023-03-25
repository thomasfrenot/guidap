<?php

namespace App\Controller;

use App\Entity\RecreationPark;
use App\Repository\ActivityRepository;
use App\Repository\RecreationParkRepository;
use Doctrine\ORM\EntityManagerInterface;
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
                'totalPages' => round(count($recreationParks) / $defaultLimit),
                'totalResults' => count($recreationParks),
                'results' => $recreationParks
            ],
            'json',
            ['groups' => 'recreation_park:read']
        );

        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/recreation-parks", name="recreation_park_create", methods={"POST"})
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
     * @Route("/api/recreation-parks/{id}", name="recreation_park_read", methods={"GET"})
     */
    public function readRecreationPark(
        RecreationPark $recreationPark,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $jsonData = $serializer->serialize($recreationPark, 'json', ['groups' => 'recreation_park:read']);
        return new JsonResponse($jsonData, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * @Route("/api/recreation-parks/{id}", name="recreation_park_update", methods={"PUT"})
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
     */
    public function deleteRecreationPark(RecreationPark $recreationPark, EntityManagerInterface $em)
    {
        $em->remove($recreationPark);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
