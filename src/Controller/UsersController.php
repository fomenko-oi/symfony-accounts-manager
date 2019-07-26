<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\User\Form;
use App\Entity\User\User;
use App\Form\AccountType;
use App\Form\Category\CategoryType;
use App\Form\User\EditType;
use App\Form\User\RegisterType;
use App\Form\User\UserType;
use App\UseCase\Account\AccountService;
use App\UseCase\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="users.")
 * @IsGranted("ROLE_ADMIN")
 */
class UsersController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $users;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->users = $em->getRepository(User::class);
        $this->userService = $userService;
    }

    /**
     * @Route("/{user}/edit", name="edit")
     */
    public function edit(User $user, Request $request)
    {
        $userForm = Form::fillByUser($user);
        $form = $this->createForm(EditType::class, $userForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->edit($user, $userForm);

                $this->em->flush();

                $this->addFlash('success', 'User successful updated.');

                return $this->redirectToRoute('users.show', ['user' => $user->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());

                return $this->redirectToRoute('users.show', ['user' => $user->getId()]);
            }
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cahge_role/{user}/{role}", name="change_role")
     */
    public function changeRole(User $user, string $role)
    {
        try {
            $this->userService->changeRole($user, $role);
            $this->em->flush();

            $this->addFlash('success', "Role {$role} was successful applied to user.");

            return $this->redirectToRoute('users.show', ['user' => $user->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('users.show', ['user' => $user->getId()]);
        }
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        /**
         * @var User[]
         */
        $users = $this->users->findBy([], ['id' => 'DESC']);

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{user}", name="show")
     */
    public function show(User $user)
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }
}
