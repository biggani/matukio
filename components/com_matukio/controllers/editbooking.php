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
jimport('joomla.application.component.controller');

class MatukioControllerEditBooking extends JControllerLegacy
{

    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = 'editbooking'; // hardcoede
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('editbooking');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }


    function save()
    {
        // Check authorization
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = JFactory::getDBO();
        $art = 4; // Backend

        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = JFactory::getApplication()->input->getInt('uid', 0);
        $uuid = JFactory::getApplication()->input->getInt('uuid', 0);
        $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 1);
        $userid = JFactory::getApplication()->input->getInt('userid', 0);

        $payment_method = JFactory::getApplication()->input->get('payment', '', 'string');

        if (empty($event_id)) {
            return JError::raiseError(404, 'COM_MATUKIO_NO_ID');
        }

        $event = JTable::getInstance('matukio', 'Table');
        $event->load($event_id);

        //var_dump($event);
        //echo $event_id;
        $reason = "";

        if (!empty($uid)) {
            // Setting booking to changed booking
            $userid = $uid; // uid = Negativ

            $art = 4;
        }

        if ($art == 4) {
            $allesok = 1;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
        }

        // Buchung eintragen
        $neu = JTable::getInstance('bookings', 'Table');

        //$neu = new mossembookings($database);
        if (!$neu->bind(JRequest::get('post'))) {
            return JError::raiseError(500, $database->stderr());
        }
        $neu->semid = $event->id;
//
//        if(empty($userid)) {
//            $userid = $event_id * -1;
//        }

        $neu->userid = $userid;

        $firstname = JFactory::getApplication()->input->get('firstname', '', 'string');
        $lastname = JFactory::getApplication()->input->get('lastname', '', 'string');

        $neu->bookingdate = MatukioHelperUtilsDate::getCurrentDate();
        $neu->name = MatukioHelperUtilsBasic::cleanHTMLfromText($firstname . " " . $lastname);
        $neu->email = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->email);
        $neu->zusatz1 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz1);
        $neu->zusatz2 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz2);
        $neu->zusatz3 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz3);
        $neu->zusatz4 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz4);
        $neu->zusatz5 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz5);
        $neu->zusatz6 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz6);
        $neu->zusatz7 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz7);
        $neu->zusatz8 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz8);
        $neu->zusatz9 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz9);
        $neu->zusatz10 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz10);
        $neu->zusatz11 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz11);
        $neu->zusatz12 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz12);
        $neu->zusatz13 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz13);
        $neu->zusatz14 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz14);
        $neu->zusatz15 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz15);
        $neu->zusatz16 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz16);
        $neu->zusatz17 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz17);
        $neu->zusatz18 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz18);
        $neu->zusatz19 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz19);
        $neu->zusatz20 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz20);

        if(empty($neu->uuid)) {
            $neu->uuid = MatukioHelperPayment::getUuid(true);
        }

        $fields = MatukioHelperUtilsBooking::getBookingFields();

        if (!empty($fields)) {

            $newfields = "";

            for ($i = 0; $i < count($fields); $i++) {
                $field = $fields[$i];
                $name = $field->field_name;
                $newfields .= $field->id;
                $newfields .= "::";
                $newfields .= JFactory::getApplication()->input->get($name, '', 'string');
                $newfields .= ";";
            }

//            var_dump($newfields);
//            die();

            $neu->newfields = $newfields;

            if(!empty($event->fees)){
                $neu->payment_method = $payment_method;
                $payment_brutto = $event->fees * $neu->nrbooked;
                $coupon_code = $neu->coupon_code;

                if(!empty($coupon_code)) {

                    $cdate = new DateTime();

                    $db = JFactory::getDBO();
                    $query= $db->getQuery(true);
                    $query->select('*')->from('#__matukio_booking_coupons')
                        ->where('code = ' . $db->quote($coupon_code) . ' AND published = 1 AND published_up < '
                        . $db->quote($cdate->format('Y-m-d H:i:s')) . " AND published_down > " . $db->quote($cdate->format('Y-m-d H:i:s')));

                    //echo $query;
                    $db->setQuery( $query );
                    $coupon = $db->loadObject();

                    //var_dump($coupon);

                    if(!empty($coupon)){
                        if($coupon->procent == 1){
                            // Get a procent value
                            $payment_brutto = round($payment_brutto * ((100 - $coupon->value) / 100), 2);
                        } else {
                            $payment_brutto = $payment_brutto - $coupon->value;
                        }
                    } else {
                        // Raise an error
                        JError::raise(E_ERROR, 500, JText::_("COM_MATUKIO_INVALID_COUPON_CODE"));
                    }
                }
                $neu->payment_brutto = $payment_brutto;
            }
        }

        if (!$neu->check()) {
            return JError::raiseError(500, $database->stderr());
        }
        if (!$neu->store()) {
            return JError::raiseError(500, $database->stderr());
        }
        $neu->checkin();

        $ueber1 = JText::_("COM_MATUKIO_BOOKING_WAS_SUCCESSFULL");

        if ($userid == 0) {
            $userid = $neu->id * -1;
        }

        if ($art == 4) {
            // Send new confirmation mail
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 11);

        } else {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 1);
            $ueberschrift = array($ueber1, $reason);

            if ($userid == 0) {
                $userid = $neu->id * -1;
            }
        }


        //$link = 'index.php?option=com_matukio&view=participants' . $neu->id;

        $viewteilnehmerlink = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $event->id . "&art=2");

        $msg = JText::_("COM_MATUKIO_BOOKING_EDITED");

        $this->setRedirect($viewteilnehmerlink, $msg);
    }

    /**
     * Save old booking form event
     * @return object
     */
    function saveoldevent(){
        // Check authorization
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = JFactory::getDBO();
        $art = 4; // Backend

        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = 0; // hardcoding, could cause some issues with booking user
        $uuid = JFactory::getApplication()->input->get('uuid', 0, 'string');
        $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 1);
        $userid = JFactory::getApplication()->input->getInt('userid', 0);;

        //$payment_method = JRequest::getVar('payment', '');

        if (empty($event_id)) {
            return JError::raiseError(404, 'COM_MATUKIO_NO_ID');
        }

        $event = JTable::getInstance('matukio', 'Table');
        $event->load($event_id);

        //var_dump($event);
        //echo $event_id;
        $reason = "";

        if (!empty($uid)) {
            // Setting booking to changed booking
            $userid = $uid; // uid = Negativ

            $art = 4;
        }

        if ($art == 4) {
            $allesok = 1;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
        }

        // Buchung eintragen
        $neu = JTable::getInstance('bookings', 'Table');

        //$neu = new mossembookings($database);
        if (!$neu->bind(JRequest::get('post'))) {
            return JError::raiseError(500, $database->stderr());
        }
        $neu->semid = $event->id;

        $neu->userid = $userid;

        $neu->bookingdate = MatukioHelperUtilsDate::getCurrentDate();
        $neu->name = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->name);
        $neu->email = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->email);
        $neu->zusatz1 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz1);
        $neu->zusatz2 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz2);
        $neu->zusatz3 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz3);
        $neu->zusatz4 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz4);
        $neu->zusatz5 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz5);
        $neu->zusatz6 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz6);
        $neu->zusatz7 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz7);
        $neu->zusatz8 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz8);
        $neu->zusatz9 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz9);
        $neu->zusatz10 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz10);
        $neu->zusatz11 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz11);
        $neu->zusatz12 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz12);
        $neu->zusatz13 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz13);
        $neu->zusatz14 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz14);
        $neu->zusatz15 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz15);
        $neu->zusatz16 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz16);
        $neu->zusatz17 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz17);
        $neu->zusatz18 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz18);
        $neu->zusatz19 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz19);
        $neu->zusatz20 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz20);
        $neu->nrbooked = $nrbooked;

        if (!empty($event->fees)) {
            $neu->payment_method = "cash";

            if($nrbooked > 0)
                $neu->payment_brutto = $event->fees * $nrbooked;
            else
                $neu->payment_brutto = $event->fees;
        }

        if(empty($neu->uuid)) {
            $neu->uuid = MatukioHelperPayment::getUuid(true);
        }

        if (!$neu->check()) {
            return JError::raiseError(500, $database->stderr());
        }
        if (!$neu->store()) {
            return JError::raiseError(500, $database->stderr());
        }
        $neu->checkin();

        $ueber1 = JText::_("COM_MATUKIO_BOOKING_WAS_SUCCESSFULL");

        if ($userid == 0) {
            $userid = $neu->id * -1;
        }

        if ($art == 4) {
            // Send new confirmation mail
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 11);

        } else {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 1);
            $ueberschrift = array($ueber1, $reason);

            if ($userid == 0) {
                $userid = $neu->id * -1;
            }
        }


        $viewteilnehmerlink = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $event->id . "&art=2");

        $msg = JText::_("COM_MATUKIO_BOOKING_EDITED");

        $this->setRedirect($viewteilnehmerlink, $msg);
    }

    function cancel()
    {
        $link = 'index.php?option=com_matukio&view=bookings';
        $this->setRedirect($link);
    }

}