<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Form\AccountType;
use App\Form\Category\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountsController extends AbstractController
{
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
            $account->setCategory($category);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category was created.');

            return $this->redirectToRoute('accounts.index');
        }

        return $this->render('accounts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
