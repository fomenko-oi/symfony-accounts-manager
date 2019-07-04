<?php

namespace App\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;

class Form
{
    /**
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Please enter a password")
     */
    public $password;

    /**
     * @Assert\NotBlank(message="Please repeat a password")
     * @Assert\Length(min="6", max="4096", minMessage="Your password should be at least {{ limit }} characters")
     */
    public $plainPassword;
}
