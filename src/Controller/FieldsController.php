<?php

namespace App\Controller;

use App\Entity\Category\Category;
use App\Entity\Category\Field;
use App\Form\Category\FieldType;
use App\UseCase\Category\CategoryService;
use App\UseCase\Category\Field\FieldService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FieldsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var FieldService
     */
    private $fieldService;

    public function __construct(EntityManagerInterface $em, CategoryService $categoryService, FieldService $fieldService)
    {
        $this->em = $em;
        $this->categoryService = $categoryService;
        $this->fieldService = $fieldService;
    }

    /**
     * @Route("categories/{category}/fields/{field}", name="category.field.edit")
     */
    public function edit(Category $category, Field $field, Request $request)
    {
        $form = $this->createForm(FieldType::class, $field);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->fieldService->updateField($field, $form);
                $this->em->flush();

                $this->addFlash('success', "Field #{$field->getId()} successful updated.");
                return $this->redirectToRoute('category.show', ['id' => $category->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('category.field.delete', ['id' => $field->getId()]);
            }
        }

        return $this->render('categories/fields/edit.html.twig', [
            'field' => $field,
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
}
