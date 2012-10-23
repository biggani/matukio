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

jimport('joomla.application.component.view');

class MatukioViewRSS extends JView {

    public function display() {

        //$this->assignRef('agb', nl2br(MatukioHelperSettings::getSettings('agb_text', '')));

        if (MatukioHelperSettings::getSettings('rss_feed', 1) == 0) {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
            return;
        }
        $database = JFactory::getDBO();
        $neudatum = MatukioHelperUtilsDate::getCurrentDate();
        $where = array();
        $database->setQuery("SELECT id, access FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
        $cats = $database->loadObjectList();
        $allowedcat = array();

        foreach ($cats AS $cat) {
            if ($cat->access < 1) {
                $allowedcat[] = $cat->id;
            }
        }
        if (count($allowedcat) > 0) {
            $allowedcat = implode(',', $allowedcat);
            $where[] = "a.catid IN ($allowedcat)";
        }

        $where[] = "a.published = '1'";
        $where[] = "a.end > '$neudatum'";
        $where[] = "a.booked > '$neudatum'";
        $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . "\nORDER BY a.publishdate DESC"
        );
        $rows = $database->loadObjectList();
//        var_dump($rows);
//        die("asdf");

        $this->assignRef('rows', $rows);
        parent::display();
    }
}