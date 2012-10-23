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

jimport('joomla.application.component.controller');

class MatukioControllerEvent extends JController
{
    public function display()
    {
        MatukioHelperUtilsBasic::loginUser();

        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'event');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Event', 'MatukioModel');
        $view->setModel($model, true);

        $tmpl = MatukioHelperSettings::getSettings("event_template", "default");  // TODO change back to default

        $params = &JComponentHelper::getParams( 'com_matukio' );
        $menuitemid = JRequest::getInt( 'Itemid' );
        if ($menuitemid)
        {
            $menu = JSite::getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        $ptmpl = $params->get('template', '');

        if(!empty($ptmpl)) {
            $tmpl = $ptmpl;
        }

        $view->setLayout($tmpl);
        $view->display();
    }

    /**
     * OLD Booking method for old form
     * @return mixed
     */
    public function bookevent()
    {
        $database = &JFactory::getDBO();
        $my = &JFactory::getuser();
        $dateid = JRequest::getInt('dateid', 1);
        $id = JRequest::getInt('cid', 0);
        $uid = JRequest::getInt('uid', 0);
        $catid = JRequest::getInt('catid', 0);
        $search = JRequest::getVar('search', '');
        $limit = JRequest::getInt('limit', 5);
        $limitstart = JRequest::getInt('limitstart', 0);
        $nrbooked = JRequest::getInt('nrbooked', 0);
        $name = JRequest::getVar('name', '');
        $email = JRequest::getVar('email', '');

        $reason = "";

        // $booking = JTable::getInstance();

        // Werte des angegebenen Kurses ermitteln
        //$row = new mosSeminar($database);

        $row =& JTable::getInstance('matukio', 'Table');
        $row->load($id);

        $usrid = $my->id;
        $art = 2;
        if ($uid > 0) {  // WTF?!
            $usrid = $uid;

            $art = 4;
        }
        $sqlid = $usrid;

        if (($name != "" AND $email != "") OR $usrid == 0) { // WTF
            $usrid = 0;
            $sqlid = -1;
        }

        // Pruefung ob Buchung erfolgreich durchfuehrbar
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$id' AND userid='$sqlid'");
        $temp = $database->loadObjectList();
        $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
        $gebucht = $gebucht->booked;

        $allesok = 1;
        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');

        $pflichtfeld = false;

        $fields = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);

        for($i = 0; $i < 20; $i++) {
            //var_dump($fields);

            $test = $fields[0][$i];
//            echo "test: ";
//            echo $test;
            //var_dump($test);
//
//            die("asdfd");

           if(!empty($test)) {
               //echo "Test" . $i . ": " . $test;

               //die(asdf);
               $res = explode("|", $test);
               if(trim($res[1]) == "1") {
                    $value = JRequest::getVar(("zusatz" . ($i + 1)), '');
                    //echo "Val: " . $value;

                    if(empty($value)){
                        //echo "VALUE IS EMPTY " . $value . " i = " . $i;
                        $pflichtfeld = true;
                    }
               }
           }
        }

//        echo "Pflichtfeld " . $pflichtfeld;
//        die("asd");

        if($my->id > 0)
        {
            $name = $my->name;
            $email = $my->email;
        }

        if((empty($name) || empty($email))) {

            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_NO_NAME_OR_EMAIL');
        } else if ($pflichtfeld) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_REQUIRED_ADDITIONAL_FIELD_EMPTY');
        } else if (count($temp) > 0) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_REGISTERED_FOR_THIS_EVENT');
        } else if (MatukioHelperUtilsDate::getCurrentDate() > $row->booked) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_EXCEEDED');
        } else if ($row->maxpupil - $gebucht - $nrbooked < 0 && $row->stopbooking == 1) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
        } else if ($row->maxpupil - $gebucht - $nrbooked < 0 && $row->stopbooking == 0) {
            $allesok = 2;
            $ueber1 = JTEXT::_('COM_MATUKIO_ADDED_WAITLIST');
            $reason = JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
        }
        if ($art == 4) {
            $allesok = 1;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
        }
        //$link = JRoute::_('index.php?option=com_matukio&view=event&catid=' .$catid . '&id=' . $row->id);

        $link = JRoute::_(MatukioHelperRoute::getEventRoute($row->id, $catid), false);
        $msg = "";

        $neu = "";
        // Alles in Ordnung
        if ($allesok > 0) {

            // Buchung eintragen
            $neu =& JTable::getInstance('bookings', 'Table');

            //$neu = new mossembookings($database);
            if (!$neu->bind(JRequest::get( 'post' ))) {
                return JError::raiseError(500, $database->stderr());
            }
            $neu->semid = $id;
            $neu->userid = $usrid;

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

            if (!$neu->check()) {
                JError::raiseError(500, $database->stderr());
                exit();
            }
            if (!$neu->store()) {
                JError::raiseError(500, $database->stderr());
                exit();
            }
            $neu->checkin();

            $ueber1 = JText::_("COM_MATUKIO_BOOKING_WAS_SUCCESSFULL");

            if($usrid == 0){
                $usrid = $neu->id * -1;
            }


            //$link = JRoute::_('index.php?option=com_matukio&view=event&catid=' .$catid . '&id=' . $row->id. "&art=1&uid=" . $usrid);
            $link = JRoute::_(MatukioHelperRoute::getEventRoute($row->id, $catid, 1, $usrid), false);


            if ($art == 4) {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($id, $neu->id, 8);

//                $link = JRoute::_('index.php?option=com_matukio&view=event&id=' . $row->id . "&art=2");
                $link = JRoute::_(MatukioHelperRoute::getEventRoute($row->id, $catid, 2), false);

            } else {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($id, $neu->id, 1);
                $ueberschrift = array($ueber1, $reason);

                // Ausgabe des Kurses
                MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
                MatukioHelperUtilsBasic::expandPathway($row->title, "");
                if ($usrid == 0) {
                    $usrid = $neu->id * -1;
                }
            }

        } else {
//            echo $ueber1 . " " . $reason;
//            echo "saving failed";
            //$link = JRoute::_("index.php?option=com_matukio&view=event&id=" . $row->id);
            $link = JRoute::_(MatukioHelperRoute::getEventRoute($row->id, $catid), false);

        }


        $this->setRedirect($link, $ueber1 . " " . $reason);

        //HTML_FrontMatukio::sem_g002($art, $row, $usrid, $search, $catid, $limit, $limitstart, $dateid, $ueberschrift);
    }

}