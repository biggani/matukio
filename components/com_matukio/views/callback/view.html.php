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

class MatukioViewCallback extends JView {

    public function display() {

        $booking_id = JRequest::getVar('booking_id', 0); // UUID

        // 00f800eb-0a2b-4c23-9534-3a2d51e9c7ab

        $user = JFactory::getUser();
        $model = $this->getModel();

        if(empty($booking_id)){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }

        $booking = $model->getBooking($booking_id);
        $event = $model->getEvent($booking->semid);

        // echo $booking_id;
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENT_PAYPAL_PAYMENT'), "");

        $this->assignRef('event', $event);
        $this->assignRef('user', $user);
        $this->assignRef('booking', $booking);

        parent::display();
    }
}