<?php

namespace App\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;

class Form
{
    /**
     * @Assert\Email(groups={"register", "updating"})
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Please enter a password", groups={"updating"})
     */
    public $password;

    /**
     * @Assert\NotBlank(message="Please repeat a password", groups={"register"})
     * @Assert\Length(min="6", max="4096", minMessage="Your password should be at least {{ limit }} characters", groups={"register"})
     */
    public $plainPassword;

    public static function fillByUser(User $user): self
    {
        $data = new self();
        $data->email = $user->getEmail()->getValue();

        return $data;
    }
}
