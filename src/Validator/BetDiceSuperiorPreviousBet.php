<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BetDiceSuperiorPreviousBet extends Constraint
{
    public string $message = 'The dice number must be superior than previous';

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}