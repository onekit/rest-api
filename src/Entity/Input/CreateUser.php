<?php
namespace App\Entity\Input;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @DoctrineAssert\UniqueEntity("email", service="app.validator.unique_user")
 */
class CreateUser
{

    public $id;

    /**
     * @Assert\Email()
     * @Assert\NotNull()
     */
    public $email;

    /**
     * @Assert\Length(min=2, max=255)
     */
    public $firstName;

    /**
     * @Assert\Length(min=2, max=255)
     */
    public $lastName;

    /**
     * @Assert\Length(min=7, max=255)
     */
    public $phone;

    /**
     * @Assert\Length(min=6)
     * @Assert\NotNull()
     */
    public $password;

}