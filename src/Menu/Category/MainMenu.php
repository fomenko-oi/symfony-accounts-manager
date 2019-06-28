<?php

namespace App\Menu\Category;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MainMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav breadcrumb']);

        $menu->addChild('Home', ['uri' => '/'])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild('Categories')
            ->setAttribute('class', 'breadcrumb-item');


        return $menu;
    }
}
