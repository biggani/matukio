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

class MatukioViewCallback extends JViewLegacy {

    public function display($tpl = NULL) {

        $booking_id = JFactory::getApplication()->input->get('booking_id', 0); // UUID

        // 00f800eb-0a2b-4c23-9534-3a2d51e9c7ab

        $user = JFactory::getUser();
        $model = $this->getModel();
        $return = JFactory::getApplication()->input->get('return', 0);

        if(empty($booking_id)){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }




        $booking = $model->getBooking($booking_id);
        $event = $model->getEvent($booking->semid);

        if($return != 1) {
            $dispatcher	= JDispatcher::getInstance();
            $results = $dispatcher->trigger('onAfterPaidBooking', $booking, $event);
        }

        // echo $booking_id;
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENT_PAYPAL_PAYMENT'), "");

        $this->event = $event;
        $this->user = $user;
        $this->booking = $booking;

        parent::display($tpl);
    }
}