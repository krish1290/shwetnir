<?php

namespace App\Http;

use Nwidart\Menus\Presenters\Presenter;

class AdminlteCustomPresenter extends Presenter
{
    /**
     * {@inheritdoc}.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="list-unstyled menu-categories" id="accordionExample">' . PHP_EOL;
    }

    /**
     * {@inheritdoc}.
     */
    public function getCloseTagWrapper()
    {
        return PHP_EOL . '</ul>' . PHP_EOL;
    }

    /**
     * {@inheritdoc}.
     */
    public function getMenuWithoutDropdownWrapper($item, $is_sub_item = false)
    {
        if (!str_contains($item->getAttributes(), 'has_sub_item')) {
            return '<li ' . $this->getActiveState($item, 'class="active"') . '> <a href="' . $item->getUrl() . '" '  . $this->getActiveState($item) . '>'  . $item->title  . '</a></li>' . PHP_EOL;
        }
        return '<li class="menu" > <a href="' . $item->getUrl() . '" ' . $item->getAttributes() . ' aria-expanded="false" class="dropdown-toggle"' . $this->getActiveState($item) . '>  <div class="">' . $item->getIcon() . ' <span>' . $item->title  . '</span></div></a></li>' . PHP_EOL;
    }


    /**
     * {@inheritdoc}.
     */
    public function getActiveState($item, $state = 'data-active=true')
    {
        return $item->isActive() ? $state : null;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param  string  $state
     * @return null|string
     */
    public function getActiveStateOnChild($item, $state = 'active')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * {@inheritdoc}.
     */
    public function getDividerWrapper()
    {
        return '<li class="divider"></li>';
    }

    /**
     * {@inheritdoc}.
     */
    public function getHeaderWrapper($item)
    {
        return '<li class="header">' . $item->title . '</li>';
    }

    /**
     * {@inheritdoc}.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        return '<li class="menu">
            <a' . str_replace('id="', 'href="#', $item->getAttributes()) . $this->getActiveStateOnChild($item, 'data-active="true"') . ' data-toggle="collapse"' . $this->getActiveStateOnChild($item, 'aria-expanded="true"') . ' class="dropdown-toggle" >
                <div class="">'
            . $item->getIcon() .
            ' <span>' . $item->title . '</span>
                </div>
                <div>
                    <i class="las la-angle-right sidemenu-right-icon"></i>
                </div>
            </a>
            <ul class="submenu list-unstyled collapse' . $this->getActiveStateOnChild($item, 'show') . '" ' . $item->getAttributes() . 'data-parent="#accordionExample" style="">
            ' . $this->getChildMenuItems($item) . '
            </ul>
	   </li>'
            . PHP_EOL;
    }

    /**
     * Get multilevel menu wrapper.
     *
     * @param  \Nwidart\Menus\MenuItem  $item
     * @return string`
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        return '<li class="menu"' . $item->getAttributes() . '>
		          <a href="#"' . $this->getActiveStateOnChild($item, 'data-active="true"') . ' data-toggle="collapse"' . $this->getActiveState($item, 'aria-expanded="true"') . ' class="dropdown-toggle" >
					' . $item->getIcon() . ' <span>' . $item->title . '</span>
			      	<span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
			      </a>
			      <ul class="treeview-menu">
			      	' . $this->getChildMenuItems($item, true) . '
			      </ul>
		      	</li>'
            . PHP_EOL;
    }
}
