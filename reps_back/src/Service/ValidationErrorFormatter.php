<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorFormatter
{
    public function format(ConstraintViolationListInterface $errors): array
    {
        $messages = [];

        foreach ($errors as $error){
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $messages;
    }
}