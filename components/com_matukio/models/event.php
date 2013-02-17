<?php
/**
 * @author Daniel Dimitrov
 * @date: 29.03.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class MatukioModelEvent extends JModelLegacy {

    public function getItem($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio')->where('id='.$db->quote($id));
        $db->setQuery($query,0,1);
        return $db->loadObject();
    }
}