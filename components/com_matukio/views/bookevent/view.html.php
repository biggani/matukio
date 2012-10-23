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

class MatukioViewBookEvent extends JView {

    public function display() {

        $cid = JRequest::getInt('cid', 0);
        $user = JFactory::getUser();
        $uid = JRequest::getInt('uid', 0);     // Booking id!!   Dirk.. WTF?!?!?!?!?!

        $model = $this->getModel();

        if(empty($cid)){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }

        $event = $model->getItem($cid);
        // With Payment Step or without?
        $steps = 3;

        if(empty($event->fees)){
            $steps = 2;
        }

        $payment = array();

        // TODO Optimize

        if(MatukioHelperSettings::getSettings("payment_cash", 1) == 1){
            $payment[] = array("name" => "payment_cash", "title" => "COM_MATUKIO_PAYMENT_CASH");
        }

        if(MatukioHelperSettings::getSettings("payment_banktransfer", 1) == 1){
            $payment[] = array("name" => "payment_banktransfer", "title" => "COM_MATUKIO_PAYMENT_BANKTRANSFER");
        }

        if(MatukioHelperSettings::getSettings("payment_paypal", 1) == 1){
            $payment[] = array("name" => "payment_paypal", "title" => "COM_MATUKIO_PAYMENT_PAYPAL");
        }

        if(MatukioHelperSettings::getSettings("payment_invoice", 1) == 1){
            $payment[] = array("name" => "payment_invoice", "title" => "COM_MATUKIO_PAYMENT_INVOICE");
        }


        if(empty($payment)){
            // If no payment then set Steps to 2 :)
            $steps = 2;
        }

        $fields_p1 = MatukioHelperUtilsBooking::getBookingFields(1);
        $fields_p2 = MatukioHelperUtilsBooking::getBookingFields(2);
        $fields_p3 = MatukioHelperUtilsBooking::getBookingFields(3);

        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio&view=eventlist"));
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENT_BOOKING'), "");

        $this->assignRef('event', $event);
        $this->assignRef('uid', $uid);
        $this->assignRef('user', $user);
        $this->assignRef('steps', $steps);
        $this->assignRef('payment', $payment);
        $this->assignRef('fields_p1', $fields_p1);
        $this->assignRef('fields_p2', $fields_p2);
        $this->assignRef('fields_p3', $fields_p3);

        parent::display();
    }
}