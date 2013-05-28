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


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class MatukioModelOrganizer extends JModelLegacy {

    /**
     * @param $id
     * @return mixed
     */
    public function getOrganizer($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio_organizers')->where('id='.$db->quote($id));
        $db->setQuery($query,0,1);
        return $db->loadObject();
    }
}
