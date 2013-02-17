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

class MatukioControllerBookEvent extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'BookEvent');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('BookEvent', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    /**
     * NEW Booking method for old and new form
     * @return mixed
     */
    public function book()
    {
        $database = JFactory::getDBO();
        $post = JRequest::get( 'post' );
        $my = JFactory::getuser();

        $input = JFactory::getApplication()->input;

        $event_id = $input->getInt('event_id', 0);
        $uid = $input->getInt('uid', 0);
        $uuid = $input->get('uuid', 0, 'string');

        $nrbooked = $input->getInt('nrbooked', 1);
        $catid = $input->getInt('catid', 0);
        $payment_method = $input->get('payment', '', 'string');
        $agb = $input->get('agb', '', 'string');

        $dispatcher	= JDispatcher::getInstance();

//        echo $agb;
//        die("asdf");
        if(empty($event_id)){
            JError::raiseError(404, 'COM_MATUKIO_NO_ID');
            return;
        }

        $event = JTable::getInstance('matukio', 'Table');
        $event->load($event_id);

        //var_dump($event);
        //echo $event_id;

        $userid = $my->id;
        $reason = "";

        $art = 2;
        if (!empty($uid)) {
            // Setting booking to changed booking
            $userid = $uid; // uid = Negativ
            $art = 4;
        }

        // Pruefung ob Buchung erfolgreich durchfuehrbar

        if(empty($uid)){
            if(!empty($userid)) {
                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $event_id . "' AND userid='" . $userid . "'");
            }
        } else {
            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $uid . "'");
        }

        if(!empty($userid) || !empty($uid)) {
            $temp = $database->loadObjectList();
        } else {
            $temp = null;
        }

        //var_dump($temp);
        //echo "QUery: " . $database->getQuery();

        $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($event);
        $gebucht = $gebucht->booked;

        $allesok = 1;
        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');

        $pflichtfeld = false;

        $fields = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($event);

        // Checking old required fields - backward compatibilty
        for($i = 0; $i < 20; $i++) {

            //var_dump($fields);

            $test = $fields[0][$i];

            if(!empty($test)) {
                //echo "Test" . $i . ": " . $test;

                //die(asdf);
                $res = explode("|", $test);
                if(trim($res[1]) == "1") {
                    $value = $input->get(("zusatz" . ($i + 1)), '', 'string');
                    //echo "Val: " . $value;

                    if(empty($value)){
                        //echo "VALUE IS EMPTY " . $value . " i = " . $i;
                        $pflichtfeld = true;
                    }
                }
            }
        }

        if(MatukioHelperSettings::getSettings("captcha", 0) == 1)  {
            $ccval = $input->get("ccval", '', 'string');
            $captcha = $input->get("captcha", '', 'string');

            if (empty($captcha)) {
                $allesok = 0;
                $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
                $reason = JTEXT::_('COM_MATUKIO_CAPTCHA_WRONG');
            } else if (md5($captcha) != $ccval){
                $allesok = 0;
                $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
                $reason = JTEXT::_('COM_MATUKIO_CAPTCHA_WRONG');
            }
        }

//        echo "Pflichtfeld " . $pflichtfeld;
//        die("asd");

        $agbtext = MatukioHelperSettings::getSettings("agb_text", "");
        if ($pflichtfeld) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_REQUIRED_ADDITIONAL_FIELD_EMPTY');
        } else if (count($temp) > 0) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_REGISTERED_FOR_THIS_EVENT');
        } else if (MatukioHelperUtilsDate::getCurrentDate() > $event->booked) {
            echo "current: " .  MatukioHelperUtilsDate::getCurrentDate();
            echo " booking: " . $event->booked;
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_EXCEEDED');
        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 1) {
            $allesok = 0;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
            $reason = JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
        } else if (!empty($agbtext)){
            if(empty($agb)) {
                $allesok = 0;
                $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
                $reason = JTEXT::_('COM_MATUKIO_AGB_NOT_ACCEPTED');
            }
        } else if ($event->maxpupil - $gebucht - $nrbooked < 0 && $event->stopbooking == 0) {
            $allesok = 2;
            $ueber1 = JTEXT::_('COM_MATUKIO_ADDED_WAITLIST');
            $reason = JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
        }

        if ($art == 4) {
            $allesok = 1;
            $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
        }

        $results = $dispatcher->trigger('onValidateBooking', $post, $event, $allesok);


        //$link = JRoute::_('index.php?option=com_matukio&view=event&catid=' .$catid . '&id=' . $event->id);
        $link = JRoute::_(MatukioHelperRoute::getEventRoute($event->id, $catid), false);

        $msg = "";
        $neu = "";

        // Alles in Ordnung
        if ($allesok > 0) {

            $booking_id = 0;

            // Buchung eintragen
            $neu = JTable::getInstance('bookings', 'Table');

            //$neu = new mossembookings($database);
            if (!$neu->bind($post)) {
                return JError::raiseError(500, $database->stderr());
            }
            $neu->semid = $event->id;
            $neu->userid = $userid;

            $firstname = $input->get('firstname', '', 'string');
            $lastname = $input->get('lastname', '', 'string');

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

            $fields = MatukioHelperUtilsBooking::getBookingFields();

            if(!empty($fields)){

                $newfields = "";

                for($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $name = $field->field_name;
                    $newfields .= $field->id;
                    $newfields .= "::";
                    $newfields .= $input->get($name, '', 'string');
                    $newfields .= ";";
                }

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
                            // Perhaps delete this invalid field, or display an error?! TODO
                        }
                    }
                    $neu->payment_brutto = $payment_brutto;
                }
            }

            $results = $dispatcher->trigger('onBeforeSaveBooking', $neu, $event);

            if (!$neu->check()) {
                JError::raiseError(500, $database->stderr());
                exit();
            }
            if (!$neu->store()) {
                JError::raiseError(500, $database->stderr());
                exit();
            }
            $neu->checkin();

            $results = $dispatcher->trigger('onAfterBooking', $neu, $event);

            $ueber1 = JText::_("COM_MATUKIO_BOOKING_WAS_SUCCESSFULL");

            $booking_id = $neu->id;

            if ($art == 4) {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 8);

                $link = JRoute::_(MatukioHelperRoute::getEventRoute($event->id, $catid, 4), false);
                //$link = JRoute::_('index.php?option=com_matukio&view=event&id=' . $event->id . "&art=2");

            } else {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($event_id, $neu->id, 1);
                $ueberschrift = array($ueber1, $reason);

                // Ausgabe des Kurses
                MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
                MatukioHelperUtilsBasic::expandPathway($event->title, "");
                if ($userid == 0) {
                    $userid = $neu->id * -1;
                }

                $link = JRoute::_(MatukioHelperRoute::getEventRoute($event->id, $catid, 1, $booking_id), false);
            }

        } else {
            $link = JRoute::_(MatukioHelperRoute::getEventRoute($event->id, $catid), false);
            //$link = JRoute::_("index.php?option=com_matukio&view=event&id=" . $event->id);
        }

        if($payment_method == 'payment_paypal' && $allesok > 0){
            //echo $neu->id;
            $link = JRoute::_("index.php?option=com_matukio&view=paypalpayment&booking_id=" . $uuid);
            $this->setRedirect($link, $ueber1 . " " . $reason);
        } else {
            $this->setRedirect($link, $ueber1 . " " . $reason);
        }

        //HTML_FrontMatukio::sem_g002($art, $row, $usrid, $search, $catid, $limit, $limitstart, $dateid, $ueberschrift);
    }


    /**
     * @return mixed
     * $unbookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&task=cancelBooking&cid=" . $this->id);
     */
    public function cancelBooking()
    {
        $cid = JFactory::getApplication()->input->getInt('cid', 0);
        $uid = JFactory::getApplication()->input->getInt('booking_id', 0);

//        echo $cid;
//        echo $uid;
//        die("asdf");

        if(!empty($cid)){
            $link = JRoute::_('index.php?option=com_matukio&view=event&id=' . $cid);
        } else {
            $link = JRoute::_('index.php?option=com_matukio&view=eventlist');
        }

        if (empty($cid) && empty($uid)) {
            $this->setRedirect($link, "COM_MATUKIO_NO_ID");
            return;
        }

        $msg = JText::_("COM_MATUKIO_BOOKING_ANNULATION_SUCESSFULL");

        $database = JFactory::getDBO();
        $user = JFactory::getuser();

        MatukioHelperUtilsEvents::sendBookingConfirmationMail($cid, $user->id, 2, true);

        if(!empty($uid)) {
            $database->setQuery("DELETE FROM #__matukio_bookings WHERE id = '" . $uid . "'");
        } else {
            if($user->id == 0) {
                JError::raiseError(403, "COM_MATUKIO_NO_ACCESS");
                return;
            } else {
                $database->setQuery("DELETE FROM #__matukio_bookings WHERE semid = " . $cid . " AND userid = '" . $user->id . "'");
            }
        }

        if (!$database->execute()) {
            JError::raiseError(500, $database->getError());
            $msg = JText::_("COM_MATUKIO_BOOKING_ANNULATION_FAILED") . " " . $database->getErrror();
        }

        $this->setRedirect($link, $msg);

        //sem_g001(1);
    }

}