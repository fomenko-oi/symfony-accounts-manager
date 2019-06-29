<?php

namespace App\UseCase\Category;

use App\Entity\Account\Account;
use App\Entity\Account\FieldValue;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use App\Model\Category\Account\AccountFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    /**
     * @var ExporterFactory
     */
    private $exporterFactory;
    /**
     * @var AccountFetcher
     */
    private $fetcher;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ExporterFactory $exporterFactory, AccountFetcher $fetcher, EntityManagerInterface $em)
    {
        $this->exporterFactory = $exporterFactory;
        $this->fetcher = $fetcher;
        $this->em = $em;
    }

    public function createField(Category $category, Field $field, FormInterface $form)
    {
        $variables = array_map(
            'trim',
            explode("\n", $form->get('variables_raw')->getData())
        );

        $field->setVariables($variables);

        $this->em->persist($field);

        $category->addCategoryField($field);
    }

    public function createAccount(Category $category, Account $account, FormInterface $form)
    {
        /** @var Field $field */
        foreach ($category->getAllFields() as $field) {
            $value = $form->get("field_{$field->getId()}")->getData();
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
    }

    public function export(Category $category, string $type): Response
    {
        $data = $category->getAccounts()->toArray();

        return $this->exporterFactory->getRenderer($type)->render($data);
    }
}
