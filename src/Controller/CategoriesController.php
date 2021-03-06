<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use App\Form\AccountType;
use App\Form\Category\CategoryType;
use App\Form\Category\FieldType;
use App\Model\Category\Account\AccountFetcher;
use App\Model\Category\CategoryFetcher;
use App\Model\Category\Account\Filter\Filter;
use App\Model\Category\Account\Filter\Form;
use App\UseCase\Category\CategoryService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    const ACCOUNTS_PER_PAGE = 10;
    const CATEGORIES_PER_PAGE = 10;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var AccountFetcher
     */
    private $accountFetcher;

    public function __construct(EntityManagerInterface $em, CategoryService $categoryService, AccountFetcher $accountFetcher)
    {
        $this->em = $em;
        $this->categoryService = $categoryService;
        $this->accountFetcher = $accountFetcher;
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
     * @Route("/categories/{id}/accounts/export/{type}", name="category.accounts.export")
     */
    public function exportAccounts(Category $category, string $type)
    {
        try {
            return $this->categoryService->export($category, $type);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('category.show', ['id' => $category->getId()]);
        }
    }

    /**
     * @Route("/categories/{id}/fields/{field_id}", name="category.field.delete", methods={"DELETE"})
     */
    public function deleteField(Category $category, Field $field)
    {
        $this->createForm(FieldType::class, $field);
    }

    /**
     * @Route("/categories/{id}", name="category.show", requirements={"id": "\d+"})
     * @ParamConverter("id", class="App\Entity\Category\Category", options={"id": "id"})
     */
    public function show(Category $category, Request $request, AccountFetcher $fetcher, PaginatorInterface $paginator)
    {
        $account = new Account($category);
        $account_form = $this->createForm(AccountType::class, $account);
        $account_form->handleRequest($request);

        if($account_form->isSubmitted() && $account_form->isValid()) {
            try {
                $this->categoryService->createAccount($category, $account, $account_form);
                $this->em->flush();

                $this->addFlash('success', 'Account success created.');
                return $this->redirectToRoute('category.show', [
                    'id' => $category->getId()
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('category.show', [
                    'id' => $category->getId()
                ]);
            }
        }

        $field = new Field();
        $fields_form = $this->createForm(FieldType::class, $field);
        $fields_form->handleRequest($request);

        if($fields_form->isSubmitted() && $fields_form->isValid()) {
            try {
                $this->categoryService->createField($category, $field, $fields_form);
                $this->em->flush();

                $this->addFlash('success', 'New tag added successful.');

                return $this->redirectToRoute('category.show', [
                    'id' => $category->getId()
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('category.show', [
                    'id' => $category->getId()
                ]);
            }
        }

        $filter = new Filter();
        $filter_form = $this->createForm(Form::class, $filter);
        $filter_form->handleRequest($request);

        $accounts = $this->accountFetcher->forCategory(
            $filter,
            $category,
            (int)$request->get('page', 1),
            self::ACCOUNTS_PER_PAGE
        );

        $accounts = $paginator->paginate(
            $category->getAccounts(),
            (int)$request->get('page', 1),
            self::ACCOUNTS_PER_PAGE
        );

        return $this->render('categories/show.html.twig', [
            'category' => $category,
            'accounts' => $accounts,
            'account_form' => $account_form->createView(),
            'fields_form' => $fields_form->createView(),
            'filter_form' => $filter_form->createView()
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
