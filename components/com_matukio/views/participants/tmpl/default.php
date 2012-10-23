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

JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'matukio.css', 'media/com_matukio/css/');
JHTML::_('script', 'matukio.js', 'media/com_matukio/js/');


$document = &JFactory::getDocument();
$my = &JFactory::getuser();

JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');

// ---------------------------------
// Anzeige Kopfbereich mit Auswahl
// ---------------------------------

$buchopt = MatukioHelperUtilsEvents::getEventBookableArray(0, $this->kurs, 0);
?>
<form action="index.php" method="post" name="FrontForm">
<?php
$knopfunten = MatukioHelperUtilsEvents::getEventlistHeader(($this->art + 1));
$zurueck = array(0, 1, 2, 24);

$backlink = JRoute::_("index.php?option=com_matukio&");
// javascript:semauf(" . $zurueck[$this->art . ",'','');\"

$knopfoben = "<a title=\"" . JTEXT::_('COM_MATUKIO_BACK') . "\" href=\"javascript:history.go(-1)\"><img src=\""
    . MatukioHelperUtilsBasic::getComponentImagePath()
    . "1032.png\" border=\"0\" align=\"absmiddle\"></a>";

$knopfunten .= "<button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"history.go(-1)\"><img src=\""
    . MatukioHelperUtilsBasic::getComponentImagePath() . "1016.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
    . JTEXT::_('COM_MATUKIO_BACK') . "</button>";

if ($this->art > 1) {
    $knopfoben .= MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->kurs->id, 3);
    $knopfunten .= " " . MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->kurs->id, 4);
}

if (count($this->rows) > 0 AND $this->art > 1) {
    $knopfoben .= MatukioHelperUtilsEvents::getPrintWindow(7, $this->kurs->id, '', '');
    $knopfoben .= MatukioHelperUtilsEvents::getPrintWindow(5, $this->kurs->id, '', '');

    $csvlink = JURI::ROOT() . 'index.php?option=com_matukio&view=printeventlist&format=raw&todo=csvlist&cid=' . $this->kurs->id;

    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_DOWNLOAD_CSV_FILE') . "\" href=\"" . $csvlink . "\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath()
        . "1632.png\" border=\"0\" align=\"absmiddle\"></a>";

    $knopfunten .= " " . MatukioHelperUtilsEvents::getPrintWindow(7, $this->kurs->id, '', 'b');
    $knopfunten .= " " . MatukioHelperUtilsEvents::getPrintWindow(5, $this->kurs->id, '', 'b');

    $knopfunten .= " <a href=\"" . $csvlink . "\" target=\"_blank\"><button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "1616.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
        . JTEXT::_('COM_MATUKIO_DOWNLOAD_CSV_FILE') . "</button></a>";
}

if ($this->art > 1) {
    // javascript:semauf('28','" . $this->kurs->id . "','');
    // wtf???    - should be Details eines gebuchten Kurses zeigen
    $booklink = JRoute::_("index.php?option=com_matukio&view=editbooking&task=new&cid=" . $this->kurs->id);

    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"" .$booklink . "\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "1132.png\" border=\"0\" align=\"absmiddle\"></a>";

    $knopfunten .= " <a href=\"" . $booklink . "\"><button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
        . MatukioHelperUtilsBasic::getComponentImagePath() . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
        . JTEXT::_('COM_MATUKIO_BOOK') . "</button></a>";
}
if (MatukioHelperSettings::getSettings('event_buttonposition', 2) == 0 OR MatukioHelperSettings::getSettings('event_buttonposition', 2) == 2) {
    echo $knopfoben;
}
MatukioHelperUtilsEvents::getEventlistHeaderEnd();

// ---------------------------------
// Anzeige Bereichsueberschrift
// ---------------------------------

$htxt = $this->kurs->title;
if ($this->kurs->cancelled == 1) {
    $htxt .= " (<span class=\"sem_cancelled\">" . JTEXT::_('COM_MATUKIO_CANCELLED') . "</span>)";
}
$temp1 = str_replace('SEM_TITLE', $htxt, JTEXT::_('COM_MATUKIO_FOLLOWING_USERS_BOOKED'));
MatukioHelperUtilsEvents::printHeading(JTEXT::_('COM_MATUKIO_BOOKINGS'), $temp1);

// ---------------------------------
// Anzeige der Spaltenueberschriften
// ---------------------------------

$html = MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>";
if ($this->art == 2) {
    $html .= MatukioHelperUtilsEvents::getTableCell('&nbsp;', 'h', 'c', 14, 'sem_row');
}
$html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NAME'), 'h', 'l', '', 'sem_row');
if ($this->art == 2) {
    $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_EMAIL'), 'h', 'l', '', 'sem_row');
    $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING'), 'h', 'c', '', 'sem_row');
}

$zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($this->kurs);
for ($i = 0; $i < count($zusfeld[0]); $i++) {
    if ($zusfeld[2][$i] == 1) {
        $zustmp = explode("|", $zusfeld[0][$i]);
        $html .= MatukioHelperUtilsEvents::getTableCell($zustmp[0], 'h', 'l', '', 'sem_row');
    }
}
$html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BOOKED_PLACES'), 'h', 'c', '', 'sem_row');
if ($this->art == 2) {
    if ($this->kurs->fees > 0) {
        $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_PAID'), 'h', 'c', '', 'sem_row');
    }
    if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
        $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CERTIFICATES'), 'h', 'c', '', 'sem_row');
    }
    if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
        $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_RATING'), 'h', 'c', '', 'sem_row');
    }
}
$html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_STATUS'), 'h', 'c', 12, 'sem_row');
$html .= "</tr>";

// ---------------------------------
// Anzeige der einzelnen Buchungen
// ---------------------------------

$n = count($this->rows);
if ($n > 0) {
    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
    $anzahl = 0;
    foreach ($this->rows as $row) {
        if (MatukioHelperSettings::getSettings('frontend_teilnehmernametyp', 1) == 0 AND $this->art < 2) {
            $row->name = $row->username;
        }
        if ($row->userid == 0) {
            $row->name = $row->aname;
            $row->email = $row->aemail;
        }
        $anzahl = $anzahl + $row->nrbooked;
        $bild = "2502.png";
        $altbild = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
        if ($anzahl > $this->kurs->maxpupil) {
            if ($this->kurs->stopbooking < 1) {
                $bild = "2501.png";
                $altbild = JTEXT::_('COM_MATUKIO_WAITLIST');
            } else {
                $bild = "2500.png";
                $altbild = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
            }
        }
        if ($this->kurs->cancelled == 1) {
            $bild = "2500.png";
            $altbild = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
        }
        $certtitel = JTEXT::_('COM_MATUKIO_CERTIFICATE');
        if ($row->certificated == 1) {
            $certtitel = JTEXT::_('COM_MATUKIO_WITHDREW_CERTIFICATE');
        }

        $paidtitel = JTEXT::_('COM_MATUKIO_MARK_AS_PAID');

        if ($row->paid == 1) {
            $paidtitel = JTEXT::_('COM_MATUKIO_MARK_AS_NOT_PAID');
        }

        $html .= "\n<tr>";
        if ($this->art == 2) {
            //     javascript:semauf(7,'" . $row->sid . "','');
            $bookingcancellink = JRoute::_("index.php?option=com_matukio&view=participants&task=cancelBookingOrganizer&uid="
                . $row->sid . "&cid=" . $this->kurs->id);

            $htxt = "<a title=\"" . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "\" href=\"" . $bookingcancellink . "\"><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath() . "2202.png\" border=\"0\"></a>";

            $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', 14, "sem_row");
        }
        $htxt = $row->name;
        if ($this->art == 2) {
            // TODO check--      Details eines gebuchten Kurses zeigen
            // javascript:semauf('28','" . $this->kurs->id . "','" . $row->sid . "');    // Booking id!

            $eventid_l = $this->kurs->id.':'.JFilterOutput::stringURLSafe($this->kurs->title);
            $catid_l = $this->kurs->catid.':'.JFilterOutput::stringURLSafe(MatukioHelperCategories::getCategoryAlias($this->kurs->catid));

            if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
                $dlink = MatukioHelperRoute::getEventRoute($eventid_l, $catid_l, 3, $row->sid);
            } else {
                $dlink = "index.php?option=com_matukio&view=editbooking&booking_id=" . $row->uuid;
            }
            //echo $dlink;
            $detaillink = JRoute::_($dlink);

            $htxt = "<a href=\"" . $detaillink . "\">" . $row->name . "</a>";
        }
        $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '', "sem_row");

        if ($this->art == 2) {
            $html .= MatukioHelperUtilsEvents::getTableCell("<a href=\"mailto:" . $row->email . "\">" . $row->email
                . "</a>", 'd', 'l', '', "sem_row");
            $html .= MatukioHelperUtilsEvents::getTableCell(JHTML::_('date', $row->bookingdate,
                MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), 'd', 'c', '', "sem_row");
        }

        $zustemp = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);
        for ($i = 0; $i < count($zusfeld[0]); $i++) {
            if ($zusfeld[2][$i] == 1) {
                $html .= MatukioHelperUtilsEvents::getTableCell($zustemp[0][$i], 'd', 'l', '', 'sem_row');
            }
        }
        $html .= MatukioHelperUtilsEvents::getTableCell($row->nrbooked, 'd', 'c', '', "sem_row");
        if ($this->art == 2) {
            if ($this->kurs->fees > 0) {
                $htxt = "&nbsp;";
                if ($anzahl <= $this->kurs->maxpupil) {
                    // PAID LINK   javascript:semauf(14,'" . $row->sid . "','');
                    $paidlink = JRoute::_("index.php?option=com_matukio&view=participants&task=toogleStatusPayed&cid=" . $row->semid . "&uid=" . $row->sid);
                    $htxt = "<a title=\"" . $paidtitel . "\" href=\"" . $paidlink . "\"><img src=\""
                        . MatukioHelperUtilsBasic::getComponentImagePath() . "220" . $row->paid . ".png\" border=\"0\" align=\"absmiddle\"></a>";
                }
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', '', "sem_row");
            }
            if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
                $htxt = "&nbsp;";
                if ($anzahl <= $this->kurs->maxpupil) {
                    // Certificate USER LInk     javascript:semauf(13,'" . $row->sid . "','');
                    $certlink = JRoute::_("index.php?option=com_matukio&view=participants&task=certificateUser&uid=" . $row->sid . "&cid=" . $this->kurs->id);
                    $htxt = "<a title=\"" . $certtitel . "\" href=\"\"><img src=\""
                        . MatukioHelperUtilsBasic::getComponentImagePath() . "220" . $row->certificated . ".png\" border=\"0\" align=\"absmiddle\"></a>";
                    if ($row->certificated == 1) {
                        $htxt .= " " . MatukioHelperUtilsEvents::getPrintWindow(1, $row->sid, '', '');
                    }
                }
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', '', "sem_row");
            }
            if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
                $hinttext = JTEXT::_('COM_MATUKIO_RATING') . "::" . htmlspecialchars($row->comment);
                $htxt = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "240" . $row->grade
                    . ".png\" class=\"editlinktip hasTip\" title=\"" . $hinttext . "\">";
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', '', "sem_row");
            }
        }
        $html .= MatukioHelperUtilsEvents::getTableCell("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
            . $bild . "\" border=\"0\" alt=\"" . $altbild . "\">", 'd', 'c', '', "sem_row");
        $html .= "\n</tr>";
    }
} else {
    $spalten = 3;
    if ($this->art == 2) {
        $spalten = 9;
    }
    $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NO_BOOKING_FOUND'), 'd', 'l', '', 
        'sem_row', $spalten) . "</tr>";
}
$html .= MatukioHelperUtilsEvents::getTableHeader('e');

// ---------------------------------------
// Ausgabe der unsichtbaren Formularfelder
// ---------------------------------------

if ($this->kurs->nrbooked <= 1 || MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) < 1) {
    $html .= "<input type=\"hidden\" name=\"nrbooked\" value=\"1\">";
}
$html .= MatukioHelperUtilsEvents::getHiddenFormElements($zurueck[$this->art], $this->catid, $this->search, $this->limit,
    $this->limitstart, 0, $this->dateid, -1);

// ---------------------------------------
// Farbbeschreibungen anzeigen
// ---------------------------------------

$html .= MatukioHelperUtilsEvents::getColorDescriptions(JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED'),
    JTEXT::_('COM_MATUKIO_WAITLIST'), JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE'));

// ---------------------------------
// Anzeige Funktionsknoepfe unten
// ---------------------------------

if (MatukioHelperSettings::getSettings('event_buttonposition', 2) > 0) {
    $html .= MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>" . MatukioHelperUtilsEvents::getTableCell($knopfunten,
        'd', 'c', '100%', 'sem_nav_d') . "</tr>" . MatukioHelperUtilsEvents::getTableHeader('e');
}
echo $html;
echo "</table>";
