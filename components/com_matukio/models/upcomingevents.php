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

class MatukioModelUpcomingEvents extends JModelLegacy {

    public function getEvents($catid, $limit = 10, $orderby = "begin ASC") {
        $db = JFactory::getDbo();
        //$query = $db->getQuery(true);
        if(!empty($catid[0])){
            //$query->select('*')->from('#__matukio')->where('catid='.$db->quote($catid) . " AND published = 1")->orderby($orderby);

            $cids = implode(',', $catid);

            $query = "SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid WHERE a.catid IN ("
                . $cids . ") AND a.published = 1 AND a.begin > '" . JFactory::getDate()->toSql() . "' ORDER BY a." . $orderby;
        } else {
            //$query->select('*')->from('#__matukio')->where("published = 1")->orderby($orderby);

            $query = "SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid WHERE a.published = 1  AND a.begin > '"
                . JFactory::getDate()->toSql() . "' ORDER BY " . $orderby;
        }
        $db->setQuery($query,0, $limit);
        return $db->loadObjectList();
    }

}