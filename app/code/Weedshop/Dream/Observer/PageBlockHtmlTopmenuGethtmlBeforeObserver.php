<?php

namespace Weedshop\Dream\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Tree\Node;


class PageBlockHtmlTopmenuGethtmlBeforeObserver implements ObserverInterface
{
    public function __construct()
    {
    }

    /**
     * @param EventObserver $observer
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(EventObserver $observer)
    {
        // get menu and tree from event
        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        // create temp menu item array
        $menuItems = [];

        // move the children from the menu into the temp array
        foreach ($menu->getChildren() as $child){
            $menuItems[] = $child;
            $menu->removeChild($child);
        }

        // construct array for parent menu item
        $data = [
            'name'      => __('Categories'),
            'id'        => 'categories',
            'url'       => '/categories',
            'is_active' => 0
        ];

        $parentNode = $this->addParentMenuItem($tree, $menu, $data);


        // add children to the parent
        foreach ($menuItems as $child){
            $parentNode->addChild($child);
        }

        // construct array for parent menu item
        $data = [
            'name'      => __('Your Account'),
            'id'        => 'your-account',
            'url'       => '/customer/account',
            'class'     => 'customer-account',
            'is_active' => 0
        ];

        $parentNode = $this->addParentMenuItem($tree, $menu, $data);

        // construct array for parent menu item
        $data = [
            'name'      => __('Today\'s Deals'),
            'id'        => 'today-deal',
            'url'       => '/sale',
            'is_active' => 0
        ];

        $parentNode = $this->addParentMenuItem($tree, $menu, $data);

        return $this;
    }

    /**
     * @param $tree
     * @param $menu
     * @return Node
     */
    protected function addParentMenuItem(&$tree, &$menu, $data)
    {
        // add parent to menu
        $parentNode = new Node($data, 'id', $tree, $menu);
        $menu->addChild($parentNode);

        return $parentNode;
    }
}
