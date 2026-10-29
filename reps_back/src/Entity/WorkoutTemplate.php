<?php

namespace App\Entity;

use App\Repository\WorkoutTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutTemplateRepository::class)]
class WorkoutTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'workoutTemplates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, TemplateExercise>
     */
    #[ORM\OneToMany(targetEntity: TemplateExercise::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $templateExercises;

    public function __construct()
    {
        $this->templateExercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, TemplateExercise>
     */
    public function getTemplateExercises(): Collection
    {
        return $this->templateExercises;
    }

    public function addTemplateExercise(TemplateExercise $templateExercise): static
    {
        if (!$this->templateExercises->contains($templateExercise)) {
            $this->templateExercises->add($templateExercise);
            $templateExercise->setTemplate($this);
        }

        return $this;
    }

    public function removeTemplateExercise(TemplateExercise $templateExercise): static
    {
        if ($this->templateExercises->removeElement($templateExercise)) {
            // set the owning side to null (unless already changed)
            if ($templateExercise->getTemplate() === $this) {
                $templateExercise->setTemplate(null);
            }
        }

        return $this;
    }
}
