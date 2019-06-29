<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Form\AccountType;
use App\Form\Category\CategoryType;
use App\UseCase\Account\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var AccountService
     */
    private $accountService;

    public function __construct(EntityManagerInterface $em, AccountService $accountService)
    {
        $this->em = $em;
        $this->accountService = $accountService;
    }

    /**
     * @Route("/accounts", name="accounts.index")
     */
    public function index()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();

        return $this->render('accounts/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/accounts/{id}/edit", name="accounts.edit")
     */
    public function edit(Account $account, Request $request)
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->accountService->editAccount($account, $form);
                $this->em->flush();

                $this->addFlash('success', 'Account success updated.');
                return $this->redirectToRoute('accounts.show', [
                    'id' => $account->getId()
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('accounts.show', [
                    'id' => $account->getId()
                ]);
            }
        }

        return $this->render('accounts/edit.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/accounts/{id}/show", name="accounts.show")
     */
    public function show(Account $account)
    {
        return $this->render('accounts/show.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * @Route("/accounts/create", name="accounts.create")
     */
    public function create(Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find(1);

        $account = new Account($category);
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->accountService->create($category, $account);
                $this->em->flush();

                $this->addFlash('success', 'Account was created.');

                return $this->redirectToRoute('accounts.index');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('accounts.index');
            }
        }

        return $this->render('accounts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
