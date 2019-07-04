<?php

namespace App\Controller\Auth;

use App\Entity\User\Email;
use App\Entity\User\Form;
use App\Entity\User\User;
use App\Form\User\RegisterType;
use App\Security\AppAuthenticator;
use App\UseCase\User\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Guard\Provider\GuardAuthenticationProvider;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var RegisterService
     */
    private $registerService;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RegisterService $registerService, EntityManagerInterface $em)
    {
        $this->registerService = $registerService;
        $this->em = $em;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserProviderInterface $userProvider, GuardAuthenticatorHandler $guard, AppAuthenticator $authenticator)
    {
        $userForm = new Form();
        $form = $this->createForm(RegisterType::class, $userForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->registerService->register($userForm);
                $this->em->flush();

                $guard->authenticateUserAndHandleSuccess(
                    $userProvider->loadUserByUsername($user->getEmail()->getValue()),
                    $request,
                    $authenticator,
                    'main'
                );

                $this->addFlash('success', 'You are successful registered.');

                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());

                return $this->redirectToRoute('register');
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
