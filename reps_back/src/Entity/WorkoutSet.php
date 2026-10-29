<?php

namespace App\Entity;

use App\Repository\WorkoutSetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutSetRepository::class)]
class WorkoutSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $setNumber = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $reps = null;

    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?float $distance = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $rpe = null;

    #[ORM\ManyToOne(inversedBy: 'sets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workout $workout = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSetNumber(): ?int
    {
        return $this->setNumber;
    }

    public function setSetNumber(int $setNumber): static
    {
        $this->setNumber = $setNumber;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(?int $reps): static
    {
        $this->reps = $reps;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getRpe(): ?int
    {
        return $this->rpe;
    }

    public function setRpe(?int $rpe): static
    {
        $this->rpe = $rpe;

        return $this;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): static
    {
        $this->workout = $workout;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }
}
