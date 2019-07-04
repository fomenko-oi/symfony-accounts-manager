<?php

namespace App\UseCase\User;

use App\Entity\User\Email;
use App\Entity\User\Form;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    public function register(Form $userForm): User
    {
        $user = new User(
            new Email($userForm->email),
            new \DateTimeImmutable()
        );

        $password = $this->passwordEncoder->encodePassword(
            $user,
            $userForm->password
        );

        $user->setPassword($password);

        $this->em->persist($user);

        return $user;
    }
}
