<?php

namespace App\Entity\User;

class Email
{
    /**
     * @var string
     */
    private $email;

    public function __construct(string $email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Wrong email {$email}.");
        }

        $this->email = mb_strtolower(trim($email), 'UTF-8');
    }

    public function getValue()
    {
        return $this->email;
    }
}
