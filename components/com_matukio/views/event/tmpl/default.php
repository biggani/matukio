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

global $mainframe;
$document = &JFactory::getDocument();
$database = &JFactory::getDBO();
$my = &JFactory::getuser();
JHTML::_('stylesheet', 'matukio.css', 'media/com_matukio/css/');
JHTML::_('stylesheet', 'modern.css', 'media/com_matukio/css/');


$neudatum = MatukioHelperUtilsDate::getCurrentDate();
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');

// ---------------------------------
// Ist Kurs noch buchbar
// ---------------------------------

if (empty($this->uid)) {
    $usrid = $my->id;
} else {
    $usrid = $this->uid;
}
$modify = 26;
if ($this->art > 1) {
    if (!empty($this->uid)) {
        $usrid = $this->uid;
    }
}
if ($this->art > 2) {
    $modify = 29;
}
// buchopt array(5) { [0]=> int(3) [1]=> string(37) "Veranstaltung hat noch nicht begonnen" [2]=> array(0) { } [3]=> int(2) [4]=> int(12) }
$buchopt = MatukioHelperUtilsEvents::getEventBookableArray($this->art, $this->event, $usrid);

//echo "buchopt ";
//var_dump($buchopt);
//die(asdf);

$nametemp = "";
$htxt = 2;
if ($this->art > 2) {
    if ($usrid == 0) {
        $nametemp = MatukioHelperUtilsBasic::getBookedUserList($this->event);
    } else if ($usrid > 0) {
        $nametemp = &JFactory::getuser($usrid);
        $nametemp = $nametemp->name;
    }
    if ($nametemp == "") {
        $htxt = 2.2;
    }
}
if ($this->event->nrbooked == 0) {
    $htxt = 2.3;
}
//$document->addCustomTag(sem_f027($htxt));
$bezahlt = 0;
if (count($buchopt[2]) > 0) {
    if ($buchopt[2][0]->paid == 1) {
        $bezahlt = 1;
    }
}


// ---------------------------------
// Darf Kurs bearbeitet werden
// ---------------------------------
$tempdis = " disabled";
if ((($buchopt[0] == 3 OR ($this->art == 1 AND MatukioHelperSettings::getSettings('booking_edit', 1) == 1 AND $bezahlt == 0))
    AND strtotime($this->event->booked) - time() >= (MatukioHelperSettings::getSettings('booking_stornotage', 1) * 24 * 60 * 60)
        AND $this->art != 2) OR $this->art == 3
) {
    $tempdis = "";
}

// ---------------------------------
// Anzeige Reiter
// ---------------------------------

//echo MatukioHelperUtilsBasic::printFormstart(1);


// Form Ausgabe
?>
<form action="index.php" method="post" name="FrontForm" id="FrontForm">
<?php


$zurueck = array(0, 1, 0, 23, 23);
$knopfunten = "";
if ($this->art == 0 OR $this->art == 2) {
    $knopfunten = MatukioHelperUtilsEvents::getEventlistHeader(1);
} elseif ($this->art == 1) {
    $knopfunten = MatukioHelperUtilsEvents::getEventlistHeader(2);
} elseif ($this->art > 2) {
    $knopfunten = MatukioHelperUtilsEvents::getEventlistHeader(3);
}

// ---------------------------------
// Anzeige Funktionsknoepfe oben
// ---------------------------------

$zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($this->event);
$zfleer = 1;
foreach ($zusfeld[0] AS $el) {
    if ($el != "") {
        $zfleer = 0;
        break;
    }
}
$gmapicon = "";

//function semauf(stask,scid,suid) {
//semauf('0','4','')
//$backlink = JRoute::_("index.php?option=com_matukio&view=eventlist&art=" . $zurueck[$this->art]); //. "&cid=" . $this->event->id);
//// Zurueck-Knopf anzeigen
//$knopfoben = "<a title=\"" . JTEXT::_('COM_MATUKIO_BACK') . "\" href=\"" . $backlink . "\"><img src=\""
//    . MatukioHelperUtilsBasic::getComponentImagePath() . "1032.png\" border=\"0\" align=\"absmiddle\"></a>";
//
//$knopfunten .= " <span class=\"mat_button\" style=\"cursor:pointer;\" onclick=\"location.href='" . $backlink . "'\">
//    <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "1016.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
//    . JTEXT::_('COM_MATUKIO_BACK') . "</span>";

// Knopf fuer ICS-Datei anzeigen
if (MatukioHelperSettings::getSettings('frontend_usericsdownload', 1) > 0) {

    $icslink = JRoute::_("index.php?option=com_matukio&view=ics&format=raw&cid=" . $this->event->id);

    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_DOWNLOAD_CALENDER_FILE') . "\" href=\"" . $icslink . "\" target=\"_BLANK\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "3332.png\" border=\"0\" align=\"absmiddle\"></a>";

    $knopfunten .= " <a title=\"" . JTEXT::_('COM_MATUKIO_DOWNLOAD_CALENDER_FILE') . "\" href=\"" . $icslink . "\" target=\"_BLANK\">"
        . "<span class=\"mat_button\" style=\"cursor:pointer;\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "3316.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
        . JTEXT::_('COM_MATUKIO_DOWNLOAD_CALENDER_FILE') . "</span></a>";
}

// Knopf fuer Nachricht anzeigen
if (($usrid != $this->event->publisher) AND ($my->id != $this->event->publisher) AND $this->art != 2) {
    if(MatukioHelperSettings::getSettings("sendmail_contact", 1)){
        $knopfoben .= MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->event->id, 1);
        $knopfunten .= " " . MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->event->id, 2);
    }
}

// Google-Maps-Karte anzeigen
if ($this->event->gmaploc != "" AND $this->art != 2) {

    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_MAP') . "\" class=\"modal\" href=\""
        . JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&event_id=' . $this->event->id)
        . "\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "1332.png\" border=\"0\" align=\"absmiddle\"></a>";
    $knopfunten .= " <a class=\"modal\" border=\"0\" href=\"" . JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&event_id=' . $this->event->id)
        . "\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\"><span class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "1316.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
        . JTEXT::_('COM_MATUKIO_MAP') . "</span></a>";
    $gmapicon = "<a title=\"" . JTEXT::_('COM_MATUKIO_MAP') . "\" class=\"modal\" href=\""
        . JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&event_id=' . $this->event->id)
        . "\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
        . "1316.png\" width=\"12px\" height=\"12px\" style=\"vertical-align: middle;\" border=\"0\"/></a>";
}

// Druckknopf anzeigen
if ($this->art != 2 AND $this->art != 4) {
    $knopfoben .= MatukioHelperUtilsEvents::getPrintWindow(2, $this->event->id, '', '');
    $knopfunten .= " " . MatukioHelperUtilsEvents::getPrintWindow(2, $this->event->id, '', 'b');
}

//function semauf(stask,scid,suid) {
//javascript:semauf('5','" . $this->event->id . "','')
// do this g005 in matukio.php

if ((($buchopt[0] > 2 AND $this->art == 0) OR ($this->art == 3 AND $usrid == 0 AND ($nametemp != "" OR
    MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1)))
    AND $this->event->cancelled == 0 AND $this->event->nrbooked > 0
) {

    //javascript:semauf('5','" . $this->event->id . "','');

    if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
//    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"javascript:semauf('5','" . $this->event->id . "','');\"><img src=\""
//        . MatukioHelperUtilsBasic::getComponentImagePath() . "1132.png\" border=\"0\" align=\"absmiddle\"></a>";


        // Booking button
        if ($this->event->fees > 0) {
            $knopfunten .= " <input type=\"submit\" style=\"cursor:pointer;\" class=\"booking_button mat_button\" value=\""
                . JTEXT::_('COM_MATUKIO_BOOK_PAID') . "\">";
        } else {
            $knopfunten .= " <input type=\"submit\" style=\"cursor:pointer;\" class=\"booking_button mat_button\" value=\""
                . JTEXT::_('COM_MATUKIO_BOOK') . "\">";
        }

    } else {
        $bookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&cid=" . $this->event->id . ":" . JFilterOutput::stringURLSafe($this->event->title));

//        $knopfunten .= "<a href=\"\" alt=\"\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
//            . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"  . JTEXT::_('COM_MATUKIO_BOOK') . "</a>"; 2

        $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"" . $bookinglink . "\"><img src=\""
            . MatukioHelperUtilsBasic::getComponentImagePath() . "1132.png\" border=\"0\" align=\"absmiddle\"></a>";

        $knopfunten .= " <a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"" . $bookinglink
            . "\"><span class=\"mat_button\" style=\"cursor:pointer;\"><img src=\""
            . MatukioHelperUtilsBasic::getComponentImagePath()
            . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
            . JTEXT::_('COM_MATUKIO_BOOK') . "</span></a>";

    }
    // <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
}

// Aenderungen speichern Veranstalter
if ($this->art == 3 And $usrid != 0 AND ($this->event->nrbooked > 1 OR $zfleer == 0)) {

    // Changes form ...
    // TODO implement oben submit something for oben :)
//    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . "\" href=\"javascript:semauf('"
//        . $modify . "','" . $this->event->id . "','" . $buchopt[2][0]->id . "');\"><img src=\""
//        . MatukioHelperUtilsBasic::getComponentImagePath() . "1432.png\" border=\"0\" align=\"absmiddle\"></a>";


    $knopfunten .= ' <input type="submit" class="mat_button" value="' . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . '">';
//    $knopfunten .= " <button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"semauf('"
//        . $modify . "','" . $this->event->id . "','" . $buchopt[2][0]->id . "');\"><img src=\""
//        . MatukioHelperUtilsBasic::getComponentImagePath() . "1416.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
//        . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . "</button>";
}

// Aenderungen speichern Benutzer falls noch nicht gezahlt
if ($this->art == 1 AND strtotime($this->event->booked) - time() >= (MatukioHelperSettings::getSettings('booking_stornotage', 1)
    * 24 * 60 * 60) AND $bezahlt == 0
) {

    if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {

//        echo "User: " . $this->user->id;
//        echo " zfleer: " . $zfleer;
//        echo " nrbooked: " . $this->event->nrbooked;

        if (MatukioHelperSettings::getSettings('booking_edit', 1) == 1 AND ($this->event->nrbooked > 1 OR $zfleer == 0)) {
//            $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . "\" href=\"javascript:semauf('"
//                . $modify . "','" . $this->event->id . "','" . $buchopt[2][0]->id . "');\"><img src=\""
//                . MatukioHelperUtilsBasic::getComponentImagePath() . "1432.png\" border=\"0\" align=\"absmiddle\"></a>";


//            $knopfunten .= " <button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"semauf('"
//                . $modify . "','" . $this->event->id . "','" . $buchopt[2][0]->id . "');\"><img src=\""
//                . MatukioHelperUtilsBasic::getComponentImagePath() . "1416.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
//                . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . "</button>";

            if($this->user->id > 0) {

                $unbookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&task=cancelBooking&cid=" . $this->id);


            $knopfoben .= "<a border=\"0\" title=\"" . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "\" href=\"" . $unbookinglink . "\">"
                . "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "1532.png\" border=\"0\" align=\"absmiddle\"></a>";


            $knopfunten .= " <a border=\"0\" href=\"" . $unbookinglink
                . "\" ><span class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath() . "1532.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "</span></a>";
            }

        }
    } else {

        $unbookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&task=cancelBooking&cid=" . $this->id);

        // Buchung stornieren Benutzer falls noch nicht gezahlt
        if (MatukioHelperSettings::getSettings('booking_stornotage', 1) > -1) {

            if($this->user->id > 0) {

                $knopfoben .= "<a border=\"0\" title=\"" . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "\" href=\"" . $unbookinglink . "\">"
                . "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "1532.png\" border=\"0\" align=\"absmiddle\"></a>";


            $knopfunten .= " <a border=\"0\" href=\"" . $unbookinglink
                . "\" ><span class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath() . "1532.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "</span></a>";
            }


    //        $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "\" href=\"javascript:semauf('6','"
    //            . $buchopt[2][0]->id . "','');\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
    //            . "1532.png\" border=\"0\" align=\"absmiddle\"></a>";
    //        $knopfunten .= " <button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"semauf('6','"
    //            . $buchopt[2][0]->id . "','');\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
    //            . "1516.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "</button>";
        }
    }
}

// obere Knoepfe anzeigen
if (MatukioHelperSettings::getSettings('event_buttonposition', 2) == 0 OR MatukioHelperSettings::getSettings('event_buttonposition', 2) == 2) {
    echo $knopfoben;
}
MatukioHelperUtilsEvents::getEventlistHeaderEnd();

// ---------------------
// Anzeige Kurstitel
// ---------------------

MatukioHelperUtilsEvents::printHeading($this->ueberschrift[0], $this->ueberschrift[1]);

// ---------------------
// Anzeige Kursangaben
// ---------------------


if ($this->event->nrbooked <= 1 OR MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) < 1) {
    $platzauswahl = "";
} else {
    $this->limits = array();
    if ($this->art == 0 OR ($this->art == 3 AND $usrid == 0)) {
        $tempplaetze = $buchopt[4];
        $tempplatz = "";
    } else {
        // buchopt array(5) { [0]=> int(3) [1]=> string(37) "Veranstaltung hat noch nicht begonnen" [2]=> array(0) { } [3]=> int(2) [4]=> int(12) }

        //var_dump($buchopt);
        // array(5) { [0]=> int(3) [1]=> string(19) "Teilnahme gesichert" [2]=> array(0) { } [3]=> int(2) [4]=> int(10) }
        if (!empty($buchopt[2])) {
            $tempplatz = $buchopt[2][0]->nrbooked;
        }
        $tempplaetze = $buchopt[4] + $tempplatz;
    }
    if ($tempplaetze > $this->event->nrbooked OR ($this->event->stopbooking == 0 AND $this->art == 0) OR ($this->art == 3 AND $usrid == 0)) {
        $tempplaetze = $this->event->nrbooked;
    }
    for ($i = 1; $i <= $tempplaetze; $i++) {
        $this->limits[] = JHTML::_('select.option', $i);
    }
    $platzauswahl = JHTML::_('select.genericlist', $this->limits, 'nrbooked', 'class="sem_inputbox" size="1"' . $tempdis,
        'value', 'text', $tempplatz);
}

// Status für Parser festlegen
$parse = "sem_unregistered";
if ($my->id > 0) {
    $parse = "sem_registered";
}
if ($buchopt[0] == 2) {
    $parse = "sem_booked";
    if ($buchopt[2][0]->paid > 0) {
        $parse = "sem_paid";
    }
    if ($buchopt[2][0]->certificated > 0) {
        $parse = "sem_certifcated";
    }
}

$html = MatukioHelperUtilsEvents::getTableHeader(4);

// Titel anzeigen
if ($nametemp != "") {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NAME') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($nametemp, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}
$html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TITLE') . ':', 'd', 'l', '20%', 'sem_rowd')
    . MatukioHelperUtilsEvents::getTableCell($this->event->title, 'd', 'l', '80%', 'sem_rowd') . "</tr>";

// Veranstaltungsnummer anzeigen
if ($this->event->semnum != "") {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NUMBER') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($this->event->semnum, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Status anzeigen
$htxt = $buchopt[1];
if ($this->event->nrbooked < 1) {
    $htxt = JTEXT::_('COM_MATUKIO_CANNOT_BOOK_ONLINE');
}
$html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_STATUS') . ':', 'd', 'l', '20%', 'sem_rowd')
    . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";

// Buchungs-ID anzeigen
if (count($buchopt[2]) > 0) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BOOKING_ID') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBooking::getBookingId($buchopt[2][0]->id), 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Falls abgesagt Formatierung aendern
$htx1 = "";
$htx2 = "";
if ($this->event->cancelled == 1) {
    $htx1 = "\n<span class=\"sem_cancelled\">" . JTEXT::_('COM_MATUKIO_CANCELLED') . " </span>(<del>";
    $htx2 = "</del>)";
}

// Beginn anzeigen
if ($this->event->showbegin > 0) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BEGIN') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($htx1 . JHTML::_('date', $this->event->begin
            , MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . $htx2, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Ende anzeigen
if ($this->event->showend > 0) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_END') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($htx1 . JHTML::_('date', $this->event->end
            , MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . $htx2, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Anmeldeschluss bzw. Buchungsdatum anzeigen
if ($this->event->showbooked > 0) {
    if ($this->art == 0 OR ($this->art == 3 AND $usrid == 0)) {
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ':', 'd', 'l', '20%', 'sem_rowd')
            . MatukioHelperUtilsEvents::getTableCell($htx1 . JHTML::_('date', $this->event->booked
                , MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . $htx2, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
    } else {
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING') . ':', 'd', 'l', '20%', 'sem_rowd')
            . MatukioHelperUtilsEvents::getTableCell(JHTML::_('date', $buchopt[2][0]->bookingdate,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), 'd', 'l', '80%', 'sem_rowd') . "</tr>";
    }
}

// Seminarleiter anzeigen
if ($this->event->teacher != "") {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TUTOR') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($this->event->teacher, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Zielgruppe anzeigen
if ($this->event->target != "") {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($this->event->target, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Google-Map & Location anzeigen
if ($this->event->webinar != 1) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CITY') . ': ' . $gmapicon, 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell(nl2br($this->event->place), 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Freie Plaetze anzeigen
if ($this->event->nrbooked > 0 AND MatukioHelperSettings::getSettings('event_showinfoline', 1) == 1) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BOOKABLE') . ':', 'd', 'l', '20%', 'sem_rowd')
        . MatukioHelperUtilsEvents::getTableCell($buchopt[4], 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Buchbare Plaetze als Auswahl anzeigen
$reqtext = "";
$reqfield = " <span class=\"sem_reqfield\">*</span>";
$reqnow = "\n<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;" . $reqfield . " "
    . JTEXT::_('COM_MATUKIO_REQUIRED_FIELD'), 'd', 'r', '100%', 'sem_nav', 2) . "</tr>";

if (MatukioHelperSettings::getSettings('oldbookingform', false)) {
    if ($this->event->nrbooked > 1 AND MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0
        AND ($buchopt[0] > 1 OR $this->art == 3)
    ) {
        if ($buchopt[0] == 3) {
            $htx1 = JTEXT::_('COM_MATUKIO_PLACES_TO_BOOK');
        } else {
    $htx1 = JTEXT::_('COM_MATUKIO_BOOKED_PLACES');
}
if ($tempdis == "") {
    $htx2 = $platzauswahl;
} else {
    $htx2 = "<input class=\"sem_inputbox\" type=\"text\" value=\"" . $buchopt[2][0]->nrbooked . "\"size=\"1\" style=\"text-align:right;\"" . $tempdis . " />";
}
$html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($htx1 . ':', 'd', 'l', '20%', 'sem_rowd') . MatukioHelperUtilsEvents::getTableCell($htx2, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}
}

// Gebuehren anzeigen
if ($this->event->fees > 0) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FEES') . ':', 'd', 'l', '20%', 'sem_rowd');
    $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . MatukioHelperUtilsEvents::getFormatedCurrency($this->event->fees);
    if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
        if ($buchopt[0] != 2) {
            $htxt .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
        }
        if ($buchopt[0] == 2 AND $buchopt[2][0]->nrbooked > 1) {
            $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " "
                . MatukioHelperUtilsEvents::getFormatedCurrency($this->event->fees * $buchopt[2][0]->nrbooked)
                . " (" . $htxt . " " . JTEXT::_('COM_MATUKIO_PRO_PERSON') . ")";
        }
    }
    if ($buchopt[0] == 2) {
        if ($buchopt[2][0]->paid == 1) {
            $htxt .= " - " . JTEXT::_('COM_MATUKIO_PAID');
        }
    }
    $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Dateien herunterladen
$datfeld = MatukioHelperUtilsEvents::getEventFileArray($this->event);
$htxt = array();
for ($i = 0; $i < count($datfeld[0]); $i++) {
    if ($datfeld[0][$i] != "" AND ($datfeld[2][$i] == 0 OR ($my->id > 0 AND $datfeld[2][$i] == 1) OR ($buchopt[0] == 2
        AND $datfeld[2][$i] == 2) OR ($buchopt[2][0]->paid == 1 AND $datfeld[2][$i] == 3))
    ) {
        // TODO improve security.. dirks way is a joke.. security by obscurity in opensource!!1
        // index.php?s=" . MatukioHelperUtilsBasic::getRandomChar() . "&amp;option=" . JRequest::getCmd('option') . "&amp;task=34&amp;a6d5dgdee4cu7eho8e7fc6ed4e76z="
        // . sha1(md5($datfeld[0][$i])) . $this->event->id .
        $filelink = JRoute::_("index.php?option=com_matukio&view=matukio&task=downloadfile&a6d5dgdee4cu7eho8e7fc6ed4e76z="
            . sha1(md5($datfeld[0][$i])) . $this->event->id);

        $htxt[] = "<tr><td style=\"white-space:nowrap;vertical-align:top;\"><span style=\"background-image:url("
            . MatukioHelperUtilsBasic::getComponentImagePath() . "0002.png);background-repeat:no-repeat;background-position:2px;padding-left:18px;vertical-align:middle;\" >
            <a href=\"" . $filelink . "\" target=\"_blank\">" . $datfeld[0][$i]
            . "</a></span></td><td width=\"80%\" style=\"vertical-align:top;\">" . $datfeld[1][$i]
            . "</td></tr>";
    }
}
if (count($htxt) > 0) {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FILES') . ":", 'd', 'l', '20%', 'sem_rowd');
    $htxt = MatukioHelperUtilsEvents::getTableHeader(4) . implode($htxt) . MatukioHelperUtilsEvents::getTableHeader('e');
    $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
}

// Beschreibung anzeigen
if ($this->event->description != "") {
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBasic::parseOutput(JHtml::_('content.prepare',
        $this->event->description), $parse), 'd', '', '', 'sem_rowd', 2) . "</tr>";
}

if (MatukioHelperSettings::getSettings('oldbookingform', false)) {
    if ($this->event->nrbooked > 0) {

        // Name und E-Mail, falls Buchung fuer nichtregistrierte User erlaubt
        $hidden = "";
        if (MatukioHelperSettings::getSettings('booking_unregistered', 1) > 0 AND $usrid < 1 AND (($buchopt[0] > 2
            AND $this->art == 0) OR $this->art == 3 OR $this->art == 2) AND $this->event->cancelled == 0
        ) {
            $zusname = "";
            $zusemail = "";
            if (count($buchopt[2]) > 0) {
                $zusname = $buchopt[2][0]->name;
                $zusemail = $buchopt[2][0]->email;
            }
            $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"name\" name=\"name\" value=\"" . $zusname . "\" size=\"50\"" . $tempdis . ">" . $reqfield;
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NAME') . ':', 'd', 'l', '20%', 'sem_rowd') .
                MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
            $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"email\" name=\"email\" value=\"" . $zusemail . "\" size=\"50\"" . $tempdis . ">" . $reqfield;
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_EMAIL') . ':', 'd', 'l', '20%', 'sem_rowd') .
                MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
        } else {
            $hidden .= "<input type=\"hidden\" name=\"name\" value=\"\"><input type=\"hidden\" name=\"email\" value=\"\">";
        }

        // Zusatzfelder ausgeben
        $zusreq = 0;
        $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($this->event);
        $zustemp = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        if (count($buchopt[2]) > 0) {
            $zustemp = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($buchopt[2][0]);
            $zustemp = $zustemp[0];
        }
        for ($i = 0; $i < count($zusfeld[0]); $i++) {
            if ($zusfeld[0][$i] != "" AND ($buchopt[0] > 1 OR $this->art == 3)) {
                $zusart = explode("|", $zusfeld[0][$i]);
                if (count($buchopt[2]) == 0) {
                    $zustemp[$i] = $zusart[2];
                }
                $htxt = $zusart[0] . MatukioHelperUtilsBasic::createToolTip($zusfeld[1][$i]);
                $temp = "";
                $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '20%', 'sem_rowd');
                if ($tempdis == "") {
                    if ($zusart[1] == 1) {
                        $temp = $reqfield;
                        $reqtext = $reqnow;
                    }
                }
                if (count($zusart) > 1) {
                    $optionen = array();
                    switch ($zusart[3]) {
                        case "select":
                            $optionen[] = JHTML::_('select.option', '', '- ' . JTEXT::_('COM_MATUKIO_PLEASE_SELECT') . ' -');
                            for ($z = 4; $z < count($zusart); $z++) {
                                $optionen[] = JHTML::_('select.option', $zusart[$z], $zusart[$z]);
                            }
                            $htxt = JHTML::_('select.genericlist', $optionen, 'zusatz' . ($i + 1), 'class="sem_inputbox" size="1"' . $tempdis, 'value', 'text', $zustemp[$i]) . $temp;
                            break;
                        case "radio":
                            for ($z = 4; $z < count($zusart); $z++) {
                                $optionen[] = JHTML::_('select.option', $zusart[$z], $zusart[$z]);
                            }
                            $auswahl = $zustemp[$i];
                            if ($zusfeld[2][$i] == 1 AND $auswahl == "") {
                                $auswahl = $zusart[2];
                            }
                            $htxt = JHTML::_('select.radiolist', $optionen, 'zusatz' . ($i + 1), 'class="sem_inputbox"' . $tempdis, 'value', 'text', $auswahl) . $temp;
                            break;
                        case "textarea":
                            if (count($zusart) > 4) {
                                if (!is_numeric($zusart[4])) {
                                    $zusart[4] = 30;
                                }
                                if (!is_numeric($zusart[5])) {
                                    $zusart[5] = 3;
                                }
                            } else {
                                $zusart[4] = 30;
                                $zusart[5] = 3;
                            }
                            $htxt = "<textarea class=\"sem_inputbox\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" cols=\""
                                . $zusart[4] . "\" rows=\"" . $zusart[5] . "\"" . $tempdis . ">" . $zustemp[$i] . "</textarea>" . $temp;
                            break;
                        case "email":
                            $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"emailzusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\""
                                . $zustemp[$i] . "\" size=\"50\"" . $tempdis . ">" . $temp;
                            break;
                        default:
                            $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\""
                                . $zustemp[$i] . "\" size=\"50\"" . $tempdis . ">" . $temp;
                            break;
                    }
                } else {
                    $htxt = "<input class=\"sem_inputbox\" type=\"text\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\"" . $zustemp[$i]
                        . "\" size=\"50\"" . $tempdis . ">" . $temp;
                }
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
                $zwang = 0;
                if ($zusart[1] == 1) {
                    $zwang = 1;
                }
                $hidden .= "<input type=\"hidden\" id=\"opt" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "opt\" value=\"" . $zwang . "\">";
            } else {
                $hidden .= "<input type=\"hidden\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\"\"><input type=\"hidden\" name=\"zusatz" . ($i + 1) . "opt\" value=\"0\">";
            }
        }

        // AGB-Bestaetigung anzeigen
        if (MatukioHelperSettings::getSettings('agb_text', '') != "" AND ($buchopt[0] > 1 OR $this->art == 3) AND $this->art != 2) {
            $htx1 = "<input class=\"sem_inputbox\" type=\"checkbox\" name=\"veragb\" value=\"1\"";
            if ($buchopt[0] == 2) {
                $htx1 .= " checked=\"checked\"";
                if ($this->art == 0 OR $this->art == 2 OR $this->art == 4 OR $tempdis != "") {
                    $htx1 .= " disabled";
                }
            }
            $htx1 .= ">" . $reqfield;
            $htxt = JURI::ROOT() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&option=" . JRequest::getCmd('option') . "&view=agb";
            $htxt = "<a href=\"" . $htxt . "\" class=\"modal\" rel=\"{handler: 'iframe', size: {x:500, y:350}}\">" . JTEXT::_('COM_MATUKIO_TERMS_AND_CONDITIONS') . "</a>";
            //$htxt = str_replace("SEM_AGB", $htxt, JTEXT::_('COM_MATUKIO_I_AGREE_WITH'));
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($htx1, 'd', 'r', '20%', 'sem_rowd') . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_rowd') . "</tr>";
        } else {
            $hidden .= "<input type=\"hidden\" name=\"veragb\" value=\"1\">";
        }
        $html .= $reqtext;
        $html .= MatukioHelperUtilsEvents::getTableHeader('e');
    }
}

// ---------------------------------
// Anzeige Funktionsknoepfe unten
// ---------------------------------

if (MatukioHelperSettings::getSettings('event_buttonposition', 2) > 0) {
    $html .= MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>" . MatukioHelperUtilsEvents::getTableCell($knopfunten,
        'd', 'c', '100%', 'sem_nav_d') . "</tr>" . MatukioHelperUtilsEvents::getTableHeader('e');
}
if (MatukioHelperSettings::getSettings('oldbookingform', false)) {
    $html .= $hidden;
}

// ---------------------------------------
// Ausgabe der unsichtbaren Formularfelder
// ---------------------------------------

if (MatukioHelperSettings::getSettings('oldbookingform', false)) {
    if ($this->event->nrbooked <= 1 OR MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) < 1) {
        $html .= "<input type=\"hidden\" name=\"nrbooked\" value=\"1\">";
    }
}
$this->uidtemp = -1;
if ($this->art == 3) {
    if ($usrid == 0) {
        $this->uidtemp = "";
    } else {
        $this->uidtemp = $usrid;
    }
}
$html .= MatukioHelperUtilsEvents::getHiddenFormElements(3, $this->catid, $this->search, $this->limit,
    $this->limitstart, $this->event->id, $this->dateid, $this->uidtemp);
echo $html;
echo MatukioHelperUtilsBasic::getCopyright();

// Oldbooking form
if ($this->art != 3) {
    ?>
<input type="hidden" name="option" value="com_matukio"/>
<input type="hidden" name="view" value="event"/>
<input type="hidden" name="controller" value="event"/>
<input type="hidden" name="task" value="bookevent"/>
    <?php
} else {
// $savechangeslink = JRoute::_("index.php?option=com_matukio&view=participants&task=changeBookingOrganizer&uid="
//    . $row->sid . "&cid=" . $this->kurs->id);
    ?>
<input type="hidden" name="option" value="com_matukio"/>
<input type="hidden" name="view" value="participants"/>
<input type="hidden" name="controller" value="participants"/>
<input type="hidden" name="task" value="changeBookingOrganizer"/>
    <?php
}
?>
</table>
</form>
