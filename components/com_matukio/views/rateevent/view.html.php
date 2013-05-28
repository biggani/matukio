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

class MatukioViewRateEvent extends JViewLegacy {

    public function display($tpl = NULL) {

        $my = JFactory::getuser();

        $art = JFactory::getApplication()->input->getInt('art', 1);  // should be 1, else it's messages to participants
        $cid = JFactory::getApplication()->input->getInt('cid', 0);

        $model = &$this->getModel();

        if(empty($cid)){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }

        // Only registered users
        if($my->id == 0) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $event = $model->getEvent($cid);

        $database = JFactory::getDBO();

        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $cid . "' AND userid='" . $my->id . "'");
        $booking = $database->loadObject();
        //HTML_FrontMatukio::sem_g014($row, $buchung);

        $this->event = $event;
        $this->booking = $booking;

        parent::display($tpl);
    }
}