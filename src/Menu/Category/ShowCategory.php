<?php

namespace App\Menu\Category;

use App\Entity\Category\Category;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ShowCategory
{
    /**
     * @var FactoryInterface
     */
    private $factory;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(FactoryInterface $factory, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
    }

    public function build(): ItemInterface
    {
        /** @var Category $category */
        $category = $this->requestStack->getCurrentRequest()->get('category');

        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav breadcrumb']);

        $menu->addChild('Home', ['uri' => '/'])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild('Categories', ['route' => 'categories'])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild($category->getName())
            ->setAttribute('class', 'breadcrumb-item');

        return $menu;
    }
}
