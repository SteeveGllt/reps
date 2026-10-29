<?php

namespace App\DataFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Exercise;
use App\Entity\TemplateExercise;
use App\Entity\User;
use App\Entity\WorkoutTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;

class WorkoutTemplateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Programme Push / Pull / Legs.
     * Chaque exercice : [référence, séries, reps min, reps max, charge de départ en kg (null = poids du corps)]
     * La charge de départ n'est pas stockée dans le modèle : elle sert à WorkoutFixtures.
     */
    public const PROGRAM = [
        'push' => [
            'name' => 'Push (pecs, épaules, triceps)',
            'exercises' => [
                ['bench_press', 4, 6, 8, 60.0],
                ['overhead_press', 3, 8, 10, 35.0],
                ['incline_dumbbell_press', 3, 8, 12, 20.0],
                ['lateral_raise', 3, 12, 15, 8.0],
                ['triceps_pushdown', 3, 10, 12, 25.0],
            ],
        ],
        'pull' => [
            'name' => 'Pull (dos, biceps)',
            'exercises' => [
                ['deadlift', 3, 5, 6, 90.0],
                ['pull_up', 4, 6, 10, null],
                ['barbell_row', 3, 8, 10, 50.0],
                ['lat_pulldown', 3, 10, 12, 45.0],
                ['biceps_curl', 3, 10, 12, 12.0],
            ],
        ],
        'legs' => [
            'name' => 'Legs (jambes)',
            'exercises' => [
                ['squat', 4, 6, 8, 70.0],
                ['romanian_deadlift', 3, 8, 10, 60.0],
                ['leg_press', 3, 10, 12, 120.0],
                ['leg_curl', 3, 10, 12, 35.0],
                ['calf_raise', 4, 12, 15, 40.0],
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $owner = $this->getReference(UserFixtures::DEMO_USER, User::class);

        foreach (self::PROGRAM as $key => $data) {
            $template = (new WorkoutTemplate())
                ->setName($data['name'])
                ->setOwner($owner);

            foreach ($data['exercises'] as $position => [$ref, $sets, $repsMin, $repsMax]) {
                $templateExercise = (new TemplateExercise())
                    ->setExercise($this->getReference('exercise_' . $ref, Exercise::class))
                    ->setPosition($position + 1)
                    ->setTargetSets($sets)
                    ->setRepsMin($repsMin)
                    ->setRepsMax($repsMax);

                $template->addTemplateExercise($templateExercise);
                $manager->persist($templateExercise);
            }

            $manager->persist($template);
            $this->addReference('template_' . $key, $template);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, ExerciseFixtures::class];
    }
}