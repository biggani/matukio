<?php

/**
 * Matukio - Adminstrator
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 * Based on Seminar for Joomla!
 * by Dirk Vollmar
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.database.table');
JTable::addIncludePath(JPATH_COMPONENT . '/tables');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/defines.php');

class HTML_matukio
{

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + Ausgabe der Kursuebersicht                           +
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    function sem_g027($rows, $listen, $search, $pageNav, $limitstart, $limit)
    {
        JHTML::_('behavior.modal');
        $html = MatukioHelperUtilsBasic::printFormstart(2) . "\n<script type=\"text/javascript\">";
        $html .= "function semauf(stask, suid) {";
        $html .= "document.adminForm.task.value = stask;";
        $html .= "document.adminForm.uid.value = suid;";
        $html .= "document.adminForm.submit();}</script>";

        // --------------------------------------------------------
        // Anlegen der Auswahlmaske
        // --------------------------------------------------------

        $html .= "\n<center><table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
        $temp3 = "\n<input type=\"text\" name=\"search\" value=\"" . $search . "\" class=\"inputbox\" onChange=\"document.adminForm.submit();\" />";
        $temp4 = "";
        if (count($rows) > 0) {
            $temp4 = MatukioHelperUtilsAdmin::getBackendPrintWindow(1, '');
            //$temp4 = "asdfsdf";
        }
        $temp = array(JTEXT::_('COM_MATUKIO_SELECTION') . ":", $listen[3], $listen[0], JTEXT::_('COM_MATUKIO_ORDERING') . ":", $listen[1], $listen[2], JTEXT::_('COM_MATUKIO_SEARCH') . ":", ($temp3), ($temp4));
        $tempa = array("nw", "", "", "nw", "", "", "nw", "", "");
        $tempb = array("r", "l", "c", "r", "l", "c", "r", "l", "r");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, $tempb, $temp, "");
        $html .= "\n</table></center>";

        // ---------------------------------------
        // Ausgabe der Kurstabelle
        // ---------------------------------------

        $html .= "\n<table class=\"adminlist\"><thead>";
        $temp3 = "<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(" . count($rows) . ");\" />";
        $temp = array(($temp3), JTEXT::_('COM_MATUKIO_TITLE'), JTEXT::_('COM_MATUKIO_NR'), JTEXT::_('COM_MATUKIO_CATEGORY'), JTEXT::_('COM_MATUKIO_BEGIN'), JTEXT::_('COM_MATUKIO_END'), JTEXT::_('COM_MATUKIO_PUBLISHED'), JTEXT::_('COM_MATUKIO_CANCELLED'), JTEXT::_('COM_MATUKIO_BOOKINGS'), JTEXT::_('COM_MATUKIO_RATING'), JTEXT::_('COM_MATUKIO_HITS'), JTEXT::_('COM_MATUKIO_STATUS'), JTEXT::_('COM_MATUKIO_AVAILABILITY'), JTEXT::_('COM_MATUKIO_BOOK'), JTEXT::_('COM_MATUKIO_ID'));
        $tempa = array("", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, "", $temp, "");
        $html .= "</thead>";
        $html .= "<tbody>";
        $n = count($rows);
        if ($n > 0) {
            $k = 0;
            $neudatum = MatukioHelperUtilsDate::getCurrentDate();
            for ($i = 0, $n; $i < $n; $i++) {
                $row = &$rows[$i];
                $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                $gebucht = $gebucht->booked;
                $bild = "2502.png";
                $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_NOT_STARTED_YET');
                if ($neudatum > $row->end) {
                    $bild = "2500.png";
                    $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_EVDED');
                } else if ($neudatum > $row->begin) {
                    $bild = "2501.png";
                    $altbild = JTEXT::_('COM_MATUKIO_EVENT_IS_RUNNING');
                }
                $abild = "2502.png";
                $altabild = JTEXT::_('COM_MATUKIO_BOOKABLE');
                if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 1) {
                    $abild = "2500.png";
                    $altabild = JTEXT::_('COM_MATUKIO_FULLY_BOOKED');
                } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
                    $abild = "2501.png";
                    $altabild = JTEXT::_('COM_MATUKIO_WAITLIST');
                }
                $bbild = "2502.png";
                $altbbild = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
                if ($neudatum > $row->booked) {
                    $bbild = "2500.png";
                    $altbbild = JTEXT::_('COM_MATUKIO_EXCEEDED');
                }
                $temp1 = "<input type=\"checkbox\" id=\"cb" . $i . "\" name=\"cid[]\" value=\"" . $row->id . "\" onclick=\"isChecked(this.checked);\" />";
                $temp2 = "<a href=\"index.php?tmpl=component&option=com_matukio\" onclick=\"return listItemTask('cb" . $i . "','12')\">";
                if (strlen($row->title) < 30) {
                    $temp2 .= $row->title;
                } else {
                    $temp2 .= substr($row->title, 0, 27) . "...";
                }
                $temp2 .= "</a>";
                if (strlen($row->category) < 25) {
                    $temp3 = $row->category;
                } else {
                    $temp3 = substr($row->category, 0, 22) . "...";
                }
                $task = $row->published ? "20" : "18";
                $img = $row->published ? "2201.png" : "2200.png";
                $temp4 = "<a href=\"javascript: void(0);\" onclick=\"return listItemTask('cb" . $i . "','" . $task . "')\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $img . "\" border=\"0\" alt=\"\" /></a>";
                $task = $row->cancelled ? "25" : "24";
                $img = $row->cancelled ? "2201.png" : "2200.png";
                $temp12 = "<a href=\"javascript: void(0);\" onclick=\"return listItemTask('cb" . $i . "','" . $task . "')\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $img . "\" border=\"0\" alt=\"\" /></a>";
                $temp5 = "<button type=\"button\" onclick=\"semauf('29','" . $row->id . "');\" value=\"" . $gebucht . "\">" . $gebucht . "</button>";
                $temp6 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $bild . "\" border=\"0\" alt=\"" . $altbild . "\">";
                $temp7 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $abild . "\" border=\"0\" alt=\"" . $altabild . "\">";
                $temp8 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $bbild . "\" border=\"0\" alt=\"" . $altbbild . "\">";
                $temp9 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "240" . $row->grade . ".png\" border=\"0\" alt=\"" . JTEXT::_('COM_MATUKIO_RATING') . "\">";
                $temp10 = JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp11 = JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp = array($temp1, $temp2, $row->semnum, $temp3, $temp10, $temp11, $temp4, $temp12, $temp5, $temp9, $row->hits, $temp6, $temp7, $temp8, $row->id);
                $tempa = array("c", "", "c", "", "c", "c", "c", "c", "c", "c", "c", "c", "c", "c", "c");
                $klasse = "row" . $k;
                $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, $klasse);
                $k = 1 - $k;
            }
        } else {
            $html .= "\n<tr class=\"row0\"><td colspan=\"15\">" . JTEXT::_('COM_MATUKIO_NO_EVENT_FOUND') . "</td></tr>";
        }
        $html .= "\n</tbody>";
        $html .= "\n<tfoot><tr><th colspan=\"3\" nowrap=\"nowrap\">" . JTEXT::_('COM_MATUKIO_DISPLAY') . ": " . sem_f040(2, $limit) . "</th><th colspan=\"9\" nowrap=\"nowrap\">" . $pageNav->getPagesLinks() . "&nbsp;</th><th colspan=\"3\" nowrap=\"nowrap\">" . $pageNav->getPagesCounter() . "&nbsp;</th></tr></tfoot>";
        $html .= "\n</table>";

        // ---------------------------------------
        // Farbbeschreibungen anzeigen
        // ---------------------------------------

        $html .= "\n<br /><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";
        $html .= "\n<th width=\"33%\" valign=\"top\"><table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2502.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_EVENT_HAS_NOT_STARTED_YET');
        $temp = array(JTEXT::_('COM_MATUKIO_STATUS'), ($temp1));
        $tempa = array("l", "l");
        $tempb = array("nw", "");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, $tempb, $temp, "");
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2501.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_EVENT_IS_RUNNING');
        $temp = array("", ($temp1));
        $tempa = array("", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "");
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2500.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_EVENT_HAS_EVDED');
        $temp = array("", ($temp1));
        $tempa = array("", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "");
        $html .= "</table></th>";
        $html .= "\n<th width=\"33%\" valign=\"top\"><table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2502.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_BOOKABLE');
        $temp = array(JTEXT::_('COM_MATUKIO_AVAILABILITY'), ($temp1));
        $tempa = array("l", "l");
        $tempb = array("nw", "");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, $tempb, $temp, "");
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2501.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_WAITLIST');
        $temp = array("", ($temp1));
        $tempa = array("", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "");
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2500.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_FULLY_BOOKED');
        $temp = array("", ($temp1));
        $tempa = array("", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "");
        $html .= "</table></th>";
        $html .= "\n<th width=\"33%\" valign=\"top\"><table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2502.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
        $temp = array(JTEXT::_('COM_MATUKIO_BOOK'), ($temp1));
        $tempa = array("l", "l");
        $tempb = array("nw", "");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, $tempb, $temp, "");
        $temp1 = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2500.png\" border=\"0\" align=\"absmiddle\" /> " . JTEXT::_('COM_MATUKIO_EXCEEDED');
        $temp = array("", ($temp1));
        $tempa = array("", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "");
        $html .= "</table></th>";
        $html .= "</tr></table>";

        // --------------------------------------------------------
        // Anlegen der zusaetzliche Variablen und HTML-Ausgabe
        // --------------------------------------------------------

        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "<input type=\"hidden\" name=\"task\" value=\"\" />";
        $html .= "<input type=\"hidden\" name=\"uid\" value=\"\" />";
        $html .= "<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />";
        $html .= "<input type=\"hidden\" name=\"limitstart\" value=\"" . $limitstart . "\" />";
        $html .= "\n</form>";
        echo $html;
    }

// +++++++++++++++++++++++++++++++++++++++
// +++ Editierformular anzeigen        +++
// +++++++++++++++++++++++++++++++++++++++

    function sem_g006($row, $art)
    {
        JRequest::setVar('hidemainmenu', 1);
        JFilterOutput::objectHTMLSafe($row);

        $document = &JFactory::getDocument();
        $htxt = 5;
        if ($art == 3) {
            $htxt = 7;
        }
        $document->addCustomTag(sem_f027($htxt + MatukioHelperSettings::getSettings('event_image', 1)));
        JHTML::_('behavior.calendar');
        JHTML::_('behavior.tooltip');

        $html = MatukioHelperUtilsBasic::printFormstart(4) . "\n<table class=\"adminform\">" . MatukioHelperUtilsEvents::getEventEdit($row, $art) . "</table>";

        // Automatisches Setzen eines neuen matukios auf published

        $html .= sem_f015();
        if ($row->published == "") {
            $row->published = 1;
        }
        $html .= "\n<input type=\"hidden\" name=\"published\" value=\"" . $row->published . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"id\" value=\"" . $row->id . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"task\" value=\"\" /></form>";
        echo $html;
        echo JHTML::_('behavior.keepalive');
    }

// +++++++++++++++++++++++++++++++++++++++
// +++ Buchung fuer Kurs anzeigen      +++
// +++++++++++++++++++++++++++++++++++++++

    function sem_g029($kurs, $rows, $uid)
    {
        global $my;
        JHTML::_('behavior.modal');

        // ---------------------------------------
        // Ueberschrift
        // ---------------------------------------

        $html = MatukioHelperUtilsBasic::printFormstart(2) . "\n<table width=\"100%\"><tr><th width=\"90%\" style=\"text-align:left\">" . JTEXT::_('COM_MATUKIO_EVENT') . ": " . $kurs->title . "</th>";
        $html .= "<td style=\"text-align: right; white-space: nowrap\">" . MatukioHelperUtilsAdmin::getBackendPrintWindow(2, $kurs->id) . MatukioHelperUtilsAdmin::getBackendPrintWindow(4, $kurs->id) . MatukioHelperUtilsAdmin::getBackendPrintWindow(5, $kurs->id) . "</td></tr></table>";

        // ---------------------------------------
        // Ausgabe der Kurstabelle
        // ---------------------------------------

        $html .= "\n<table class=\"adminlist\"><thead>";
        $temp3 = "<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(" . count($rows) . ");\" />";

        // -------------------------- BEGIN dirty hack -------------------------------------
        //
        // 1. We don't need the name and eMail as separate header, so we remove
        //
        //     JTEXT::_('COM_MATUKIO_NAME')
        //
        // and
        //
        //     'JTEXT::_('COM_MATUKIO_EMAIL')
        //
        // from the array
        $temp = array($temp3, JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING'), JTEXT::_('COM_MATUKIO_BOOKED_PLACES'));

        // 2. We need our booking fields
        $bookingfields  = MatukioHelperUtilsBooking::getBookingFields();

        // 3. Then we generate the headers
        $bookingHeaders = array();
        foreach ($bookingfields as $field) {
          $bookingHeaders[] = JTEXT::_($field->label);
	      }

	      // 4. now we can merge it
        $temp = array_merge($temp, $bookingHeaders);

        // -------------------------- END dirty hack -------------------------------------

        if ($kurs->fees > 0) {
            $temp[] = JTEXT::_('COM_MATUKIO_PAID');
        }
        array_push($temp, JTEXT::_('COM_MATUKIO_CERTIFICATES'), JTEXT::_('COM_MATUKIO_RATING'), JTEXT::_('COM_MATUKIO_COMMENT'), JTEXT::_('COM_MATUKIO_STATUS'));
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", "", "", $temp, "");
        $html .= "</thead><tbody>";

        // Schleife fuer die einzelnen Kurse

        $n = count($rows);
        if ($n > 0) {
            $k = 0;
            $neudatum = MatukioHelperUtilsDate::getCurrentDate();
            $anzahl = 0;
            $i = 0;
            foreach ($rows as $row) {
                if ($row->userid == 0) {
                    $row->name = $row->aname;
                    $row->email = $row->aemail;
                }
                $anzahl = $anzahl + $row->nrbooked;
                $bild = "2502.png";
                $altbild = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
                if ($anzahl > $kurs->maxpupil) {
                    if ($kurs->stopbooking < 1) {
                        $bild = "2501.png";
                        $altbild = JTEXT::_('COM_MATUKIO_WAITLIST');
                    } else {
                        $bild = "2500.png";
                        $altbild = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
                    }
                }
                $temp = array();
                $temp[] = "<input type=\"checkbox\" id=\"cb" . $i . "\" name=\"cid[]\" value=\"" . $row->sid . "\" onclick=\"isChecked(this.checked);\" />";

                $link = "index.php?option=com_matukio&controller=bookings&task=editBooking&booking_id=" . $row->sid;

                // -------------------------- BEGIN dirty hack -------------------------------------
                // We don't need the name and email address as separate entries.
                //
                // $temp[] = '<a href="'. $link . '">' . $row->name . '</a>';
                // $temp[] = "<a href=\"mailto:" . $row->email . "\">" . $row->email . "</a>";
                //
                // -------------------------- END dirty hack -------------------------------------

                $temp[] = JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')) .
                    ", " . JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp[] = $row->nrbooked;

                // -------------------------------- BEGIN dirty hack -------------------------------------
                // 1. we need the values from `newfields`
                $newfields = explode(";", $row->newfields);
                $values = array();

                // 2. we have to separate the ID from the value
                foreach ($newfields as $val){
                  $tmp = explode("::", $val);
                  if(count($tmp) > 1){
                    $values[$tmp[0]]=$tmp[1];
                  }
                }
                // 3. now we can assign the values
                foreach ($bookingfields as $field) {
                  $temp[] = ''. $values[$field->id];
                }
                // -------------------------------- END dirty hack --------------------------------------

                $tempa = array("c", "", "", "c", "c");
                if ($kurs->fees > 0) {
                    $htxt = "&nbsp;";
                    if ($anzahl <= $kurs->maxpupil) {
                        $paidbild = "2200.png";
                        $paidtitel = JTEXT::_('COM_MATUKIO_MARK_AS_PAID');
                        if ($row->paid == 1) {
                            $paidbild = "2201.png";
                            $paidtitel = JTEXT::_('COM_MATUKIO_MARK_AS_NOT_PAID');
                        }
                        $htxt = "<a title=\"" . $paidtitel . "\" href=\"javascript: void(0);\" onclick=\"return listItemTask('cb" . $i
                            . "','27')\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $paidbild . "\" border=\"0\" alt=\""
                            . JTEXT::_('COM_MATUKIO_PAID') . "\"></a>";
                    }
                    $temp[] = $htxt;
                    $tempa[] = "c";
                }
                $htxt = "&nbsp;";
                if ($anzahl <= $kurs->maxpupil) {
                    $certbild = "2200.png";
                    $certtemp = "";
                    $certtitel = JTEXT::_('COM_MATUKIO_CERTIFICATE');
                    if ($row->certificated == 1) {
                        $certbild = "2201.png";
                        $certtemp = " " . MatukioHelperUtilsAdmin::getBackendPrintWindow(3, $row->sid);
                        $certtitel = JTEXT::_('COM_MATUKIO_WITHDREW_CERTIFICATE');
                    }
                    $htxt = "<a title=\"" . $certtitel . "\" href=\"javascript: void(0);\" onclick=\"return listItemTask('cb" . $i
                        . "','26')\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $certbild . "\" border=\"0\" alt=\""
                        . JTEXT::_('COM_MATUKIO_CERTIFICATES') . "\"></a>" . $certtemp;
                }
                $temp[] = $htxt;
                $tempa[] = "c";
                $temp [] = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "240" . $row->grade . ".png\" border=\"0\" alt=\""
                    . JTEXT::_('COM_MATUKIO_RATING') . "\">";
                $tempa[] = "c";
                $temp[] = $row->comment;
                $tempa[] = "";
                $temp[] = "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $bild . "\" border=\"0\" alt=\"" . $altbild . "\">";
                $tempa[] = "c";
                $klasse = "row" . $k;
                $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, $klasse);
                $k = 1 - $k;
                $i++;
            }
        } else {
            $html .= "\n<tr class=\"row0\"><td colspan=\"10\">." . JTEXT::_('COM_MATUKIO_NO_BOOKING_FOUND') . "</td></tr>";
        }
        $html .= "\n</tbody></table>";

        // ---------------------------------------
        // Farbbeschreibungen anzeigen
        // ---------------------------------------

        $html .= "\n<br /><center><table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";
        $html .= "\n<td align=\"center\" width=\"33%\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2502.png\" border=\"0\" align=\"absmiddle\" /> <b>" . JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED') . "</b></td>";
        $html .= "\n<td align=\"center\" width=\"33%\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2501.png\" border=\"0\" align=\"absmiddle\" /> <b>" . JTEXT::_('COM_MATUKIO_WAITLIST') . "</b></td>";
        $html .= "\n<td align=\"center\" width=\"33%\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2500.png\" border=\"0\" align=\"absmiddle\" /> <b>" . JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE') . "</b></td>";
        $html .= "\n</tr></table></center>";

        // ---------------------------------------
        // Zusaetzliche Variablen uebergeben
        // ---------------------------------------

        $html .= sem_f015();
        $html .= "\n<input type=\"hidden\" name=\"uid\" value=\"" . $uid . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"task\" value=\"\" />";
        $html .= "\n<input type=\"hidden\" name=\"event_id\" value=\"" . $kurs->id . "\" />";
        $html .= "\n<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />";
        $html .= "\n</form>";
        echo $html;
    }

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + Statistikuebersicht anzeigen                         +
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    function sem_g030($stats, $mstats)
    {
        $database = &JFactory::getDBO();
        $html = MatukioHelperUtilsBasic::printFormstart(2) . "\n<script type=\"text/javascript\">";
        $html .= "function semauf(semy, semm) {";
        $html .= "document.adminForm.year.value = semy;";
        $html .= "document.adminForm.month.value = semm;";
        $html .= "document.adminForm.submit();}</script>";
        JHTML::_('stylesheet', 'matukio.css', 'media/com_matukio/backend/css/');


        // --------------------------------------------------------
        // Anlegen des Kopfs und der Ueberschrift
        // --------------------------------------------------------

        $n = count($stats);
        if ($n == 2) {
            $o = 1;
        } else {
            $o = 0;
        }
        for ($i = $o, $n; $i < $n; $i++) {
            $daten = $mstats[$i];
            $m = count($daten);
            if ($n > ($o + 1)) {
                $html .= "\n<div style=\"border: 1px solid #C0C0F0;width: 100%;border-style: ridge;\">";
            }
            $html .= "\n<br /><center><a style=\"font-size:18px; font-weight: bold;\" href=\"#\" onclick=\"semauf('" . $stats[$i]->year . "','');\">" . $stats[$i]->year . "</a></center>";
            $html0 = "";
            $html1 = "\n<table class=\"adminlist\">";
            // --------------------------------------------------------
            // Anlegen Tabellenkopfes
            // --------------------------------------------------------

            $html1 .= "\n<thead>";
            $temp = array(JTEXT::_('COM_MATUKIO_MONTH'), JTEXT::_('COM_MATUKIO_EVENTS'), JTEXT::_('COM_MATUKIO_HITS'), JTEXT::_('COM_MATUKIO_BOOKINGS'), JTEXT::_('COM_MATUKIO_CERTIFICATES'), JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT'), JTEXT::_('COM_MATUKIO_AVERAGE_UTILISATION'), JTEXT::_('COM_MATUKIO_HITS') . " / " . JTEXT::_('COM_MATUKIO_EVENT'), JTEXT::_('COM_MATUKIO_BOOKINGS') . " / " . JTEXT::_('COM_MATUKIO_EVENT'), JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . " / " . JTEXT::_('COM_MATUKIO_EVENT'));
            $tempa = array("nw", "nw", "nw", "nw", "nw", "nw", "c2", "", "", "");
            $html1 .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, "", $temp, "");
            $html1 .= "\n</thead>";

            // --------------------------------------------------------
            // Anlegen des Tabellenkoerpers
            // --------------------------------------------------------

            $html1 .= "<tbody>";
            if ($m > 0) {
                $image = "http://chart.apis.google.com/chart?cht=lc";
                $image .= "&amp;chs=400x200";
                $image .= "&amp;chco=ffa844,44cc44,4444ff,ff4444";
                $image .= "&amp;chm=b,ff8800,0,4,0|b,00cc00,1,4,0|b,0000ff,2,4,0|b,ff0000,3,4,0";
                $image .= "&amp;chg=0,50";
                $image .= "&amp;chdl=" . JTEXT::_('COM_MATUKIO_HITS') . "|" . JTEXT::_('COM_MATUKIO_BOOKINGS') . "|" . JTEXT::_('COM_MATUKIO_CERTIFICATES') . "|" . JTEXT::_('COM_MATUKIO_EVENTS');
                $image .= "&amp;chxt=x,y";
                $chl = array(JTEXT::_('JANUARY_SHORT'), JTEXT::_('FEBRUARY_SHORT'), JTEXT::_('MARCH_SHORT'), JTEXT::_('APRIL_SHORT'), JTEXT::_('MAY_SHORT'), JTEXT::_('JUNE_SHORT'), JTEXT::_('JULY_SHORT'), JTEXT::_('AUGUST_SHORT'), JTEXT::_('SEPTEMBER_SHORT'), JTEXT::_('OCTOBER_SHORT'), JTEXT::_('NOVEMBER_SHORT'), JTEXT::_('DECEMBER_SHORT'));
                $imagea = "http://chart.apis.google.com/chart?cht=p3&amp;chs=230x100&amp;&amp;chco=";
                $imagehi = $imagea . "ff8800&amp;chd=t:";
                $imagebo = $imagea . "00cc00&amp;chd=t:";
                $imagece = $imagea . "0000ff&amp;chd=t:";
                $imageco = $imagea . "ff0000&amp;chd=t:";
                $highest = array();
                for ($l = 0, $m; $l < $m; $l++) {
                    $highest[] = $daten[$l]->hits;
                }
                $maximum = max($highest);
                if ($maximum < 1) {
                    $maximum = 1;
                }
                $image .= "&amp;chxl=0:|" . implode('|', $chl) . "|1:|0|" . (round($maximum * 0.25)) . "|" . (round($maximum * 0.5)) . "|" . (round($maximum * 0.75)) . "|" . $maximum;
                $image .= "&amp;chd=t:";
                $ihits = array();
                $ibookings = array();
                $icertificated = array();
                $icourses = array();
                $phits = array();
                $pbookings = array();
                $pcertificated = array();
                $pcourses = array();
                $plhits = array();
                $plbookings = array();
                $plcertificated = array();
                $plcourses = array();

                $k = 0;
                for ($l = 0, $m; $l < $m; $l++) {
                    if ($daten[$l]->maxpupil == "" OR $daten[$l]->maxpupil == 0) {
                        $temp0 = 0;
                    } else {
                        $temp0 = round($daten[$l]->bookings * 100 / $daten[$l]->maxpupil, 0);
                    }
                    $temp1 = sem_f016($temp0);
                    $temp11 = $temp0 . "%";
                    if ($daten[$l]->hits == "" OR $daten[$l]->hits == 0) {
                        $temp2 = 0;
                        $teiler = 1;
                    } else {
                        $temp2 = $daten[$l]->hits;
                        $phits[] = $stats[$i]->hits != 0 ? round(($temp2 * 100) / $stats[$i]->hits) : 100;
                        $plhits[] = $chl[$l];
                    }
                    $ihits[] = round(($temp2 * 100) / $maximum);
                    if ($daten[$l]->bookings == "" OR $daten[$l]->bookings == 0) {
                        $temp3 = 0;
                    } else {
                        $temp3 = $daten[$l]->bookings;
                        $pbookings[] = $stats[$i]->bookings != 0 ? round(($temp3 * 100) / $stats[$i]->bookings) : 100;
                        $plbookings[] = $chl[$l];
                    }
                    $ibookings[] = round(($temp3 * 100) / $maximum);
                    if ($daten[$l]->certificated == "" OR $daten[$l]->certificated == 0) {
                        $temp9 = 0;
                    } else {
                        $temp9 = $daten[$l]->certificated;
                        $pcertificated[] = $stats[$i]->certificated != 0 ? round(($temp9 * 100) / $stats[$i]->certificated) : 100;
                        $plcertificated[] = $chl[$l];
                    }
                    $icertificated[] = round(($temp9 * 100) / $maximum);
                    if ($daten[$l]->maxpupil == "" OR $daten[$l]->maxpupil == 0) {
                        $temp4 = 0;
                    } else {
                        $temp4 = $daten[$l]->maxpupil;
                    }
                    if ($daten[$l]->courses == "" OR $daten[$l]->courses == 0) {
                        $temp5 = 0;
                        $temp6 = 0;
                        $temp7 = 0;
                    } else {
                        $temp5 = $daten[$l]->courses != 0 ? round($daten[$l]->hits / $daten[$l]->courses) : $daten[$l]->hits;
                        $temp6 = $daten[$l]->courses != 0 ? round($daten[$l]->bookings / $daten[$l]->courses) : $daten[$l]->bookings;
                        $temp7 = $daten[$l]->courses != 0 ? round($daten[$l]->maxpupil / $daten[$l]->courses) : $daten[$l]->maxpupil;
                        $pcourses[] = $stats[$i]->courses != 0 ? round((($daten[$l]->courses) * 100) / $stats[$i]->courses) : 100;
                        $plcourses[] = $chl[$l];
                    }
                    $icourses[] = round((($daten[$l]->courses) * 100) / $maximum);
                    $temp8 = "<a href=\"#\" onclick=\"semauf('" . $stats[$i]->year . "','" . ($l + 1) . "')\">" . $daten[$l]->year . "</a>";
                    $temp = array(($temp8), ($daten[$l]->courses), ($temp2), ($temp3), ($temp9), ($temp4), ($temp1), ($temp11), ($temp5), ($temp6), ($temp7));
                    $tempa = array("c", "r", "r", "r", "r", "r", "r", "r", "r", "r", "r");
                    $tempb = array("", "", "", "", "", "", "nw", "", "", "", "");
                    $html1 .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, "row" . $k);
                    $k = 1 - $k;
                }
                $image .= implode(',', $ihits) . "|" . implode(',', $ibookings) . "|" . implode(',', $icertificated) . "|" . implode(',', $icourses) . "|0,0";

                $imagehi .= implode(',', $phits) . "&amp;chl=" . implode('|', $plhits);
                $imagebo .= implode(',', $pbookings) . "&amp;chl=" . implode('|', $plbookings);
                $imagece .= implode(',', $pcertificated) . "&amp;chl=" . implode('|', $plcertificated);
                $imageco .= implode(',', $pcourses) . "&amp;chl=" . implode('|', $plcourses);
                $html0 .= "<br /><center><table width= \"100\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
                $html0 .= "<tr><th colspan=\"2\" rowspan=\"2\"><img src=\"" . $image . "\" border=\"0\"></th>";
                $html0 .= "<th><img src=\"" . $imagehi . "\" border=\"0\"></th>";
                $html0 .= "<th><img src=\"" . $imagebo . "\" border=\"0\"></th></tr>";
                $html0 .= "<tr><th><img src=\"" . $imagece . "\" border=\"0\"></th>";
                $html0 .= "<th><img src=\"" . $imageco . "\" border=\"0\"></th></tr>";
                $html0 .= "</table></center><br />";
            } else {
                $html1 .= "<tr class=\"row0\"><td colspan=\"9\">" . JTEXT::_('COM_MATUKIO_NO_STATS') . "</td>";
            }
            $html .= $html0 . $html1 . "</tbody>";

            // --------------------------------------------------------
            // Anlegen des Tabellenfusses
            // --------------------------------------------------------

            if ($m > 0) {
                $html .= "<tfoot>";
                if ($stats[$i]->hits == "") {
                    $temp1 = 0;
                } else {
                    $temp1 = $stats[$i]->hits;
                }
                if ($stats[$i]->bookings == "") {
                    $temp2 = 0;
                } else {
                    $temp2 = $stats[$i]->bookings;
                }
                if ($stats[$i]->certificated == "") {
                    $temp9 = 0;
                } else {
                    $temp9 = $stats[$i]->certificated;
                }
                if ($stats[$i]->maxpupil == "") {
                    $temp3 = 0;
                } else {
                    $temp3 = $stats[$i]->maxpupil;
                }
                if ($stats[$i]->maxpupil == 0) {
                    $temp4 = "0%";
                } else {
                    $temp4 = round($stats[$i]->bookings * 100 / $stats[$i]->maxpupil, 0) . "%";
                }
                if ($stats[$i]->courses == 0) {
                    $temp5 = 0;
                    $temp6 = 0;
                    $temp7 = 0;
                } else {
                    $temp5 = round($stats[$i]->hits / $stats[$i]->courses);
                    $temp6 = round($stats[$i]->bookings / $stats[$i]->courses);
                    $temp7 = round($stats[$i]->maxpupil / $stats[$i]->courses);
                }
                $temp = array(JTEXT::_('COM_MATUKIO_SUMMARY'), ($stats[$i]->courses), ($temp1), ($temp2), ($temp9), ($temp3), ($temp4), ($temp5), ($temp6), ($temp7));
                $tempa = array("c", "r", "r", "r", "r", "r", "r", "r", "r", "r");
                $tempb = array("", "", "", "", "", "", "c2", "", "", "");
                $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, $tempb, $temp, "");
                $html .= "\n</tfoot>";
            }
            // --------------------------------------------------------
            // Anlegen des Seitenendes und Ausgabe
            // --------------------------------------------------------

            $html .= "</table>";
            if ($n > ($o + 1)) {
                $html .= "</div>";
            }
            $html .= "<br />";
        }
        if ($n > 0) {
            $html .= JTEXT::_('COM_MATUKIO_INFO_RELATED_TO_EVENTS') . "<br />";
        }
        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "<input type=\"hidden\" name=\"task\" value=\"30\" />";
        $html .= "<input type=\"hidden\" name=\"year\" value=\"\" />";
        $html .= "<input type=\"hidden\" name=\"month\" value=\"\" />";
        $html .= "\n</form>";

        echo $html;
    }

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + Statistik pro Monat - Jahr anzeigen                  +
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    function sem_g031($rows, $mon, $yea)
    {
        $database = &JFactory::getDBO();

        // --------------------------------------------------------
        // Anlegen des Monats und des Jahrs
        // --------------------------------------------------------

        $html = MatukioHelperUtilsBasic::printFormstart(2) . "\n<center><div style=\"font-size:18px; font-weight: bold;\">" . $yea . $mon . "</div></center><br />";
        $html .= "\n<table class=\"adminlist\">";

        // --------------------------------------------------------
        // Anlegen des Tabellenkopfes
        // --------------------------------------------------------

        $html .= "\n<thead>";
        $temp = array(JTEXT::_('COM_MATUKIO_NR'), JTEXT::_('COM_MATUKIO_TITLE'), JTEXT::_('COM_MATUKIO_CATEGORY'), JTEXT::_('COM_MATUKIO_BEGIN'), JTEXT::_('COM_MATUKIO_END'), JTEXT::_('COM_MATUKIO_HITS'), JTEXT::_('COM_MATUKIO_BOOKINGS'), JTEXT::_('COM_MATUKIO_CERTIFICATES'), JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT'), JTEXT::_('COM_MATUKIO_AVERAGE_UTILISATION'));
        $tempa = array("nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "nw", "c2");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, "", $temp, "");
        $html .= "\n</thead>";

        // --------------------------------------------------------
        // Anlegen des Tabellenkoerpers
        // --------------------------------------------------------

        $html .= "\n<tbody>";
        $n = count($rows);
        if ($n > 0) {
            $k = 0;
            for ($i = 0, $n; $i < $n; $i++) {
                $row = $rows[$i];
                $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                $gebucht = $gebucht->booked;
                if ($row->maxpupil == 0) {
                    $temp0 = 0;
                } else {
                    $temp0 = round($gebucht * 100 / $row->maxpupil, 0);
                }
                $usage = sem_f016($temp0);
                $temp1 = $temp0 . "%";
                $temp2 = JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp3 = JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp = array($row->semnum, $row->title, $row->category, $temp2, $temp3, $row->hits, $gebucht, $row->certificated, $row->maxpupil, $usage, $temp1);
                $tempa = array("r", "l", "l", "c", "c", "r", "r", "r", "r", "r", "r");
                $klasse = "row" . $k;
                $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, $klasse);
                $k = 1 - $k;
            }
        } else {
            $html .= "\n<tr class=\"row0\"><td colspan=\"10\">" . JTEXT::_('COM_MATUKIO_NO_STATS') . "</td></tr>";
        }
        $html .= "\n</tbody>";

        // --------------------------------------------------------
        // Anlegen der zusaetzliche Variablen und HTML-Ausgabe
        // --------------------------------------------------------

        $html .= "\n</table>";
        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "<input type=\"hidden\" name=\"task\" value=\"\" />";
        $html .= "\n</form>";
        echo $html;
    }

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + Ausgabe der Vorlagenuebersicht                       +
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    function sem_g032($rows, $clist, $search, $pageNav, $limitstart, $limit)
    {
        $html = MatukioHelperUtilsBasic::printFormstart(2) . "\n<script type=\"text/javascript\">";
        $html .= "function semauf(stask, suid) {";
        $html .= "document.adminForm.task.value = stask;";
        $html .= "document.adminForm.uid.value = suid;";
        $html .= "document.adminForm.submit();}</script>";

        // --------------------------------------------------------
        // Anlegen der Auswahlmaske
        // --------------------------------------------------------

        $html .= "\n<center><table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
        $temp3 = "\n<input type=\"text\" name=\"search\" value=\"" . $search . "\" class=\"inputbox\" onChange=\"document.adminForm.submit();\" />";
        $temp = array(JTEXT::_('COM_MATUKIO_SELECTION') . ":", $clist, JTEXT::_('COM_MATUKIO_SEARCH') . ":", ($temp3));
        $tempa = array("nw", "", "nw", "");
        $tempb = array("r", "l", "r", "l");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, $tempb, $temp, "");
        $html .= "\n</table></center>";

        // ---------------------------------------
        // Ausgabe der Kurstabelle
        // ---------------------------------------

        $html .= "\n<table class=\"adminlist\"><thead>";
        $temp3 = "<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(" . count($rows) . ");\" />";
        $temp = array(($temp3), JTEXT::_('COM_MATUKIO_TEMPLATE'), JTEXT::_('COM_MATUKIO_CATEGORY'), JTEXT::_('COM_MATUKIO_OWNER'), JTEXT::_('COM_MATUKIO_CREATED_ON'), JTEXT::_('COM_MATUKIO_CHANGED_ON'), JTEXT::_('COM_MATUKIO_PUBLISHED'), JTEXT::_('COM_MATUKIO_ID'));
        $tempa = array("", "nw", "nw", "nw", "nw", "nw", "nw");
        $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("th", $tempa, "", $temp, "");
        $html .= "</thead>";
        $html .= "<tbody>";
        $n = count($rows);
        if ($n > 0) {
            $k = 0;
            $neudatum = MatukioHelperUtilsDate::getCurrentDate();
            for ($i = 0, $n; $i < $n; $i++) {
                $row = &$rows[$i];
                $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                $gebucht = $gebucht->booked;
                $bild = "2502.png";
                $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_NOT_STARTED_YET');
                if ($neudatum > $row->end) {
                    $bild = "2500.png";
                    $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_EVDED');
                } else if ($neudatum > $row->begin) {
                    $bild = "2501.png";
                    $altbild = JTEXT::_('COM_MATUKIO_EVENT_IS_RUNNING');
                }
                $abild = "2502.png";
                $altabild = JTEXT::_('COM_MATUKIO_BOOKABLE');
                if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 1) {
                    $abild = "2500.png";
                    $altabild = JTEXT::_('COM_MATUKIO_FULLY_BOOKED');
                } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
                    $abild = "2501.png";
                    $altabild = JTEXT::_('COM_MATUKIO_WAITLIST');
                }
                $bbild = "2502.png";
                $altbbild = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
                if ($neudatum > $row->booked) {
                    $bbild = "2500.png";
                    $altbbild = JTEXT::_('COM_MATUKIO_EXCEEDED');
                }
                $temp1 = "<input type=\"checkbox\" id=\"cb" . $i . "\" name=\"cid[]\" value=\"" . $row->id . "\" onclick=\"isChecked(this.checked);\" />";
                $temp2 = "<a href=\"index.php?tmpl=component&option=com_matukio\" onclick=\"return listItemTask('cb" . $i . "','13')\">";
                if (strlen($row->pattern) < 30) {
                    $temp2 .= $row->pattern;
                } else {
                    $temp2 .= substr($row->pattern, 0, 27) . "...";
                }
                $temp2 .= "</a>";
                if (strlen($row->category) < 25) {
                    $temp3 = $row->category;
                } else {
                    $temp3 = substr($row->category, 0, 22) . "...";
                }
                $task = $row->published ? "21" : "19";
                $img = $row->published ? "2201.png" : "2200.png";
                $temp4 = "<a href=\"javascript: void(0);\" onclick=\"return listItemTask('cb" . $i . "','" . $task . "')\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $img . "\" border=\"0\" alt=\"\" /></a>";
                $temp = JFactory::getuser($row->publisher);
                $temp10 = $temp->name;
                $temp11 = JHTML::_('date', $row->publishdate, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->publishdate, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp12 = JHTML::_('date', $row->updated, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'))
                    . ", " . JHTML::_('date', $row->updated, MatukioHelperSettings::getSettings('time_format', 'H:i'));
                $temp = array($temp1, $temp2, $temp3, $temp10, $temp11, $temp12, $temp4, $row->id);
                $tempa = array("c", "c", "", "", "c", "c", "c", "c");
                $klasse = "row" . $k;
                $html .= "\n" . MatukioHelperUtilsAdmin::getTableLine("td", $tempa, "", $temp, $klasse);
                $k = 1 - $k;
            }
        } else {
            $html .= "\n<tr class=\"row0\"><td colspan=\"15\">" . JTEXT::_('COM_MATUKIO_NO_EVENT_FOUND') . "</td></tr>";
        }
        $html .= "\n</tbody>";
        $html .= "\n<tfoot><tr><th colspan=\"2\" nowrap=\"nowrap\">" . JTEXT::_('COM_MATUKIO_DISPLAY') . ": " . sem_f040(2, $limit) . "</th><th colspan=\"4\" nowrap=\"nowrap\">" . $pageNav->getPagesLinks() . "&nbsp;</th><th colspan=\"2\" nowrap=\"nowrap\">" . $pageNav->getPagesCounter() . "&nbsp;</th></tr></tfoot>";
        $html .= "\n</table>";

        // --------------------------------------------------------
        // Anlegen der zusaetzliche Variablen und HTML-Ausgabe
        // --------------------------------------------------------

        $html .= "\n<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\" />";
        $html .= "<input type=\"hidden\" name=\"task\" value=\"1\" />";
        $html .= "<input type=\"hidden\" name=\"uid\" value=\"\" />";
        $html .= "<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />";
        $html .= "<input type=\"hidden\" name=\"limitstart\" value=\"" . $limitstart . "\" />";
        $html .= "\n</form>";
        echo $html;
    }

// ++++++++++++++++++++++++++++++
// + Ausgabe der Einstellungen  +
// ++++++++++++++++++++++++++++++

    function showSettings($items_basic, $items_layout, $items_advanced, $items_security, $items_payment)
    {
        JHTML::_('behavior.tooltip');
        jimport('joomla.html.pane');

        $doc =& JFactory::getDocument();
        $doc->addStyleSheet( '../media/com_matukio/backend/css/settings.css' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.Check.js' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.CheckGroup.js' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.Dropdown.js' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.Radio.js' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.RadioGroup.js' );
        $doc->addScript( '../media/com_matukio/backend/js/Form.SelectOption.js' );

//        $script = "window.onload = function() {
//            var check = new Form.RadioGroup(document.getElement('matsettings'));
//        };";
//
//        $doc->addScriptDeclaration($script);

        echo '<form action="index.php" method="post" name="adminForm">';
        $pane =& JPane::getInstance('tabs', array('startOffset' => 0));
        echo $pane->startPane('pane');
        echo $pane->startPanel(JText::_('COM_MATUKIO_BASIC'), 'basic');
        ?>

    <div class="col60">
        <div id="matsettings">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_BASIC'); ?></legend>

            <table class="admintable">
                <?php
                foreach ($items_basic as $value) {

                    echo '<tr>';
                    echo '<td class="key">';
                    echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td colspan="2">';

                    echo MatukioHelperSettings::getSettingField($value);

                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </fieldset>
        </div>
    </div>
    <div class="clr"></div>
    <?php
        echo $pane->endPanel();

        echo $pane->startPanel(JText::_('COM_MATUKIO_LAYOUT'), 'layout');
        ?>
    <div class="col60">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_LAYOUT'); ?></legend>

            <table class="admintable">
                <?php
                foreach ($items_layout as $value) {

                    echo '<tr>';
                    echo '<td class="key">';
                    echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td colspan="2">';

                    echo MatukioHelperSettings::getSettingField($value);

                    echo '</td>';
                    echo '</tr>';

                }
                ?>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php

        echo $pane->endPanel();
        echo $pane->startPanel(JText::_('COM_MATUKIO_PAYMENT'), 'layout');
        ?>
    <div class="col60">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_PAYMENT'); ?></legend>

            <table class="admintable">
                <?php
                foreach ($items_payment as $value) {

                    echo '<tr>';
                    echo '<td class="key">';
                    echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td colspan="2">';

                    echo MatukioHelperSettings::getSettingField($value);

                    echo '</td>';
                    echo '</tr>';

                }
                ?>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php

        echo $pane->endPanel();
        echo $pane->startPanel(JText::_('COM_MATUKIO_ADVANCED'), 'layout');
        ?>
    <div class="col60">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_ADVANCED'); ?></legend>

            <table class="admintable">
                <?php
                foreach ($items_advanced as $value) {

                    echo '<tr>';
                    echo '<td class="key">';
                    echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td colspan="2">';

                    echo MatukioHelperSettings::getSettingField($value);

                    echo '</td>';
                    echo '</tr>';

                }
                ?>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php

        echo $pane->endPanel();
        echo $pane->startPanel(JText::_('COM_MATUKIO_SECURITY'), 'layout');
        ?>
    <div class="col60">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_SECURITY'); ?></legend>

            <table class="admintable">
                <?php
                foreach ($items_security as $value) {

                    echo '<tr>';
                    echo '<td class="key">';
                    echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td colspan="2">';

                    switch ($value->type) {
                        case 'textarea':
                            echo MatukioHelperSettings::getTextareaSettings($value->id, $value->title, $value->value);
                            break;

                        case 'select':
                            echo MatukioHelperSettings::getSelectSettings($value->id, $value->title, $value->value, $value->values);
                            break;


                        case 'text':
                        default:

                            echo MatukioHelperSettings::getTextSettings($value->id, $value->title, $value->value);
                            break;

                    }
                    echo '</td>';
                    echo '</tr>';

                }
                ?>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php

        echo $pane->endPanel();
        echo $pane->endPane();
        ?>

    <input type="hidden" name="option" value="com_matukio"/>
    <input type="hidden" name="type" value="config"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="uid" value=""/>

    <?php echo JHTML::_('form.token'); ?>

    <?php
    }

    /**
     * Über Matukio Seite
     */
    function showAboutMatukio()
    {
        ?>
    <div style="float:right;margin:10px;">
        <?php
        echo "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . 'logo.png' . "\" />";
        ?>
    </div>

    <div>
        <h1><?php echo JText::_('COM_MATUKIO_INFORMATIONS'); ?></h1>

        <h3><?php echo JText::_('COM_MATUKIO_VERSION'); ?></h3>

        <p><?php echo MATUKIO_VERSION; ?></p>

        <h3><?php echo JText::_('COM_MATUKIO_COPYRIGHT'); ?></h3>

        <p>Copyrigth &copy; <?php echo date("Y"); ?> Compojoom.com - Yves Hoppe</p>

        <h3><?php echo JText::_('COM_MATUKIO_LICENSE'); ?></h3>

        <p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
        <br/>

        <h3>Thank you</h3>
        <div>
            This software would not have been possible without the help of those listed here.
            THANK YOU for your continuous help, support and inspiration!
        </div>
        <ul>
            <li>
                <em>Dirk Vollmar</em> (<a href="http://seminar.vollmar.ws/"
                                          target="_blank">http://seminar.vollmar.ws/</a>)
                - for creating com_seminar for Joomla 1.5 (on which this extension is based)
            </li>
            <li>
                <em>Daniel Dimitrov</em> (<a href="http://compojoom.com" target="_blank">http://seminar.vollmar.ws</a>)
                -
                for his continous help, giving ideas and for compojoom ofc :)
            </li>

        </ul>


        <h3><?php echo JText::_('COM_MATUKIO_HELP'); ?></h3>

        <p>
            <a href="http://www.compojoom.com/">Matukio Main Site</a><br/>
        </p>

        <p><br/>
            Maps are created by Google Maps&trade;<br/>
            <br/>
            Google&trade; is a trademark of Google Inc.<br/>
            Google Maps&trade; is a trademark of Google Inc.<br/><br/>
        </p>
    </div>
    <?php
    }

}

?>
