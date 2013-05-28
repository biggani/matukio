<?php
/**
* Matukio
* @package Joomla!
* @Copyright (C) 2012 - Yves Hoppe - compojoom.com
* @All rights reserved
* @Joomla! is Free Software
* @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 2.0.0 $
**/
defined( '_JEXEC' ) or die ( 'Restricted access' );

jimport('joomla.application.component.view');

class MatukioViewPPayment extends JViewLegacy {

    public function display($tpl = NULL) {

        $uuid = JFactory::getApplication()->input->get('uuid', 0);

        if(empty($uuid)) {
            JError::raise(E_ERROR, 404, JText::_("COM_MATUKIO_NO_ID"));
            return;
        }

        $model = $this->getModel();

        $booking = $model->getBooking($uuid);
        $event = $model->getEvent($booking->semid);

        if(empty($booking)) {
            JError::raise(E_ERROR, 404, JText::_("COM_MATUKIO_NO_ID"));
            return;
        }

        $this->uuid = $uuid;
        $this->booking = $booking;
        $this->event = $event;

        parent::display($tpl);
    }
}