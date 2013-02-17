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

class MatukioControllerBookings extends JControllerLegacy
{

    public function __construct()
    {
        parent::__construct();
        // Register Extra tasks
        $this->registerTask('unpublish', 'publish');
        // Register Extra tasks
        $this->registerTask('addBooking', 'editBooking');
        $this->registerTask('apply', 'save');
    }

    /**
     * @param bool $cachable
     * @param bool $urlparams
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'bookings');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Bookings', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function remove()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');
        $db = JFactory::getDBO();

//        die("delet");

        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = "DELETE FROM #__matukio_bookings where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->execute()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
        }

        $this->setRedirect('index.php?option=com_matukio&view=bookings&uid=');
    }

    /**
     *
     */

    public function publish()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');

        if ($this->task == 'publish') {
            $publish = 1;
        } else {
            $publish = 0;
        }

        $msg = "";
        $tilesTable = JTable::getInstance('bookings', 'Table');
        $tilesTable->publish($cid, $publish);

        $link = 'index.php?option=com_matukio&view=bookings';

        $this->setRedirect($link, $msg);
    }

    // Edit Gallery

    public function editBooking()
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
        if(JFactory::getApplication()->input->getInt("oldform", 0) == 1){
            $this->saveOld();
            return;
        }

        $database = JFactory::getDBO();
        $art = 4; // Backend

        $event_id         = JFactory::getApplication()->input->getInt('event_id',          0);
        $uid              = JFactory::getApplication()->input->getInt('uid',               0);
        $uuid             = JFactory::getApplication()->input->getInt('uuid',              0);
        $nrbooked         = JFactory::getApplication()->input->getInt('nrbooked',          0);
        $userid           = JFactory::getApplication()->input->getInt('userid',            0);
        $id               = JFactory::getApplication()->input->getInt('id',                0);
        $sendConfirmation = JFactory::getApplication()->input->getInt('send_confirmation', 0);

        $payment_method = JFactory::getApplication()->input->get('payment', '', 'string');

        if (empty($event_id)) {
            JError::raiseError(404, 'COM_MATUKIO_NO_ID');
            return;
        }

        $event = JTable::getInstance('matukio', 'Table');
        $event->load($event_id);

        //var_dump($event);
        //echo $event_id;
        $reason = "";

        if (!empty($uid)) {
            if($uid < 0) {
                // Setting booking to changed booking
                $userid = $uid; // uid = Negativ

                $art = 4;
            }
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
//                    $value = JFactory::getApplication()->input->get(("zusatz" . ($i + 1)), '');
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

        if(empty($id)) {
            $neu->bookingdate = MatukioHelperUtilsDate::getCurrentDate();
        }
        $neu->name = MatukioHelperUtilsBasic::cleanHTMLfromText($firstname . " " . $lastname);
        $neu->email = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->email);

        // omg!
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
          if ($sendConfirmation == 1) {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 11);
          }

        } else {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 1);
            $ueberschrift = array($ueber1, $reason);

            if ($userid == 0) {
                $userid = $neu->id * -1;
            }
        }


        switch ($this->task) {
            case 'apply':
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_APPLY');
                $link = 'index.php?option=com_matukio&controller=bookings&task=editBooking&booking_id=' . $neu->id;
                break;

            case 'save':
            default:
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_SAVE');
                $link = 'index.php?option=com_matukio&task=2'; // Not implemented
                break;
        }

        $this->setRedirect($link, $msg);
    }

    function saveOld(){
        $database = JFactory::getDBO();
        $art = 4; // Backend

        $id = JFactory::getApplication()->input->getInt("id", 0);
        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = 0; // Hardcoded to get it working, could cause some new bugs
        $uuid = JFactory::getApplication()->input->getInt('uuid', 0);
        $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 0);
        $userid = JFactory::getApplication()->input->getInt('userid', 0);

//      var_dump($userid);

        if (empty($event_id)) {
            return JError::raiseError(404, 'COM_MATUKIO_NO_ID');
        }

        $event = JTable::getInstance('matukio', 'Table');
        $event->load($event_id);

        //var_dump($event);
        //echo $event_id;
        $reason = "";

        if (!empty($uid)) {
            if($uid < 0) {
                // Setting booking to changed booking
                $userid = $uid; // uid = Negativ

                $art = 4;
            }
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
//                    $value = JFactory::getApplication()->input->get(("zusatz" . ($i + 1)), '');
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

        $neu->userid = $userid;

        if(empty($id)) {
            $neu->bookingdate = MatukioHelperUtilsDate::getCurrentDate();
        }
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



        //die("adsf");

        if (!$neu->check()) {
            return JError::raiseError(500, $database->stderr());
        }
        if (!$neu->store()) {
            return JError::raiseError(500, $database->stderr());
        }
        $neu->checkin();

        $ueber1 = JText::_("COM_MATUKIO_BOOKING_WAS_SUCCESSFULL");


        if ($art == 4) {
            // Send new confirmation mail
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 11);

        } else {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 1);
            $ueberschrift = array($ueber1, $reason);
        }


        switch ($this->task) {
            case 'apply':
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_APPLY');
                $link = 'index.php?option=com_matukio&controller=bookings&task=editBooking&booking_id=' . $neu->id;
                break;

            case 'save':
            default:
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_SAVE');
                $link = 'index.php?option=com_matukio&task=2'; // Not implemented
                break;
        }

//        var_dump($userid);
//        die("as");
        $this->setRedirect($link, $msg);
    }

    function cancel()
    {
        $link = 'index.php?option=com_matukio&task=2';
        $this->setRedirect($link);
    }

}
