<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class SidebarMenu
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav']);

        $menu->addChild('Dashboard', ['route' => 'home'])
            ->setExtra('icon', 'nav-icon icon-speedometer')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu->addChild('News', ['route' => 'news'])
            ->setExtra('routes', [
                ['route' => 'news'],
                ['pattern' => '/^news\..+/']
            ])
            ->setExtra('icon', 'nav-icon icon-briefcase')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
