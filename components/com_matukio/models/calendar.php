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

class MatukioModelCalendar extends JModelLegacy {

    /**
     * @deprecated
     * Not used any more.. all Ajax driven!
     * @param $catid
     * @param int $limit
     * @param string $orderby
     * @return mixed
     */
    public function getEvents($catid, $limit = 100, $orderby = "begin ASC") {
        $db = JFactory::getDbo();
        $groups	= implode(',', JFactory::getUser()->getAuthorisedViewLevels());

        if(!empty($catid)){

            $query = "SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid WHERE a.catid = "
                .$db->quote($catid) . " AND cat.access in (" . $groups . ") AND a.published = 1 ORDER BY a." . $orderby; // show also old events AND a.begin > '" . JFactory::getDate()->toMySQL() . "'
        } else {
            $query = "SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid WHERE a.published = 1 AND cat.access in (" . $groups . ") ORDER BY " . $orderby;
        }

        $db->setQuery($query,0, $limit);
        return $db->loadObjectList();
    }

}