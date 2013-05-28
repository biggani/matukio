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

$mainconfig = JFactory::getConfig();
$filename = $this->events[0]->title;

//var_dump($this->events);

if(count($this->events) > 1) {
    $filename = $mainconfig->get('config.sitename') . "- Events";
}

$icsdata = "BEGIN:VCALENDAR\n";
$icsdata .= "VERSION:2.0\n";
$icsdata .= "PRODID:" . MatukioHelperUtilsBasic::getSitePath() . "\n";
$icsdata .= "METHOD:PUBLISH\n";

foreach($this->events as $event) {
    $user = JFactory::getuser($event->publisher);
    $icsdata .= "BEGIN:VEVENT\n";
    $icsdata .= "UID:" . MatukioHelperUtilsBooking::getBookingId($event->id) . "\n";
    $icsdata .= "ORGANIZER;CN=\"" . $user->name . "\":MAILTO:" . $user->email . "\n";
    $icsdata .= "SUMMARY:" . $event->title . "\n";
    $icsdata .= "LOCATION:" . str_replace("(\r\n|\n|\r)", ", ", $event->place) . "\n";
    $icsdata .= "DESCRIPTION:" . str_replace("(\r\n|\n|\r)", " ", $event->shortdesc) . "\n";
    $icsdata .= "CLASS:PUBLIC\n";
    $icsdata .= "DTSTART:" . strftime("%Y%m%dT%H%M%S", JFactory::getDate($event->begin)->toUnix()) . "\n";
    $icsdata .= "DTEND:" . strftime("%Y%m%dT%H%M%S", JFactory::getDate($event->end)->toUnix()) . "\n";
    $icsdata .= "DTSTAMP:" . strftime("%Y%m%dT%H%M%S", JFactory::getDate(MatukioHelperUtilsDate::getCurrentDate())->toUnix()) . "\n";
    $icsdata .= "BEGIN:VALARM\n";
    $icsdata .= "TRIGGER:-PT1440M\n";
    $icsdata .= "ACTION:DISPLAY\n";
    $icsdata .= "DESCRIPTION:Reminder\n";
    $icsdata .= "END:VALARM\n";
    $icsdata .= "END:VEVENT\n";
}
$icsdata .= "END:VCALENDAR";

header("Content-Type: text/calendar; charset=utf-8");
header("Content-Length: " . strlen($icsdata));
header("Content-Disposition: attachment; filename=\"" . $filename . ".ics\"");
header('Pragma: no-cache');

echo $icsdata;
