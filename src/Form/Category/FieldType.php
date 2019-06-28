<?php

namespace App\Form\Category;

use App\Entity\Category\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Title',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => Field::getTypesList()
            ])
            ->add('variables_raw', TextareaType::class, [
                'mapped' => false,
                'data' => implode("\n", $builder->getData()->getVariables()->toArray()),
                'required' => false,
                'attr' => ['placeholder' => implode("\r\n", ['var1', 'var2', 'var3']), 'rows' => 3]
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'Is Required',
                'required' => false,
                'attr' => ['class' => 'custom-control-input'],
                'label_attr' => ['class' => 'custom-control-label']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
