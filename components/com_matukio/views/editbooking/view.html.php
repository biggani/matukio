<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class MatukioViewEditBooking extends JViewLegacy {

    function display($tpl = NULL) {

        $model = $this->getModel();

        $booking = $model->getBooking();
        // New booking
        if (!$booking) {
            $booking = JTable::getInstance('bookings', 'Table');
            $booking->id = 0;
            $booking->semid = JFactory::getApplication()->input->getInt("cid", 0);
            // echo $booking->semid;
            $booking->userid = 0;
            $booking->uuid = MatukioHelperPayment::getUuid(true);
            $booking->uid = 0;
        }

        // Check authorization
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        //$procent = JHTML::_('select.booleanlist', 'procent', 'class="inputbox"', $coupon->procent);

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

        if(MatukioHelperSettings::getSettings("payment_invoice", 1) == 0){
            $payment[] = array("name" => "payment_invoice", "title" => "COM_MATUKIO_PAYMENT_INVOICE");
        }

        $this->booking = $booking;
        $this->payment = $payment;

        parent::display($tpl);
    }
}
