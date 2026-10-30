<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class WorkoutInput
{
    public function __construct(
        #[Assert\NotNull(message: 'La date de la séance est obligatoire')]
        public ?\DateTimeImmutable $performedAt = null,

        #[Assert\Positive]
        public ?int $duration = null,

        #[Assert\Length(max: 1000)]
        public ?string $notes = null
    ) {}
}