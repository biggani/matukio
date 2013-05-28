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

class MatukioViewBookEvent extends JViewLegacy {

    public function display($tpl = NULL) {

        $cid = JFactory::getApplication()->input->getInt('cid', 0);
        $user = JFactory::getUser();
        $uid = JFactory::getApplication()->input->getInt('uid', 0);     // Booking id!!   Dirk.. WTF?!?!?!?!?!

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

//        $payment = array();
//
//        // TODO Optimize
//
//        if(MatukioHelperSettings::getSettings("payment_cash", 1) == 1){
//            $payment[] = array("name" => "payment_cash", "title" => "COM_MATUKIO_PAYMENT_CASH");
//        }
//
//        if(MatukioHelperSettings::getSettings("payment_banktransfer", 1) == 1){
//            $payment[] = array("name" => "payment_banktransfer", "title" => "COM_MATUKIO_PAYMENT_BANKTRANSFER");
//        }
//
//        if(MatukioHelperSettings::getSettings("payment_paypal", 1) == 1){
//            $payment[] = array("name" => "payment_paypal", "title" => "COM_MATUKIO_PAYMENT_PAYPAL");
//        }
//
//        if(MatukioHelperSettings::getSettings("payment_invoice", 1) == 1){
//            $payment[] = array("name" => "payment_invoice", "title" => "COM_MATUKIO_PAYMENT_INVOICE");
//        }

        $fields_p1 = MatukioHelperUtilsBooking::getBookingFields(1);
        $fields_p2 = MatukioHelperUtilsBooking::getBookingFields(2);
        $fields_p3 = MatukioHelperUtilsBooking::getBookingFields(3);

        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio&view=eventlist"));
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENT_BOOKING'), "");

        $dispatcher = JDispatcher::getInstance();

        $pplugins = array("paypal", "paypalpro", "bycheck", "byorder", "linkpoint", "ccavenue", "payu", "authorizenet");
        JPluginHelper::importPlugin("payment");
        $gateways = $dispatcher->trigger('onTP_GetInfo', array($pplugins));

        /**
         * array(6) { [0]=> object(stdClass)#670 (2) { ["name"]=> string(11) "PayBy Check" ["id"]=> string(7) "bycheck" }
         * [1]=> object(stdClass)#669 (2) { ["name"]=> string(20) "PayBy Purchase Order" ["id"]=> string(7) "byorder" }
         * [2]=> object(stdClass)#668 (2) { ["name"]=> string(9) "Linkpoint" ["id"]=> string(9) "linkpoint" }
         * [3]=> object(stdClass)#667 (2) { ["name"]=> string(6) "Paypal" ["id"]=> string(6) "paypal" }
         * [4]=> object(stdClass)#666 (2) { ["name"]=> string(9) "Paypalpro" ["id"]=> string(9) "paypalpro" }
         * [5]=> object(stdClass)#665 (2) { ["name"]=> string(39) "Payu Credit Card/Debit Card/Net Banking" ["id"]=> string(4) "payu" } }
         */

        $payment = array();

        foreach($gateways as $gway) {
            $payment[] = array("name" => $gway->id, "title" => $gway->name);
        }

        if(empty($payment)){
            // If no payment then set Steps to 2 :)
            $steps = 2;
        }

        $this->gateways = $gateways;
        $this->event = $event;
        $this->uid = $uid;
        $this->user = $user;
        $this->steps = $steps;
        $this->payment = $payment;
        $this->fields_p1 = $fields_p1;
        $this->fields_p2 = $fields_p2;
        $this->fields_p3 = $fields_p3;

        parent::display($tpl);
    }
}