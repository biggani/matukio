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

JHTML::_('stylesheet', 'media/com_matukio/css/matukio.css');
$database = JFactory::getDBO();

$neudatum = MatukioHelperUtilsDate::getCurrentDate();
$cid = JFactory::getApplication()->input->getInt('cid', 0);
$kurs = JTable::getInstance('Matukio', 'Table');
$kurs->load($cid);
$database->setQuery("SELECT a.*, cc.*, a.id AS sid, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS a " .
    "LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '" . $kurs->id . "' ORDER BY a.id");
$rows = $database->loadObjectList();

$html = "";
if ($this->art > 2) {
    $html .= MatukioHelperUtilsBasic::getHTMLHeader();
    $this->art -= 2;
}

$html .= "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";
$html .= "\n<br /><center><span class=\"sem_list_title\">" . JTEXT::_('COM_MATUKIO_LIST_PARTICIPANTS') . "</span></center><br />";
$gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($kurs);
$gebucht = $gebucht->booked;
$freieplaetze = $kurs->maxpupil - $gebucht;
if ($freieplaetze < 0) {
    $freieplaetze = 0;
}
$html .= "\n" . MatukioHelperUtilsEvents::getTableHeader(2);

// Kursnummer
if ($kurs->semnum != "") {
    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NUMBER') . ':', 'd', 'l', '5%', 'sem_list_blank')
        . MatukioHelperUtilsEvents::getTableCell($kurs->semnum, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
}

// Titel
$html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TITLE') . ':', 'd', 'l', '5%', 'sem_list_blank')
    . MatukioHelperUtilsEvents::getTableCell($kurs->title, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";

// Seminarleiter
if ($kurs->teacher != "") {
    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TUTOR') . ':', 'd', 'l', '5%', 'sem_list_blank')
        . MatukioHelperUtilsEvents::getTableCell($kurs->teacher, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
}

// Beginn
if ($kurs->showbegin > 0) {
    $htxt = JHTML::_('date', $kurs->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
    if ($kurs->cancelled > 0) {
        $htxt = JTEXT::_('COM_MATUKIO_CANCELLED') . " (<del>" . $htxt . "</del>)";
    }
    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BEGIN') . ':', 'd', 'l', '5%', 'sem_list_blank')
        . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
}

// Ende
if ($kurs->showend > 0) {
    $htxt = JHTML::_('date', $kurs->end, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
    if ($kurs->cancelled > 0) {
        $htxt = JTEXT::_('COM_MATUKIO_CANCELLED') . " (<del>" . $htxt . "</del>)";
    }
    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_END') . ':', 'd', 'l', '5%', 'sem_list_blank')
        . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
}

// Gebuehr
if ($kurs->fees > 0) {
    $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . $kurs->fees;
    if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
        $htxt .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
    }
    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FEES') . ':', 'd', 'l', '5%', 'sem_list_blank')
        . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
}

$html .= "\n</table>";
// Unterschiftliste
if ($this->art == 1) {
    $html .= "\n<br />" . MatukioHelperUtilsEvents::getTableHeader(2, 'sem_list');
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('#', 'h', 'c', '10px', 'sem_list_head')
        . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BOOKING_ID'), 'h', 'l', '40px', 'sem_list_head')
        . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NAME'), 'h', 'l', '', 'sem_list_head')
        . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_SIGN'), 'h', 'l', '', 'sem_list_head') . "</tr>";
    $i = 1;
    foreach ($rows AS $row) {
        if ($row->userid == 0) {
            $row->name = $row->aname;
            $row->email = $row->aemail;
        }
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($i . '.<br />&nbsp;', 'd', 'r', '10px', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBooking::getBookingId($row->sid), 'd', 'l', '40px', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell($row->name, 'd', 'l', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'l', '', 'sem_list_row') . "</tr>";
        $i++;
        for ($j = 1, $n = $row->nrbooked; $j < $n; $j++) {
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($i . '<br />&nbsp;', 'd', 'r', '10px', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBooking::getBookingId($row->sid), 'd', 'l', '40px', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'l', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'l', '', 'sem_list_row') . "</tr>";
            $i++;
        }
    }
    $html .= "\n</table>";

//Detailliste
} else {
    $i = 1;
    foreach ($rows AS $row) {
        if ($row->userid == 0) {
            $row->name = $row->aname;
            $row->email = $row->aemail;
        }
        $htxt = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
        if ($i >= $kurs->maxpupil) {
            if ($kurs->stopbooking < 1) {
                $htxt = JTEXT::_('COM_MATUKIO_WAITLIST');
            } else {
                $htxt = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
            }
        }
        if ($kurs->cancelled > 0) {
            $htxt = JTEXT::_('COM_MATUKIO_CANCELLED');
        }
        $html .= "\n<br />" . MatukioHelperUtilsEvents::getTableHeader(2, 'sem_list');
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($i . '.', 'd', 'r', '', 'sem_list_head')
            . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NAME') . ":", 'd', 'l', '', 'sem_list_head')
            . MatukioHelperUtilsEvents::getTableCell($row->name, 'd', 'l', '', 'sem_list_head') . "</tr>";

        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_EMAIL') . ":</b>", 'd', 'l', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell($row->email, 'd', 'l', '', 'sem_list_row') . "</tr>";

        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . ":</b>", 'd', 'l', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBooking::getBookingId($row->sid), 'd', 'l', '', 'sem_list_row') . "</tr>";

        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING') . ":</b>", 'd', 'l', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell(JHTML::_('date', $row->bookingdate,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), 'd', 'l', '', 'sem_list_row') . "</tr>";

        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_STATUS') . ":</b>", 'd', 'l', '', 'sem_list_row')
            . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";

        if ($kurs->nrbooked > 1 AND MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ":</b>", 'd', 'l', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell($row->nrbooked, 'd', 'l', '', 'sem_list_row') . "</tr>";
        }
        if ($kurs->fees > 0) {
            $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " "
                . number_format((str_replace(",", ".", $kurs->fees) * $row->nrbooked), 2, ",", "");

            if ($kurs->nrbooked > 1) {
                $htxt .= " (" . MatukioHelperSettings::getSettings('currency_symbol', '$') . " "
                    . number_format(str_replace(",", ".", $kurs->fees), 2, ",", "") . " " . JTEXT::_('COM_MATUKIO_PRO_PERSON') . ")";
            }

            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_FEES') . ":</b>", 'd', 'l', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";

            $htxt = JTEXT::_('COM_MATUKIO_NO');
            if ($row->paid == 1) {
                $htxt = JTEXT::_('COM_MATUKIO_YES');
            }
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell("<b>" . JTEXT::_('COM_MATUKIO_PAID') . ":</b>", 'd', 'l', '', 'sem_list_row')
                . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";
        }
        $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($kurs);
        $zuserg = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);
        for ($z = 0; $z < count($zusfeld[0]); $z++) {
            if ($zusfeld[0][$z] != "") {
                $zusart = explode("|", $zusfeld[0][$z]);
                $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'd', 'r', '', 'sem_list_row')
                    . MatukioHelperUtilsEvents::getTableCell("<b>" . $zusart[0] . "</b>", 'd', 'l', '', 'sem_list_row')
                    . MatukioHelperUtilsEvents::getTableCell($zuserg[0][$z], 'd', 'l', '', 'sem_list_row') . "</tr>";
            }
        }
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsBooking::getBookingIdCodePicture($row->sid),
                            'd', 'c', '', 'sem_list_row', 3)
            . "</tr></table>";
        $i++;
    }
}
$html .= "<br />" . MatukioHelperUtilsBasic::getCopyright();
$html .= "</body></html>";
echo $html;