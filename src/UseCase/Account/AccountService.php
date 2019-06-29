<?php

namespace App\UseCase\Account;

use App\Entity\Account\Account;
use App\Entity\Account\FieldValue;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class AccountService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(Category $category, Account $account)
    {
        $account->setCategory($category);

        $this->em->persist($category);
    }

    public function editAccount(Account $account, FormInterface $form)
    {
        foreach ($account->getFieldsValues() as $fieldValue) {
            $account->removeFieldValue($fieldValue);
            $this->em->remove($fieldValue);
        }

        /** @var Field $field */
        foreach ($account->getCategory()->getAllFields() as $field) {
            $value = $form->get("field_{$field->getId()}")->getData();
            if($field->isSelect()) {
                $value = $field->getVariables()[$value] ?? null;
            }

            $fieldValue = new FieldValue();
            $fieldValue->setField($field);
            $fieldValue->setAccount($account);
            $fieldValue->setValue($value);

            $this->em->persist($fieldValue);

            $account->addFieldValue($fieldValue);
        }
    }
}
