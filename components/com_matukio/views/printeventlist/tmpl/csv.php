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

$database = JFactory::getDBO();

$kurs = JTable::getInstance('Matukio', 'Table');
$kurs->load($this->cid);


$tmpl = MatukioHelperTemplates::getTemplate("export_csv");

$database->setQuery("SELECT a.*, cc.*, a.id AS id, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS " .
    "a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$kurs->id' ORDER BY a.id");

$bookings = $database->loadObjectList();
if ($database->getErrorNum()) {
    echo $database->stderr();
    return;
}

//var_dump($bookings);
//
//die();

//$csvdata = "\"#\",\"" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . "\",\"" . JTEXT::_('COM_MATUKIO_NAME') . "\",\"" . JTEXT::_('COM_MATUKIO_EMAIL') . "\",\""
//    . JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING') . "\",\"" . JTEXT::_('COM_MATUKIO_TIME_OF_BOOKING') . "\",\"" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . "\",\""
//    . JTEXT::_('COM_MATUKIO_STATUS');

$csvdata = MatukioHelperTemplates::getCSVHeader($tmpl);

$csvdata .= MatukioHelperTemplates::getCSVData($tmpl, $bookings, $kurs);


//
//
//if ($kurs->fees > 0) {
//    $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_PAID');
//}
//if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
//    $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_CERTIFICATES');
//}
//if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
//    $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_RATING') . "\",\"" . JTEXT::_('COM_MATUKIO_COMMENT');
//}
//$zusatz1 = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($kurs);
//
//foreach ($zusatz1[0] AS $el) {
//    if ($el != "") {
//        $el = explode("|", $el);
//        $csvdata .= "\",\"" . str_replace("\"", "'", $el[0]);
//    }
//}
//$csvdata .= "\"\r\n";
//
//$summe = 0;
//$i = 0;
//foreach ($rows AS $row) {
//    if ($row->userid == 0) {
//        $row->name = $row->aname;
//        $row->email = $row->aemail;
//    }
//    $i++;
//    $summe = $summe + $row->nrbooked;
//    $temp9 = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
//    if ($summe > $kurs->maxpupil) {
//        if ($kurs->stopbooking < 1) {
//            $temp9 = JTEXT::_('COM_MATUKIO_WAITLIST');
//        } else {
//            $temp9 = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
//        }
//    }
//    $temp6 = JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
//    $temp7 = JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('time_format', 'H:i'));
//    $temp8 = $i;
//    $csvdata .= "\"" . $temp8 . "\",\"" . MatukioHelperUtilsBooking::getBookingId($row->sid) . "\",\"" . str_replace("\"", "'", $row->name) . "\",\""
//        . $row->email . "\",\"" . $temp6 . "\",\"" . $temp7 . "\",\"" . $row->nrbooked . "\",\"" . $temp9;
//    if ($kurs->fees > 0) {
//        $temp7 = JTEXT::_('COM_MATUKIO_NO');
//        if ($row->paid == 1) {
//            $temp7 = JTEXT::_('COM_MATUKIO_YES');
//        }
//        $csvdata .= "\",\"" . $temp7;
//    }
//    if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
//        $temp7 = JTEXT::_('COM_MATUKIO_NO');
//        if ($row->certificated == 1) {
//            $temp7 = JTEXT::_('COM_MATUKIO_YES');
//        }
//        $csvdata .= "\",\"" . $temp7;
//    }
//    if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
//        $csvdata .= "\",\"" . $row->grade . "\",\"" . str_replace("\"", "'", $row->comment);
//    }
//    $zusatz2 = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);
//    for ($l = 0, $m = count($zusatz2[0]); $l < $m; $l++) {
//        if ($zusatz1[0][$l] != "") {
//            $csvdata .= "\",\"" . str_replace("\"", "'", $zusatz2[0][$l]);
//        }
//    }
//    $csvdata .= "\"\r\n";




//}
$konvert = MatukioHelperSettings::getSettings('csv_export_charset', 'ISO-8859-15');
$csvdata = iconv("UTF-8", $konvert, $csvdata);

var_dump($csvdata);

die();

header("content-type: application/csv-tab-delimited-table; charset=" . $konvert);
header("content-length: " . strlen($csvdata));
header("content-disposition: attachment; filename=\"$kurs->title.csv\"");
header('Pragma: no-cache');
echo $csvdata;