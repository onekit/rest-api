<?php
namespace App\Validator\Constraints;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class UniqueUserValidator extends UniqueEntityValidator
{

    /**
     * @param object $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $user = new User();
        if (property_exists($value, 'email')) {
            $user->setEmail($value->email);
        }
        parent::validate($user, $constraint);
    }
}