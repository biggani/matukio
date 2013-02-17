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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class MatukioViewPayPalPayment extends JViewLegacy {

    public function display($tpl = NULL) {

        $booking_id = JFactory::getApplication()->input->get('booking_id', 0); // UUID

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
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio&view=eventlist"));
        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENT_PAYPAL_PAYMENT'), "");

        // TODO Add Taxes etc.
        $net_amount = $booking->payment_brutto;

        $tax_amount = 0; // empty
//        echo $net_amount;
//        die("asdf");

//        echo MatukioHelperPayment::getPayPalForm(MatukioHelperSettings::getSettings("paypal_address", ''),
//            $this->event->title,
//            $this->event->fees,
//            MatukioHelperSettings::getSettings("paypal_currency", 'EUR'),
//            "index.php?option=com_matukio&view=bookevent&task=paypal"
//        );

        $successurl = JURI::base().substr(JRoute::_("index.php?option=com_matukio&view=callback&booking_id=". $booking_id), strlen(JURI::base(true)) + 1);
        $cancelreturn = JURI::base().substr(JRoute::_("index.php?option=com_matukio&view=callback&task=cancel&booking_id=". $booking_id . "&return=1"), strlen(JURI::base(true)) + 1);        $item_number = $booking->nrbooked;

        //echo $cancelreturn . "<br>";
        //die($successurl);

        $this->event = $event;
        $this->user = $user;
        $this->booking = $booking;
        $this->merchant_address = MatukioHelperSettings::getSettings("paypal_address", 'paypal@compjoom.com');
        $this->currency = MatukioHelperSettings::getSettings("paypal_currency", 'EUR');
        $this->success_url = $successurl;
        $this->cancel_url = $cancelreturn;
        $this->item_number = $item_number;
        $this->net_amount = $net_amount;
        $this->tax_amount = $tax_amount;

        parent::display($tpl);
    }
}