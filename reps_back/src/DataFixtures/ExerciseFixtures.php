<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExerciseFixtures extends Fixture
{
    // référence => [nom, type, groupe musculaire]
    public const EXERCISES = [
        'bench_press'            => ['Développé couché', 'strength', 'Pectoraux'],
        'incline_dumbbell_press' => ['Développé incliné haltères', 'strength', 'Pectoraux'],
        'overhead_press'         => ['Développé militaire', 'strength', 'Épaules'],
        'lateral_raise'          => ['Élévations latérales', 'strength', 'Épaules'],
        'dips'                   => ['Dips', 'strength', 'Triceps'],
        'triceps_pushdown'       => ['Extension triceps poulie', 'strength', 'Triceps'],
        'deadlift'               => ['Soulevé de terre', 'strength', 'Dos'],
        'pull_up'                => ['Tractions', 'strength', 'Dos'],
        'barbell_row'            => ['Rowing barre', 'strength', 'Dos'],
        'lat_pulldown'           => ['Tirage vertical', 'strength', 'Dos'],
        'biceps_curl'            => ['Curl biceps haltères', 'strength', 'Biceps'],
        'squat'                  => ['Squat', 'strength', 'Jambes'],
        'leg_press'              => ['Presse à cuisses', 'strength', 'Jambes'],
        'romanian_deadlift'      => ['Soulevé de terre jambes tendues', 'strength', 'Ischio-jambiers'],
        'leg_curl'               => ['Leg curl', 'strength', 'Ischio-jambiers'],
        'calf_raise'             => ['Mollets debout', 'strength', 'Mollets'],
        'treadmill'              => ['Tapis de course', 'cardio', null],
        'rowing_machine'         => ['Rameur', 'cardio', null],
        'bike'                   => ['Vélo', 'cardio', null],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EXERCISES as $ref => [$name, $type, $muscleGroup]) {
            $exercise = (new Exercise())
                ->setName($name)
                ->setType($type)
                ->setMuscleGroup($muscleGroup);

            $manager->persist($exercise);

            // permet aux autres fixtures de récupérer cet exercice
            $this->addReference('exercise_' . $ref, $exercise);
        }

        $manager->flush();
    }
}