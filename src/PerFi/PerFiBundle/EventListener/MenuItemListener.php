<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Avanzu\AdminThemeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

class MenuItemListener
{
    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    protected function getMenu(Request $request)
    {
        $menuItems = array(
            $homepage = new MenuItemModel('homepage', 'Home', 'homepage', [], 'iconclasses fa fa-home'),
            $accounts = new MenuItemModel('accounts', 'Accounts', null, [], 'iconclasses fa fa-bank'),
        );

        $accounts->addChild(
            new MenuItemModel('list_accounts', 'List of accounts', 'accounts', [], 'iconclasses fa fa-list')
        );

        $accounts->addChild(
            new MenuItemModel('create_account', 'Create account', 'create_account', [], 'iconclasses fa fa-plus')
        );

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    protected function activateByRoute($route, $items)
    {
        foreach($items as $item) {
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }
}
