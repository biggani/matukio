<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined( '_JEXEC' ) or die ( 'Restricted access' );

class MatukioHelperRoute
{
    private static $instance;

    public static function getEventRoute($id, $catid = 0, $art = 0, $uid = 0)
    {
        $needles = array(
            'event' => (int)$id,
            'category' => (int)$catid,
        );

        $link = 'index.php?option=com_matukio&view=event&catid=' . $catid . '&id=' . $id . "&art=" . $art;

        if(!empty($uid)){
            $link .= "&uid=" .$uid;
        }

        if ($item = MatukioHelperRoute::_findItem($needles)) {
            $link .= '&Itemid=' . $item->id;
        }
        return $link;
    }

    public static function _findItem($needles)
    {
        $component = JComponentHelper::getComponent('com_matukio');

        $componentId = 'component_id';

        $site = new JSite();

        //$menus = JApplication::getMenu('site', array());
        $menus = $site->getMenu('site', array());


        $items = $menus->getItems($componentId, $component->id);

        $match = null;

        foreach ($needles as $needle => $id) {
            if (count($items)) {
                foreach ($items as $item) {
                    if ($needle == 'event') {
                        if ((@$item->query['id'] == $id)) {
                            $match = $item;
                        }
//						if we don't find a match, try to set a default one
                        if (!isset($match)) {
                            if ((@$item->query['view'] == 'eventlist')) {
                                $match = $item;
                            }
                        }
                    }
                }
            }

            if (isset($match)) {
                break;
            }
        }

        return $match;
    }

}