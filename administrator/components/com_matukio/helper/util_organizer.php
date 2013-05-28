<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 $
 **/

defined('_JEXEC') or die ('Restricted access');

class MatukioHelperOrganizer
{
    private static $instance;

    public static function getOrganizer($userid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio_organizers')->where('userId=' . $db->quote($userid));
        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }

    public static function getOrganizerId($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio_organizers')->where('id=' . $db->quote($id));
        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }

}