<?php

namespace App\Entity;

use App\Repository\TemplateExerciseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateExerciseRepository::class)]
class TemplateExercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $targetSets = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $repsMin = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $repsMax = null;

    #[ORM\ManyToOne(inversedBy: 'templateExercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkoutTemplate $template = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getTargetSets(): ?int
    {
        return $this->targetSets;
    }

    public function setTargetSets(int $targetSets): static
    {
        $this->targetSets = $targetSets;

        return $this;
    }

    public function getRepsMin(): ?int
    {
        return $this->repsMin;
    }

    public function setRepsMin(int $repsMin): static
    {
        $this->repsMin = $repsMin;

        return $this;
    }

    public function getRepsMax(): ?int
    {
        return $this->repsMax;
    }

    public function setRepsMax(int $repsMax): static
    {
        $this->repsMax = $repsMax;

        return $this;
    }

    public function getTemplate(): ?WorkoutTemplate
    {
        return $this->template;
    }

    public function setTemplate(?WorkoutTemplate $template): static
    {
        $this->template = $template;

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
