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

class MatukioViewRateEvent extends JView {

    public function display() {

        $my = &JFactory::getuser();

        $art = JRequest::getInt('art', 1);  // should be 1, else it's messages to participants
        $cid = JRequest::getInt('cid', 0);

        $model = &$this->getModel();

        if(empty($cid)){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }

        $event = $model->getEvent($cid);

        $database = &JFactory::getDBO();
//        $cid = JRequest::getInt('cid', 0);
//        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//        $rows = $database->loadObjectList();
//        $row = &$rows[0];

        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $cid . "' AND userid='" . $my->id . "'");
        $booking = $database->loadObject();
        //HTML_FrontMatukio::sem_g014($row, $buchung);

        $this->assignRef('event', $event);
        $this->assignRef('booking', $booking);

        parent::display();
    }
}