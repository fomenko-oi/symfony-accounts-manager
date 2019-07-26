<?php

namespace App\UseCase\User;

use App\Entity\User\Email;
use App\Entity\User\Form;
use App\Entity\User\Role;
use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;

class UserService
{
    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $users;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->users = $em->getRepository(User::class);
    }

    public function edit(User $user, Form $form): User
    {
        if($user->getEmail()->getValue() !== $form->email && $this->users->hasByEmail($form->email)) {
            throw new \InvalidArgumentException("User with email {$form->email} already exists.");
        }

        $user->setEmail(new Email($form->email));

        if($password = $form->password) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );
        }

        return $user;
    }

    public function changeRole(User $user, string $role): User
    {
        return $user->setRole(new Role($role));
    }
}
