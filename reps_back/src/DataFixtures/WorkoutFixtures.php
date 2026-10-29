<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\Workout;
use App\Entity\WorkoutSet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Génère 8 semaines d'historique réaliste pour l'utilisateur de démo :
 * Push le lundi, Pull le mercredi, Legs le vendredi, cardio le samedi.
 * Les charges suivent une double progression, pour avoir de vraies courbes
 * et des records à détecter.
 */
class WorkoutFixtures extends Fixture implements DependentFixtureInterface
{
    private const WEEKS = 8;

    // jour de la semaine (1 = lundi) => séance du programme
    private const SCHEDULE = [
        1 => 'push',
        3 => 'pull',
        5 => 'legs',
    ];

    private const NOTES = [
        'Grosse forme aujourd\'hui',
        'Mal dormi, séance difficile',
        'Salle bondée, attente sur les machines',
        'Bonne séance, technique propre',
        'Légère gêne à l\'épaule, à surveiller',
    ];

    public function load(ObjectManager $manager): void
    {
        mt_srand(42); // mêmes données à chaque chargement

        $user = $this->getReference(UserFixtures::DEMO_USER, User::class);

        // état de progression de chaque exercice : charge actuelle et reps visées
        $progress = [];
        foreach (WorkoutTemplateFixtures::PROGRAM as $template) {
            foreach ($template['exercises'] as [$ref, $sets, $repsMin, $repsMax, $startWeight]) {
                $progress[$ref] = ['weight' => $startWeight, 'reps' => $repsMin];
            }
        }

        $start = (new \DateTimeImmutable('monday this week'))
            ->modify(sprintf('-%d weeks', self::WEEKS));

        for ($week = 0; $week < self::WEEKS; $week++) {
            foreach (self::SCHEDULE as $day => $templateKey) {
                // environ 1 séance sur 10 sautée, comme dans la vraie vie
                if (mt_rand(1, 10) === 1) {
                    continue;
                }

                $date = $start
                    ->modify(sprintf('+%d weeks +%d days', $week, $day - 1))
                    ->setTime(18, mt_rand(0, 59));

                $workout = (new Workout())
                    ->setOwner($user)
                    ->setPerformedAt($date)
                    ->setDuration(mt_rand(50, 75));

                if (mt_rand(1, 4) === 1) {
                    $workout->setNotes(self::NOTES[array_rand(self::NOTES)]);
                }

                foreach (WorkoutTemplateFixtures::PROGRAM[$templateKey]['exercises'] as [$ref, $sets, $repsMin, $repsMax]) {
                    $exercise = $this->getReference('exercise_' . $ref, Exercise::class);
                    $allSetsAtMax = true;

                    for ($n = 1; $n <= $sets; $n++) {
                        // la fatigue fait parfois perdre une répétition sur les dernières séries
                        $reps = $progress[$ref]['reps'];
                        if ($n > 2 && mt_rand(1, 3) === 1) {
                            $reps--;
                        }

                        if ($reps < $repsMax) {
                            $allSetsAtMax = false;
                        }

                        $set = (new WorkoutSet())
                            ->setExercise($exercise)
                            ->setSetNumber($n)
                            ->setReps($reps)
                            ->setWeight($progress[$ref]['weight'])
                            ->setRpe(min(10, 6 + $n + mt_rand(0, 1)));

                        $workout->addSet($set);
                        $manager->persist($set);
                    }

                    // double progression : toutes les séries au max => on monte la charge
                    if ($allSetsAtMax) {
                        if ($progress[$ref]['weight'] !== null) {
                            $progress[$ref]['weight'] += $progress[$ref]['weight'] < 20 ? 1 : 2.5;
                            $progress[$ref]['reps'] = $repsMin;
                        }
                    } else {
                        $progress[$ref]['reps'] = min($repsMax, $progress[$ref]['reps'] + 1);
                    }
                }

                $manager->persist($workout);
            }

            // séance cardio du samedi, un peu plus de distance chaque semaine
            $cardio = (new Workout())
                ->setOwner($user)
                ->setPerformedAt($start->modify(sprintf('+%d weeks +5 days', $week))->setTime(10, 0))
                ->setDuration(30);

            $run = (new WorkoutSet())
                ->setExercise($this->getReference('exercise_treadmill', Exercise::class))
                ->setSetNumber(1)
                ->setDuration(30 * 60)
                ->setDistance(round(4.5 + $week * 0.15 + mt_rand(0, 20) / 100, 2));

            $cardio->addSet($run);
            $manager->persist($run);
            $manager->persist($cardio);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, ExerciseFixtures::class, WorkoutTemplateFixtures::class];
    }
}