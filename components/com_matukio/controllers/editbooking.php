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
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = JFactory::getDBO();
        $art = 4; // Backend

        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = JFactory::getApplication()->input->getInt('uid', 0);
        $uuid = JFactory::getApplication()->input->getInt('uuid', 0);
        $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 0);
        $userid = JFactory::getApplication()->input->getInt('userid', 0);;

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
        // Checking old required fields - backward compatibilty - only frontend
//        for($i = 0; $i < 20; $i++) {
//
//            //var_dump($fields);
//
//            $test = $fields[0][$i];
//
//            if(!empty($test)) {
//                //echo "Test" . $i . ": " . $test;
//
//                //die(asdf);
//                $res = explode("|", $test);
//                if(trim($res[1]) == "1") {
//                    $value = JRequest::getVar(("zusatz" . ($i + 1)), '');
//                    //echo "Val: " . $value;
//
//                    if(empty($value)){
//                        //echo "VALUE IS EMPTY " . $value . " i = " . $i;
//                        $pflichtfeld = true;
//                    }
//                }
//            }
//        }

//        echo "Pflichtfeld " . $pflichtfeld;
//        die("asd");

//        if ($pflichtfeld) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_REQUIRED_ADDITIONAL_FIELD_EMPTY');
//        } else if (count($temp) > 0) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_REGISTERED_FOR_THIS_EVENT');
//        } else if (MatukioHelperUtilsDate::getCurrentDate() > $event->booked) {
//            echo "current: " .  MatukioHelperUtilsDate::getCurrentDate();
//            echo " booking: " . $event->booked;
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_EXCEEDED');
//        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 1) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
//        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 0) {
//            $allesok = 2;
//            $ueber1 = JTEXT::_('COM_MATUKIO_ADDED_WAITLIST');
//            $reason = JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
//        }
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

            if (!empty($event->fees)) {
                $neu->payment_method = $payment_method;
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
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = JFactory::getDBO();
        $art = 4; // Backend

        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = 0; // hardcoding, could cause some issues with booking user
        $uuid = JFactory::getApplication()->input->get('uuid', 0, 'string');
        $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 0);
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
        // Checking old required fields - backward compatibilty - only frontend
//        for($i = 0; $i < 20; $i++) {
//
//            //var_dump($fields);
//
//            $test = $fields[0][$i];
//
//            if(!empty($test)) {
//                //echo "Test" . $i . ": " . $test;
//
//                //die(asdf);
//                $res = explode("|", $test);
//                if(trim($res[1]) == "1") {
//                    $value = JRequest::getVar(("zusatz" . ($i + 1)), '');
//                    //echo "Val: " . $value;
//
//                    if(empty($value)){
//                        //echo "VALUE IS EMPTY " . $value . " i = " . $i;
//                        $pflichtfeld = true;
//                    }
//                }
//            }
//        }

//        echo "Pflichtfeld " . $pflichtfeld;
//        die("asd");

//        if ($pflichtfeld) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_REQUIRED_ADDITIONAL_FIELD_EMPTY');
//        } else if (count($temp) > 0) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_REGISTERED_FOR_THIS_EVENT');
//        } else if (MatukioHelperUtilsDate::getCurrentDate() > $event->booked) {
//            echo "current: " .  MatukioHelperUtilsDate::getCurrentDate();
//            echo " booking: " . $event->booked;
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_EXCEEDED');
//        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 1) {
//            $allesok = 0;
//            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//            $reason = JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
//        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 0) {
//            $allesok = 2;
//            $ueber1 = JTEXT::_('COM_MATUKIO_ADDED_WAITLIST');
//            $reason = JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
//        }
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


        //$link = 'index.php?option=com_matukio&view=participants' . $neu->id;

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