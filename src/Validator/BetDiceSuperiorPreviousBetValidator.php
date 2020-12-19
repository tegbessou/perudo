<?php

namespace App\Validator;

use App\Entity\Bet;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @Annotation
 */
class BetDiceSuperiorPreviousBetValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof BetDiceSuperiorPreviousBet) {
            throw new UnexpectedTypeException($constraint, BetDiceSuperiorPreviousBet::class);
        }

        if (get_class($value) !== Bet::class) {
            throw new UnexpectedValueException($value, Bet::class);
        }

        if (empty($value->getGame()->getBets()->last())) {
            return;
        }

        $lastBet = $value->getGame()->getBets()->last();

        if ($lastBet->getDiceNumber() >= $value->getDiceNumber() && $lastBet->getDiceValue() >= $value->getDiceValue()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
