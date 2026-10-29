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

#[Route('/api/exercises', name: 'api_exercise_')]
final class ExerciseController extends AbstractController
{

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(ExerciseRepository $repo): JsonResponse
    {
        $exercices = $repo->findAll();
        return $this->json($exercices);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Exercise $exercice): JsonResponse
    {
        return $this->json($exercice);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->toArray();

        $exercise = (new Exercise())
        ->setName($data['name'] ?? '')
        ->setType($data['type'] ?? '')
        ->setMuscleGroup($data['muscleGroup'] ?? null);

        $errors = $validator->validate($exercise);
        if(count($errors) > 0){
            $messages = [];
            foreach ($errors as $error){
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $messages], 422);
        }

        $em->persist($exercise);
        $em->flush();

        return $this->json($exercise, 201);
    }
}
