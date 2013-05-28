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

defined('_JEXEC') or die();

class MatukioHelperCategories {

    private static $instance;

    public static function getCategoryName($id){
        $database = JFactory::getDBO();
        $database->setQuery("Select id, title FROM #__categories WHERE id = " . $id);

        $cat = $database->loadObject();

        return $cat->title;
    }

    public static function getCategoryAlias($id){
        $database = JFactory::getDBO();
        $database->setQuery("Select id, alias FROM #__categories WHERE id = " . $id);

        $cat = $database->loadObject();

        return $cat->alias;
    }

}