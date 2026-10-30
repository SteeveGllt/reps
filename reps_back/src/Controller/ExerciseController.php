<?php

namespace App\Controller;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Exercise;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorFormatter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/exercises', name: 'api_exercise_')]
final class ExerciseController extends AbstractController
{

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(ExerciseRepository $repo): JsonResponse
    {
        $exercises = $repo->findAll();
        return $this->json($exercises);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Exercise $exercise): JsonResponse
    {
        return $this->json($exercise);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, ValidationErrorFormatter $formatter): JsonResponse
    {
        $data = $request->toArray();

        $exercise = (new Exercise())
        ->setName($data['name'] ?? '')
        ->setType($data['type'] ?? '')
        ->setMuscleGroup($data['muscleGroup'] ?? null);

        $errors = $validator->validate($exercise);
        if(count($errors) > 0){
            return $this->json(['errors' => $formatter->format($errors)], 422);
        }

        $em->persist($exercise);
        $em->flush();

        return $this->json($exercise, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, Exercise $exercise): JsonResponse
    {
        $data = $request->toArray();

        if(isset($data['name'])) {
            $exercise->setName($data['name']);
        }
        if(isset($data['type'])){
            $exercise->setType($data['type']);
        }
        if (array_key_exists('muscleGroup', $data)) {
            $exercise->setMuscleGroup($data['muscleGroup']);
        }

        $errors = $validator->validate($exercise);
        if(count($errors) > 0){
            return $this->json(['errors' => $formatter->format($errors)], 422);
        }

        $em->flush();

        return $this->json($exercise);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $em, Exercise $exercise): JsonResponse
    {
        $em->remove($exercise);
        $em->flush();

        return $this->json($exercise, 204);
    }
}
