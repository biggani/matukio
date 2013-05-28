<?php
/**
* Matukio - Helper
* @package Joomla!
* @Copyright (C) 2012 - Yves Hoppe - compojoom.com
* @All rights reserved
* @Joomla! is Free Software
* @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 0.9.0 beta $
**/

defined('_JEXEC') or die();

class MatukioHelperPlugins
{
    /**
     * @param $event
     * @param array $data
     * @return array
     */
    public static function triggerPlugin($event,array &$data =array())
    {
        static $dispatcher = null;

        if($dispatcher===null){
            $dispatcher = JDispatcher::getInstance();
        }

        return $dispatcher->trigger($event, $data);
    }


}