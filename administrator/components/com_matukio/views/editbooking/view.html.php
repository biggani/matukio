<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.0.0 Stable $
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class MatukioViewEditBooking extends JView {

    function display($tpl = null) {

        $model = $this->getModel();

        $booking = $model->getBooking();

        if (!$booking) {
            $booking = JTable::getInstance('bookings', 'Table');

            $event_id = JRequest::getInt("event_id", 0);
            $booking->semid = $event_id;
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

        $this->assignRef('booking', $booking);
        $this->assignRef('payment', $payment);


        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_MATUKIO_EDIT_BOOKING'), 'user');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.booking', true);
    }

}
