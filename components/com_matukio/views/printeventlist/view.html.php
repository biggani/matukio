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

class MatukioViewPrintEventlist extends JViewLegacy {

    public function display($tpl = NULL) {

        $database = JFactory::getDBO();
        $my = JFactory::getuser();
        $dateid = JFactory::getApplication()->input->getInt('dateid', 1);
        $catid = JFactory::getApplication()->input->getInt('catid', 0);
        $search = JFactory::getApplication()->input->get('search', '', 'string');
        $limit = JFactory::getApplication()->input->getInt('limit', 5);
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $cid = JFactory::getApplication()->input->getInt('cid', 0);
        $uid = JFactory::getApplication()->input->getInt('uid', 0);


        $todo = JFactory::getApplication()->input->get('todo', 'print_eventlist'); // print_eventlist, print_booking, print_myevents, print
        $rows = null;
        $status = null;
        $headertext = null;

        // Did comment this
        // echo "TODO: " . $todo;

        $neudatum = MatukioHelperUtilsDate::getCurrentDate();
        if ($limitstart < 0) {
            $limitstart = 0;
        }
        $ttlimit = "";
        if ($limit > 0) {
            $ttlimit = "\nLIMIT $limitstart, $limit";
        }

        /**
         * 65O9805443904 =    public ?!
         * 653O875032490 =    Meine Angebote
         * 6530387504345 =  Meine Buchungen / Buchungsbestätigung ?!
         *
         * 3728763872762 =
         * csv
         */

        $where = array();
        $where[] = "a.pattern = ''";
        $where[] = "a.published = '1'";

        switch ($todo) {
            case "print_eventlist":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavshowmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
                break;
            case "print_booking":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavbookingmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
                break;
            case "print_myevents":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavoffermodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
                break;
            case "print_teilnehmerliste":
                $navioben = "";
                break;

        }
        if($todo != "print_teilnehmerliste" && $todo != "csvlist" && $todo != "certificate") {
            if (in_array('SEM_TYPES', $navioben)) {
                switch ($dateid) {
                    case "1":
                        $where[] = "a.end > '$neudatum'";
                        break;
                    case "2":
                        $where[] = "a.end <= '$neudatum'";
                        break;
                }
            }
        }


        switch ($todo) {
            default:
            case "print_eventlist":
                if (!in_array('SEM_TYPES', $navioben)) {
                    $where[] = "a.end > '$neudatum'";
                }
                if ((isset($_GET["catid"]) OR in_array('SEM_CATEGORIES', $navioben)) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }
                $headertext = JTEXT::_('COM_MATUKIO_EVENTS');
                if ($cid) {
                    $where[] = "a.id= '$cid'";
                    $headertext = JTEXT::_('COM_MATUKIO_EVENT');
                }
                $database->setQuery("SELECT a.*, cc.title AS category FROM #__matukio AS a"
                        . "\nLEFT JOIN #__categories AS cc"
                        . "\nON cc.id = a.catid"
                        . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                        . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%'"
                        . " OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
                );
                $rows = $database->loadObjectList();

                // Abzug der Kurse, die wegen Ausbuchung nicht angezeigt werden sollen
                if (!$cid) {
                    $abid = array();
                    foreach ($rows as $row) {
                        if ($row->stopbooking == 2) {
                            $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                            if ($row->maxpupil - $gebucht->booked < 1) {
                                $abid[] = $row->id;
                            }
                            ;
                        }
                    }
                    if (count($abid) > 0) {
                        $abid = implode(',', $abid);
                        $where[] = "a.id NOT IN ($abid)";
                    }
                }

                $database->setQuery("SELECT a.*, cc.title AS category FROM #__matukio AS a"
                        . "\nLEFT JOIN #__categories AS cc"
                        . "\nON cc.id = a.catid"
                        . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                        . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
                        . "\nORDER BY a.begin"
                        . $ttlimit
                );
                $rows = $database->loadObjectList();
                $status = array();
                $paid = array();
                $abid = array();
                for ($i = 0, $n = count($rows); $i < $n; $i++) {
                    $row = &$rows[$i];
                    $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                    $gebucht = $gebucht->booked;
                    if (MatukioHelperUtilsDate::getCurrentDate() > $row->booked OR ($row->maxpupil - $gebucht < 1
                        AND $row->stopbooking == 1) OR ($my->id == $row->publisher
                        AND MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0)) {
                        $status[$i] = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
                    } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
                        $status[$i] = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
                    } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 2) {
                        $abid[] = $row->id;
                    } else {
                        $status[$i] = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
                    }
                    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' AND userid='$my->id'");
                    $temp = $database->loadObjectList();
                    if (count($temp) > 0) {
                        $status[$i] = JTEXT::_('COM_MATUKIO_ALREADY_BOOKED');
                        if ($temp[0]->paid == 1) {
                            $rows[$i]->fees = $rows[$i]->fees . " - " . JTEXT::_('COM_MATUKIO_PAID');
                        }
                    }
                    $rows[$i]->codepic = "";
                }
                break;

            // Buchungsbestätigung ?!
            case "print_booking":
                $headertext = JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . " - " . $my->name;
                if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }
                $where[] = "cc.userid = '" . $my->id . "'";
                if ($cid) {
                    $where[] = "cc.semid = '" . $cid . "'";
                    $headertext = JTEXT::_('COM_MATUKIO_BOOKING_CONFIRMATION') . " - " . $my->name;
                }
                $database->setQuery("SELECT a.*, cat.title AS category, cc.bookingdate AS bookingdate, cc.id AS bookid FROM #__matukio
                                    AS a LEFT JOIN #__matukio_bookings AS cc ON cc.semid = a.id LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                        . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                        . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%'
                        OR a.description LIKE '%$search%')"
                        . "\nORDER BY a.begin"
                        . $ttlimit
                );
                $rows = $database->loadObjectList();
                $status = array();
                for ($i = 0, $n = count($rows); $i < $n; $i++) {
                    $row = &$rows[$i];
                    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' ORDER BY id");
                    $temps = $database->loadObjectList();
                    $status[$i] = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
                    $rows[$i]->codepic = $row->bookid;
                    if (count($temps) > $row->maxpupil) {
                        if ($row->stopbooking == 0) {
                            for ($l = 0, $m = count($temps); $l < $m; $l++) {
                                $temp = &$temps[$l];
                                if ($temp->userid == $my->id) {
                                    break;
                                }
                            }
                            if ($l + 1 > $row->maxpupil) {
                                $status[$i] = JTEXT::_('COM_MATUKIO_WAITLIST');
                            }
                        } else {
                            $status[$i] = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
                        }
                    }
                    if ($temps[0]->paid == 1) {
                        $rows[$i]->fees = $rows[$i]->fees . " - " . JTEXT::_('COM_MATUKIO_PAID');
                    }
                }
                break;

            // Meine Angebot ?!
            case "print_myevents":
                if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }
                $where[] = "a.publisher = '" . $my->id . "'";
                $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                        . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                        . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
                        . "\nORDER BY a.begin"
                        . $ttlimit
                );
                $rows = $database->loadObjectList();
                $status = array();
                $headertext = JTEXT::_('COM_MATUKIO_MY_OFFERS') . " - " . $my->name;
                for ($i = 0, $n = count($rows); $i < $n; $i++) {
                    $row = &$rows[$i];
                    $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                    $gebucht = $gebucht->booked;
                    if ((MatukioHelperUtilsDate::getCurrentDate() > $row->booked) OR ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 1)) {
                        $status[$i] = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
                    } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
                        $status[$i] = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
                    } else {
                        $status[$i] = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
                    }
                    $rows[$i]->codepic = "";
                }
                break;

            case "print_teilnehmerliste":
                // TODO implement userchecking

                $art = JFactory::getApplication()->input->getInt('art', 0);
                $this->art = $art;

                if($art == 1) {
                    $this->setLayout("signaturelist");
                } else {
                    $this->setLayout("participants");
                }
                break;


            case "csvlist":
                // TODO implement userchecking
                $art = JFactory::getApplication()->input->getInt('art', 0);
                $this->art = $art;
                $this->cid = $cid;

                $this->setLayout("csv");
                break;

            case "certificate":
                // TODO implement userchecking
                $art = JFactory::getApplication()->input->getInt('art', 0);
                $uid = JFactory::getApplication()->input->getInt('uid', 0);

                $this->art = $art;
                $this->uid = $uid;

                $this->setLayout("certificate");
                //sem_f051($cid);
                break;

        }
        //sem_f056($rows, $status, $headertext);


        $this->rows = $rows;
        $this->status = $status;
        $this->headertext = $headertext;


        parent::display($tpl);
    }
}