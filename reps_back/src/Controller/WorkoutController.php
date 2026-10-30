<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\WorkoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\Workout;
use App\Security\Voter\WorkoutVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Dto\WorkoutInput;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/workouts', name: 'api_workouts_')]
final class WorkoutController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] User $user, WorkoutRepository $repo): JsonResponse
    {
        $workouts = $repo->findBy(['owner' => $user], ['performedAt' => 'DESC']);

        return $this->json($workouts, context: ['groups' => 'workout:read']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[IsGranted(WorkoutVoter::VIEW, subject: 'workout')]
    public function show(Workout $workout): JsonResponse
    {
        return $this->json($workout, context: ['groups' => 'workout:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(#[CurrentUser] User $user, #[MapRequestPayload] WorkoutInput $input, EntityManagerInterface $em): JsonResponse
    {
        $workout = (new Workout())
        ->setOwner($user)
        ->setPerformedAt($input->performedAt)
        ->setDuration($input->duration)
        ->setNotes($input->notes);

        $em->persist($workout);
        $em->flush();

        return $this->json($workout, 201, context: ['groups' => 'workout:read']);
    }
}
