<?php

namespace App\Model\Category\Account\Filter;

use App\Entity\Category\Category;
use App\Entity\Category\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Category $category */
        $category = $this->request->getCurrentRequest()->get('category');

        $builder
            ->add('account_id', NumberType::class, [
                'attr' => ['placeholder' => 'ID'],
                'required' => false,
            ])
            ->add('login', TextType::class, [
                'attr' => ['placeholder' => 'Login'],
                'required' => false,
            ])
            ->add('password', TextType::class, [
                'attr' => ['placeholder' => 'Password'],
                'required' => false,
            ])
        ;

        /** @var Field $field */
        foreach ($category->getAllFields() as $field) {
            $key = "filter_field_{$field->getId()}";

            $defaultParams = [
                'attr' => ['placeholder' => $field->getName()],
                'required' => false,
            ];
            $params = [];

            if($field->isText()) {
                $type = TextType::class;
            } elseif($field->isTextarea()) {
                $type = TextareaType::class;
            } elseif ($field->isSelect()) {
                $values = $field->getVariables()->toArray();

                $type = ChoiceType::class;
                $params = [
                    'choices' => array_combine($values, $values),
                ];
            }

            $builder->add($key, $type, array_merge($defaultParams, $params));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
