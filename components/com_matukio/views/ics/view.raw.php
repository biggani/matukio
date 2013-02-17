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

class MatukioViewICS extends JViewLegacy {

    public function display($tpl = NULL) {

        //$this->agb', nl2br(MatukioHelperSettings::getSettings('agb_text = ''));

        if (MatukioHelperSettings::getSettings('frontend_usericsdownload', 1) == 0) {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
            return;
        }

        $cid = JFactory::getApplication()->input->getInt('cid', 0);

        $events = array();

        if(!empty($cid)) {
            $event = JTable::getInstance('Matukio', 'Table');
            $event->load($cid);
            $events[] = $event;
        } else {
            $database = JFactory::getDBO();
            $neudatum = MatukioHelperUtilsDate::getCurrentDate();
            // ICS File with all Events
            $where = array();
            $database->setQuery("SELECT id, access FROM #__categories WHERE extension = '" . JFactory::getApplication()->input->get('option') . "'");
            $cats = $database->loadObjectList();
            $allowedcat = array();

            foreach ($cats AS $cat) {
                if ($cat->access < 1) {
                    $allowedcat[] = $cat->id;
                }
            }
            if (count($allowedcat) > 0) {
                $allowedcat = implode(',', $allowedcat);
                $where[] = "a.catid IN (" . $allowedcat . ")";
            }

            $where[] = "a.published = '1'";
            $where[] = "a.end > '$neudatum'";
            $where[] = "a.booked > '$neudatum'";
            $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                    . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                    . "\nORDER BY a.publishdate DESC"
            );
            $events = $database->loadObjectList();
        }
//        var_dump($events);
//        die("asf");

        $this->events = $events;

        parent::display($tpl);
    }
}