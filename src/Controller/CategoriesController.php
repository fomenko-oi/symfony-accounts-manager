<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use App\Entity\Account\FieldValue;
use App\Form\AccountType;
use App\Form\Category\CategoryType;
use App\Form\Category\FieldType;
use App\Model\Category\Account\AccountFetcher;
use App\Model\Category\CategoryFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    const ACCOUNTS_PER_PAGE = 10;
    const CATEGORIES_PER_PAGE = 10;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function index(CategoryFetcher $fetcher, Request $request)
    {
        $categories = $fetcher->all(
            (int)$request->get('page', 1),
            self::CATEGORIES_PER_PAGE
        );

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categories/{id}", name="category.show", requirements={"id": "\d+"})
     * @ParamConverter("id", class="App\Entity\Category\Category", options={"id": "id"})
     */
    public function show(Category $category, Request $request, AccountFetcher $fetcher)
    {
        $account = new Account($category);
        $account_form = $this->createForm(AccountType::class, $account);
        $account_form->handleRequest($request);

        if($account_form->isSubmitted() && $account_form->isValid()) {
            /** @var Field $field */
            foreach ($category->getAllFields() as $field) {
                $value = $account_form->get("field_{$field->getId()}")->getData();
                if($field->isSelect()) {
                    $value = $field->getVariables()[$value] ?? null;
                }

                $fieldValue = new FieldValue();
                $fieldValue->setField($field);
                $fieldValue->setAccount($account);
                $fieldValue->setValue($value);

                $account->addFieldValue($fieldValue);

                $this->em->persist($fieldValue);
            }

            $category->addAccount($account);

            $this->em->persist($account);
            $this->em->flush();

            $this->addFlash('success', 'Account success created.');

            return $this->redirectToRoute('category.show', ['id' => $category->getId()]);
        }

        $field = new Field();
        $fields_form = $this->createForm(FieldType::class, $field);
        $fields_form->handleRequest($request);

        if($fields_form->isSubmitted() && $fields_form->isValid()) {
            $variables = array_map('trim', explode("\n", $fields_form->get('variables_raw')->getData()));

            $field->setVariables($variables);

            $this->em->persist($field);

            $category->addCategoryField($field);

            $this->em->flush();

            $this->addFlash('success', 'New tag added successful.');

            return $this->redirectToRoute('category.show', ['id' => $category->getId()]);
        }

        $accounts = $fetcher->forCategory(
            $category->getId(),
            (int)$request->get('page', 1),
            self::ACCOUNTS_PER_PAGE
        );

        return $this->render('categories/show.html.twig', [
            'category' => $category,
            'accounts' => $accounts,
            'account_form' => $account_form->createView(),
            'fields_form' => $fields_form->createView(),
        ]);
    }

    /**
     * @Route("/categories/create", name="category.create")
     */
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category was created.');

            return $this->redirectToRoute('categories');
        }

        return $this->render('categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categories/{category}/fields", name="category.fields")
     */
    public function fields()
    {
        return $this->render('categories/field.html.twig');
    }
}
