<?php

namespace App\UseCase\Category\Field;

use App\Entity\Category\Category;
use App\Entity\Category\Field;
use App\Model\Category\Account\AccountFetcher;
use App\UseCase\Category\ExporterFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class FieldService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function updateField(Field $field, FormInterface $form)
    {
        $variables = array_map(
            'trim',
            explode("\n", $form->get('variables_raw')->getData())
        );

        $field->setVariables($variables);
    }
}
