<?php

namespace App\Form;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class)
            ->add('password', TextType::class)
        ;

        /** @var Category $category */
        $category = $builder->getData()->getCategory();
        $fields = $category->getAllFields();

        /** @var Field $field */
        foreach ($fields as $field) {
            $key = "field_{$field->getId()}";

            if($field->isText()) {
                $builder->add($key, TextType::class, [
                    'label' => $field->getName(),
                    'mapped' => false,
                    'required' => $field->isRequired(),
                ]);
            } elseif($field->isTextarea()) {
                $builder->add($key, TextareaType::class, [
                    'label' => $field->getName(),
                    'mapped' => false,
                    'required' => $field->isRequired(),
                ]);
            } elseif ($field->isSelect()) {
                $builder->add($key, ChoiceType::class, [
                    'label' => $field->getName(),
                    'mapped' => false,
                    'required' => $field->isRequired(),
                    'choices' => array_flip($field->getVariables()->toArray())
                ]);
            }
        }

        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'btn btn-success'],
            'label' => 'Add',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
