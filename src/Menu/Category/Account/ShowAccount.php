<?php

namespace App\Menu\Category\Account;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ShowAccount
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
        /** @var Account $account */
        $account = $this->requestStack->getCurrentRequest()->get('account');

        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav breadcrumb']);

        $menu->addChild('Home', ['uri' => '/'])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild('Categories', ['route' => 'categories'])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild($account->getCategory()->getName(), [
            'route' => 'category.show',
            'routeParameters' => ['id' => $account->getCategory()->getId()]
        ])
            ->setAttribute('class', 'breadcrumb-item');

        $menu->addChild("Account #{$account->getId()}")
            ->setAttribute('class', 'breadcrumb-item');

        return $menu;
    }
}
