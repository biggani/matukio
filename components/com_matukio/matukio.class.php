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
jimport('joomla.methods');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_basic.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_booking.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_date.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_events.php');


// ++++++++++++++++++++++++++++++++++++++
// +++ Buchungs-ID ausgeben          +++
// ++++++++++++++++++++++++++++++++++++++

//function getBookingId($id)
//{
//    return strtoupper(substr(sha1($id), 0, 10));
//}
//
//// ++++++++++++++++++++++++++++++++++++++
//// +++ Buchungs-ID-Codebild ausgeben  +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function getBookingIdCodePicture($id)
//{
//    $temp = MatukioHelperSettings::getSettings('frontend_userlistscode', 1); // $config->get('sem_p029',1);
//    if ($temp == 1) {
//        return "<img src=\"http://chart.apis.google.com/chart?cht=qr&amp;chs=100x100&amp;choe=UTF-8&amp;chld=H|4&amp;chl=" . urlencode(getBookingId($id)) . "\"><br /><code><b>" . getBookingId($id) . "</b></code>";
//    } else if ($temp == 2) {
//        return "<img src=\"" . MatukioHelperUtilsBasic::getComponentPath() . "matukio.code.php?code=" . getBookingId($id) . "\">";
//    }
//}
//
//// ++++++++++++++++++++++++++++++++++++++
//// +++ Basisverzeichnis ausgeben      +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function MatukioHelperUtilsBasic::getSitePath()
//{
//    $htxt = JURI::BASE();
//    return str_replace("/administrator", "", $htxt);
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Komponentenverzeichnis ausgeben ++
// ++++++++++++++++++++++++++++++++++++++

//function sem_f005()
//{
//    return MatukioHelperUtilsBasic::getSitePath() . "components/" . JRequest::getCmd('option') . "/";
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Bildverzeichnis 1 ausgeben     +++
// ++++++++++++++++++++++++++++++++++++++
//
//    function MatukioHelperUtilsBasic:getComponentImagePath
//    {
//        return MatukioHelperUtilsBasic::getComponentPath() . "images/";
//    }

// ++++++++++++++++++++++++++++++++++++++
// +++ Bildverzeichnis 2 ausgeben     +++
// ++++++++++++++++++++++++++++++++++++++

//function MatukioHelperUtilsBasic::getEventImagePath($art)
//{
//    $htxt = "";
//    if (MatukioHelperSettings::getSettings('image_path', "") != "" AND $art > 0) {
//        $htxt = trim(MatukioHelperSettings::getSettings('image_path', ""), "/") . "/";
//    }
//    return MatukioHelperUtilsBasic::getSitePath() . "images/stories/" . $htxt;
//}

//// ++++++++++++++++++++++++++++++++++++++++++++
//// +++ Editierbereich der Seminare ausgeben +++
//// ++++++++++++++++++++++++++++++++++++++++++++
//
//function sem_f008($row, $art)
//{
//    jimport('joomla.database.table');
//    jimport('joomla.html.pane');
//    $database = JFactory::getDBO();
//    $editor = JFactory::getEditor();
//    $catlist = sem_f010($row->catid);
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $reqfield = " <span class=\"sem_reqfield\">*</span>";
//
//    // Vorlage
//    $html = "";
//    if ($art == 1 OR $art == 2) {
//        $html = "<input type=\"hidden\" name=\"pattern\" value=\"\"><input type=\"hidden\" name=\"vorlage\" value=\"0\">";
//    }
//    if ($row->id == 0 AND ($art == 1 OR $art == 2)) {
//        $html = sem_f057($row->vorlage, $art);
//    }
//    $html .= "<tr><td width=\"100%\">";
//
//    $pane =& JPane::getInstance('sliders', array('allowAllClose' => true));
//    $html .= $pane->startPane('pane');
//
//    // ### Panel 1 ###
//
//    $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_BASIC_SETTINGS'), 'panel1');
//    $html .= "<table>";
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_SETTINGS_NEEDED'), 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";
//
//    // Vorlagenname und Besitzer
//    if ($art == 3) {
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TEMPLATE') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022(
//            "<input class=\"sem_inputbox\" type=\"text\" name=\"pattern\" size=\"50\" maxlength=\"100\"
//        value=\"" . $row->pattern . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_OWNER') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022(
//            sem_f009($row->publisher) . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//        $reqfield = "";
//    }
//
//    // ID der Veranstaltung
//    if ($row->id < 1) {
//        $htxt = JTEXT::_('COM_MATUKIO_ID_NOT_CREATED');
//        $htx2 = JTEXT::_('COM_MATUKIO_SHOULD_REGISTERED_USERS_RECEIVE_MAIL');
//        $htx3 = JTEXT::_('COM_MATUKIO_NEW_EVENT_PUBLISHED_INTERESTED_SEE_HOMEPAGE');
//        $htx4 = "";
//        $htx5 = " checked=\"checked\"";
//    } else {
//        $htxt = $row->id;
//        $htx2 = JTEXT::_('COM_MATUKIO_INFORM_PER_EMAIL');
//        $htx3 = JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED');
//        if ($row->cancelled == 0) {
//            $htx4 = "";
//            $htx5 = " checked=\"checked\"";
//            if ($art != 3) {
//                $htx4 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_ORGANISER_CANCELLED') . "'\"";
//                $htx5 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED') . "'\"" . $htx5;
//            }
//        } else {
//            $htx4 = " checked=\"checked\"";
//            $htx5 = "";
//            if ($art != 3) {
//                $htx4 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED') . "'\"" . $htx4;
//                $htx5 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_ORGANISER_HAS_REPUBLISHED_EVENT') . "'\"";
//            }
//        }
//    }
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_ID') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_AUTO_ID')), 'd', 'r', '20%', 'sem_edit');
//    $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//
//    // Kursnummer
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_NUMBER') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_UNIQUE_NUMBER')), 'd', 'r', '20%', 'sem_edit');
//    $html .= sem_f022("<input class=\"sem_inputbox\" type=\"text\" name=\"semnum\" size=\"10\" maxlength=\"100\" value=\"" . $row->semnum . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Abgesagt
//    $htxt = "<input type=\"radio\" name=\"cancel\" id=\"cancel\" value=\"1\" class=\"sem_inputbox\"" . $htx4 . " /><label for=\"cancel\">" . JTEXT::_('COM_MATUKIO_YES') . "</label> <input type=\"radio\" name=\"cancel\" id=\"cancel\" value=\"0\" class=\"sem_inputbox\"" . $htx5 . "/><label for=\"cancel\">" . JTEXT::_('COM_MATUKIO_NO') . "</label>";
//    $html .= "\n<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CANCELLED') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_CANCELLED_EVENT_NO_BOOKINGS')), 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "<input type=\"hidden\" name=\"cancelled\" value=\"" . $row->cancelled . "\"></tr>";
//
//    // Titel
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TITLE') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022("<input class=\"sem_inputbox\" type=\"text\" name=\"title\" size=\"50\" maxlength=\"250\" value=\"" . $row->title . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Kategorie
//    $htxt = $catlist[0];
//    if (MatukioHelperSettings::getSettings('event_image', 1) == 1) {
//        foreach ($catlist[1] as $el) {
//            $htxt .= "<input type=\"hidden\" id=\"im" . $el->id . "\" value=\"" . $el->image . "\">";
//        }
//    }
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CATEGORY') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_EVENT_ASSIGNED_CATEGORY')), 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    $radios = array();
//    $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_YES'));
//    $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_NO'));
//
//    // Veranstaltungsbeginn
//    $htxt = JHTML::_('calendar', JHtml::_('date',$row->begin, 'Y-m-d H:i:s'), '_begin_date', '_begin_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '22'));
//    $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist', $radios, 'showbegin', 'class="sem_inputbox"', 'value', 'text', $row->showbegin);
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BEGIN') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Veranstaltungsende
//    $htxt = JHTML::_('calendar', JHtml::_('date',$row->end, 'Y-m-d H:i:s'), '_end_date', '_end_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '22'));
//    $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist', $radios, 'showend', 'class="sem_inputbox"', 'value', 'text', $row->showend);
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_END') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Anmeldeschluss
//    $htxt = JHTML::_('calendar', JHtml::_('date',$row->booked, 'Y-m-d H:i:s'), '_booked_date', '_booked_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '22', 'filter' => 'USER_UTC'));
//    $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist', $radios, 'showbooked', 'class="sem_inputbox"', 'value', 'text', $row->showbooked);
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Kurzbeschreibung
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BRIEF_DESCRIPTION') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_BRIEF_DESCRIPTION_DESC')), 'd', 'r', '20%', 'sem_edit') . sem_f022("<textarea class=\"sem_inputbox\" cols=\"50\" rows=\"3\" name=\"shortdesc\" style=\"width:500px\" width=\"500\">" . $row->shortdesc . "</textarea>" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Veranstaltungsort
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CITY') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022("<textarea class=\"sem_inputbox\" cols=\"50\" rows=\"3\" name=\"place\" style=\"width:500px\" width=\"500\">" . $row->place . "</textarea>" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Veranstalter
//    if ($reglevel > 5 AND $art != 3) {
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_ORGANISER') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_ORGANISER_MANAGE_FRONTEND')), 'd', 'r', '20%', 'sem_edit') . sem_f022(sem_f009($row->publisher) . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    }
//
//    // Plätze
//    $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"maxpupil\" size=\"3\" maxlength=\"5\" value=\"" . $row->maxpupil . "\" /> - " . JTEXT::_('COM_MATUKIO_IF_FULLY_BOOKED') . ": ";
//    $radios = array();
//    $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_WAITLIST'));
//    $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_END_BOOKING'));
//    $radios[] = JHTML::_('select.option', 2, JTEXT::_('COM_MATUKIO_HIDE_EVENT'));
//    $htxt .= JHTML::_('select.genericlist', $radios, 'stopbooking', 'class="sem_inputbox" ', 'value', 'text', $row->stopbooking);
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // max. Buchung
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_MAX_BOOKABLE_PLACES') . ':' . sem_f055(JTEXT::_('COM_MATUKIO_CANNOT_BOOK_ONLINE')), 'd', 'r', '20%', 'sem_edit');
//    if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 2) > 0) {
//        $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"nrbooked\" size=\"3\" maxlength=\"3\" value=\"" . $row->nrbooked . "\" />";
//    } else {
//        $radios = array();
//        $radios[] = JHTML::_('select.option', 0, "0");
//        $radios[] = JHTML::_('select.option', 1, "1");
//        $htxt = JHTML::_('select.genericlist', $radios, 'nrbooked', 'class="sem_inputbox" ', 'value', 'text', $row->nrbooked);
//    }
//    $html .= sem_f022($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    $html .= "</table>";
//    $html .= $pane->endPanel();
//
//    // ### Panel 2 ###
//
//    $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_ADDITIONAL_SETTINGS'), 'panel2');
//    $html .= "<table>";
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_ADDITIONAL_SETTINGS'), 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";
//
//    // Beschreibung
//    $name = "editor1";
//    $htxt = $editor->display("description", $row->description, "500", "300", "50", "5");
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_DESCRIPTION') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022(JTEXT::_('COM_MATUKIO_USE_FOLLOWING_TAGS') . $htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Veranstaltungsbild
//    if (MatukioHelperSettings::getSettings('event_image', 1) == 1) {
//        jimport('joomla.filesystem.folder');
//        $htxt = "";
//        if (MatukioHelperSettings::getSettings('image_path', '') != "") {
//            $htxt = trim(MatukioHelperSettings::getSettings('image_path', ''), "/") . "/";
//        }
//        $htxt = JPATH_SITE . "/images/" . $htxt;
//        if (!is_dir($htxt)) {
//            mkdir($htxt, 0755);
//        }
//        $imageFiles = JFolder::files($htxt);
//        $images = array(JHTML::_('select.option', '', '- ' . JText::_('COM_MATUKIO_STANDARD_IMAGE') . ' -'));
//        foreach ($imageFiles as $file) {
//            if (eregi("gif|jpg|png", $file)) {
//                $images[] = JHTML::_('select.option', $file);
//            }
//        }
//        $imagelist = JHTML::_('select.genericlist', $images, 'image', 'class="sem_inputbox" size="1" ', 'value', 'text', $row->image);
//        $htxt = "<span style=\"position:absolute;display:none;border:3px solid #FF9900;background-color:#FFFFFF;\" id=\"1\"><img id=\"toolbild\"
//        src=\"images/stories/" . $row->image . "\" \></span><span style=\"position:absolute;display:none;border:3px solid #FF9900;background-color:#FFFFFF;\"
//        id=\"2\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2601.png\" \></span>";
//        $htxt .= $imagelist . "&nbsp;<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2116.png\" border=\"0\" onmouseover=\"showSemTip('1');\" onmouseout=\"hideSemTip();\" />";
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_IMAGE_FOR_OVERVIEW') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    }
//
//    // Google-Map
//    if (MatukioHelperSettings::getSettings('googlemap_apicode', '') != "") {
//        $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"gmaploc\" size=\"50\" maxlength=\"250\" value=\"" . $row->gmaploc . "\" /> ";
//        $actform = "FrontForm";
//        $gmaphref = JURI::BASE();
//        if (strstr($gmaphref, "/administrator")) {
//            $actform = "adminForm";
//        }
//        $htxt .= "<a href=\"\" title=\"" . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "\" class=\"modal\" onclick=\"href='" . MatukioHelperUtilsBasic::getComponentPath() . "/matukio.gmap.php?key=" . MatukioHelperSettings::getSettings('googlemap_apicode', '') .
//            "&amp;iw=" . MatukioHelperSettings::getSettings('googlemap_booble', 1) . "&amp;ziel=' + unescape(document." . $actform . ".gmaploc.value)
//        + '&amp;ort=' + unescape(document." . $actform . ".place.value.replace(/\\n/gi, '<br />'));\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\">" . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "</a>";
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_GMAPS_LOCATION') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    }
//
//    // Leitung
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TUTOR') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022("<input class=\"sem_inputbox\" type=\"text\" name=\"teacher\" size=\"50\" maxlength=\"250\" value=\"" . $row->teacher . "\" />", 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Zielgruppe
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022("<input class=\"sem_inputbox\" type=\"text\" name=\"target\" size=\"50\" maxlength=\"500\" value=\"" . $row->target . "\" />", 'd', 'l', '80%', 'sem_edit') . "</tr>";
//
//    // Gebuehr
//    $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . "&nbsp;<input class=\"sem_inputbox\" type=\"text\" name=\"fees\" size=\"8\" maxlength=\"10\" value=\"" . $row->fees . "\" />";
//    if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 2) > 0) {
//        $htxt .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
//    }
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_FEES') . ':', 'd', 'r', '20%', 'sem_edit') . sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    $html .= "</table>";
//    $html .= $pane->endPanel();
//
//    // ### Panel 3 ###
//
//    $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_GENERAL_INPUT_FIELDS'), 'panel3');
//    $html .= "<table>";
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_FILLED_IN_ONCE') . "<br />&nbsp;<br />" . JTEXT::_('COM_MATUKIO_FIELD_INPUT_SPECIFIED') . "<br />&nbsp;<br />" . JTEXT::_('COM_MATUKIO_FIELD_TIPS_SPECIFIED') . "<br />&nbsp;<br />", 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";
//
//    // Zusatzfelder
//    $zusfeld = sem_f017($row);
//    for ($i = 0; $i < count($zusfeld[0]); $i++) {
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_INPUT') . " " . ($i + 1) . ":", 'd', 'r', '20%', 'sem_edit');
//        $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"zusatz" . ($i + 1) . "\" size=\"50\" value=\"" . $zusfeld[0][$i] . "\" />";
//        $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//        $html .= "<tr>" . sem_f022("&nbsp;", 'd', 'r', '20%', 'sem_edit');
//        $htxt = JTEXT::_('COM_MATUKIO_FIELD_TIP') . ": <input class=\"sem_inputbox\" type=\"text\" name=\"zusatz" . ($i + 1) . "hint\" size=\"50\" maxlength=\"120\" value=\"" . $zusfeld[1][$i] . "\" />";
//        $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//        $html .= "<tr>" . sem_f022("&nbsp;", 'd', 'r', '20%', 'sem_edit');
//        $radios = array();
//        $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_YES'));
//        $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_NO'));
//        $htxt = str_replace("SEM_FNUM", $i + 1, JTEXT::_('COM_MATUKIO_DISPLAY_SEM_FNUM'));
//        $htxt = $htxt . " " . JHTML::_('select.radiolist', $radios, 'zusatz' . ($i + 1) . 'show', 'class="sem_inputbox" ', 'value', 'text', $zusfeld[2][$i]);
//        $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//    }
//    $html .= "</table>";
//    $html .= $pane->endPanel();
//
//    // ### Panel 5 ###
//    if (MatukioHelperSettings::getSettings('file_maxsize', 500) > 0) {
//        $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_FILES'), 'panel4');
//        $htxt = str_replace("SEM_FILESIZE", MatukioHelperSettings::getSettings('file_maxsize', 500), JTEXT::_('COM_MATUKIO_FILE_SIZE_UP_TO'));
//        $htxt = str_replace("SEM_FILETYPES", strtoupper(MatukioHelperSettings::getSettings('file_endings', 'txt pdf zip jpg')), $htxt);
//        $html .= "<table>";
//        $html .= "<tr>" . sem_f022($htxt, 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";
//        $datfeld = sem_f060($row);
//        $select = array();
//        $select[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_EVERYONE'));
//        $select[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_REGISTERED_USERS'));
//        $select[] = JHTML::_('select.option', 2, JTEXT::_('COM_MATUKIO_USERS_BOOKED_EVENT'));
//        $select[] = JHTML::_('select.option', 3, JTEXT::_('COM_MATUKIO_USERS_PAID_FOR_EVENT'));
//        for ($i = 0; $i < count($datfeld[0]); $i++) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_FILE') . " " . ($i + 1) . ":", 'd', 'r', '20%', 'sem_edit');
//            if ($datfeld[0][$i] != "") {
//                $htxt = "<b>" . $datfeld[0][$i] . "</b> - <input class=\"sem_inputbox\" type=\"checkbox\" name=\"deldatei" . ($i + 1) . "\" value=\"1\" onClick=\"if(this.checked==true) {datei" . ($i + 1) . ".disabled=true;} else {datei" . ($i + 1) . ".disabled=false;}\"> " . JTEXT::_('COM_MATUKIO_DELETE_FILE');
//                $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//                $html .= "<tr>" . sem_f022("&nbsp;", 'd', 'r', '20%', 'sem_edit');
//            }
//            $htxt = "<input class=\"sem_inputbox\" name=\"datei" . ($i + 1) . "\" type=\"file\">";
//            $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//            $html .= "<tr>" . sem_f022("&nbsp;", 'd', 'r', '20%', 'sem_edit');
//            $htxt = JTEXT::_('COM_MATUKIO_DESCRIPTION') . ": <input class=\"sem_inputbox\" type=\"text\" name=\"file" . ($i + 1) . "desc\" size=\"50\" maxlength=\"255\" value=\"" . $datfeld[1][$i] . "\" />";
//            $html .= sem_f022($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//            $html .= "<tr>" . sem_f022("&nbsp;", 'd', 'r', '20%', 'sem_edit');
//            $htxt = JHTML::_('select.genericlist', $select, 'file' . ($i + 1) . 'down', 'class="sem_inputbox" ', 'value', 'text', $datfeld[2][$i]);
//            $html .= sem_f022(JTEXT::_('COM_MATUKIO_WHO_MAY_DOWNLOAD') . " " . $htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
//        }
//        $html .= "</table>";
//        $html .= $pane->endPanel();
//    }
//
//    $html .= $pane->endPane();
//    $html .= "\n</td></tr><tr>" . sem_f022("&nbsp;* " . JTEXT::_('COM_MATUKIO_REQUIRED_FIELD'), 'd', 'r', '100%', 'sem_nav', 2);
//
//    // Benutzer informieren
//    //   if($art!=3) {
//    //     $html .= "</tr></td></tr>";
//    //     $radios = array();
//    //     $radios[] = JHTML::_('select.option',1,JTEXT::_('COM_MATUKIO_YES'));
//    //     $radios[] = JHTML::_('select.option',0,JTEXT::_('COM_MATUKIO_NO'));
//    //     $htx2 .= "<br />".JHTML::_('select.radiolist',$radios,'inform','class="sem_inputbox"','value','text',0);
//    //     $htx2 .= "<br />".JTEXT::_('COM_MATUKIO_MESSAGE_TEXT').": <input class=\"sem_inputbox\" type=\"text\" name=\"infotext\" id=\"infotext\" size=\"70\" value=\"".$htx3."\" />";
//    //     $html .= "\n<tr>".sem_f022($htx2,'d','c','100%','sem_nav',2);
//    //   }
//
//    return $html;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Veranstalterliste ausgeben     +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f009($pub)
//{
//    $publevel = MatukioHelperSettings::getSettings('frontend_createevents', 0); //SettingsHelper::getSettings('frontend_createevents', 0);
//    $database = JFactory::getDBO();
//    $where = array();
//    $where [] = "usertype<>'Registered'";
//    if ($publevel > 3) {
//        $where [] = "usertype<>'Author'";
//    } else if ($publevel > 4) {
//        $where [] = "usertype<>'Editor'";
//    } else if ($publevel > 5) {
//        $where [] = "usertype<>'Publisher'";
//    } else if ($publevel > 6) {
//        $where [] = "usertype<>'Manager'";
//    } else if ($publevel > 7) {
//        $where [] = "usertype<>'Administrator'";
//    }
//    $database->setQuery("SELECT id AS value, name AS text FROM #__users"
//            . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//            . "\nORDER BY name"
//    );
//    $benutzer = $database->loadObjectList();
//    return JHTML::_('select.genericlist', array_merge($benutzer), 'publisher', 'class="sem_inputbox" size="1"',
//        'value', 'text', $pub);
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Kategorienliste ausgeben     +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f010($catid)
//{
//    jimport('joomla.database.table');
//    $database = JFactory::getDBO();
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $accesslvl = 1;
//    if ($reglevel >= 6) {
//        $accesslvl = 3;
//    } else if ($reglevel >= 2) {
//        $accesslvl = 2;
//    }
//    $categories[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_CHOOSE_CATEGORY'));
//    //  $database->setQuery( "SELECT id AS value, title AS text, image AS image FROM #__categories". " WHERE extension='".JRequest::getCmd('option')."'" );
//    $database->setQuery("Select id AS value, title AS text FROM #__categories WHERE extension='com_matukio'");
//    $dats = $database->loadObjectList();
//
//    $categories = array_merge($categories, (array)$dats);
//    $clist = JHTML::_('select.genericlist', $categories, 'caid', 'class="sem_inputbox" size="1"', 'value', 'text', intval($catid));
//    $ilist = array();
//
//    foreach ((array)$dats as $el) {
//        $el->image = "";
//        $bild = "";
//        if ($el->image != "") {
//            $bild->id = $el->value;
//            $bild->image = $el->image;
//            $ilist[] = $bild;
//        }
//    }
//    return array($clist, $ilist);
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Benutzerliste ausgeben         +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f011($row)
//{
//    $database = JFactory::getDBO();
//    //  $database->setQuery( "SELECT a.*, cc.*, a.id AS sid FROM #__matukio_bookings AS a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$row->id' ORDER BY a.id");
//    $database->setQuery("SELECT userid AS id FROM #__matukio_bookings WHERE semid = '$row->id'");
//    $users = $database->loadObjectList();
//    if ($database->getErrorNum()) {
//        echo $database->stderr();
//        return false;
//    }
//    if ((count($users) >= $row->maxpupil) AND ($row->stopbooking > 0)) {
//        $blist = "";
//    } else {
//        $userout = array();
//        if (MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0) {
//            $userout[] = $row->publisher;
//        }
//        foreach ($users as $user) {
//            $userout[] = $user->id;
//        }
//        $where = "";
//        if (count($userout) > 0) {
//            $userout = implode(',', $userout);
//            $where = "\nWHERE id NOT IN ($userout)";
//        }
//        $database->setQuery("SELECT id AS value, name AS text FROM #__users"
//                . $where
//                . "\nORDER BY name"
//        );
//        $benutzer = $database->loadObjectList();
//        if (count($benutzer)) {
//            $benutzer = array_merge($benutzer);
//            $blist = JHTML::_('select.genericlist', $benutzer, 'uid', 'class="sem_inputbox" size="1"', 'value', 'text', '');
//        } else {
//            $blist = "";
//        }
//    }
//    return $blist;
//}

//// ++++++++++++++++++++++++++++++++++++++++++++++++
//// +++ Name und Beschreibung der Kategorie ausgeben
//// ++++++++++++++++++++++++++++++++++++++++++++++++
//
//function sem_f012($catid)
//{
//    $database = JFactory::getDBO();
//    $database->setQuery("Select * FROM #__categories WHERE extension='com_matukio' AND id = '$catid'");
//    $rows = $database->loadObjectList();
//    return array($rows[0]->title, $rows[0]->description);
//}

// +++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe des Prozentbalkens
// +++++++++++++++++++++++++++++++++++++++

function sem_f013($max, $frei, $art)
{
    if ($max == 0) {
        $max = 1;
    }
    $hoehe = 30;
    $hoehefrei = round($frei * $hoehe / $max);
    $hoehebelegt = $hoehe - $hoehefrei;
    $html = "<span class=\"sem_bar\">" . $max . "</span><br />";
    $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2100.png\" width=\"18\" height=\"1\"><br />";
    if ($hoehefrei > 0) {
        $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "212" . $art . ".png\" width=\"18\" height=\"" . $hoehefrei . "\"><br />";
    }
    if ($hoehebelegt > 0) {
        $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "211" . $art . ".png\" width=\"18\" height=\"" . $hoehebelegt . "\"><br />";
    }
    $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2100.png\" width=\"18\" height=\"1\"><br />";
    $html .= "<span class=\"sem_bar\">0</span>";
    return $html;
}

//// +++++++++++++++++++++++++++++++++++++++++++++++++++
//// +++ Anzeige der versteckten Variablen im Frontend +
//// +++++++++++++++++++++++++++++++++++++++++++++++++++
//
//function sem_f014($task, $catid, $search, $limit, $limitstart, $cid, $dateid, $uid)
//{
//    $html = "<input type=\"hidden\" name=\"task\" value=\"" . $task . "\" />";
//    $html .= "<input type=\"hidden\" name=\"limitstart\" value=\"" . $limitstart . "\" />";
//    $html .= "<input type=\"hidden\" name=\"cid\" value=\"" . $cid . "\" />";
//    if ($catid != "") {
//        $html .= "<input type=\"hidden\" name=\"catid\" value=\"" . $catid . "\" />";
//    }
//    if ($search != "") {
//        $html .= "<input type=\"hidden\" name=\"search\" value=\"" . $search . "\" />";
//    }
//    if ($limit != "") {
//        $html .= "<input type=\"hidden\" name=\"limit\" value=\"" . $limit . "\" />";
//    }
//    if ($uid != "") {
//        if ($uid == -1) {
//            $uid = "";
//        }
//        $html .= "<input type=\"hidden\" name=\"uid\" value=\"" . $uid . "\" />";
//    }
//    if ($dateid != "") {
//        $html .= "<input type=\"hidden\" name=\"dateid\" value=\"" . $dateid . "\" />";
//    }
//    return $html;
//}

// ++++++++++++++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe der Versteckten Variablen im Backend +
// ++++++++++++++++++++++++++++++++++++++++++++++++++

function sem_f015()
{
    $html = "<input type=\"hidden\" name=\"katid\" value=\"" . trim(JFactory::getApplication()->input->getInt('katid', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"ordid\" value=\"" . trim(JFactory::getApplication()->input->getInt('ordid', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"ricid\" value=\"" . trim(JFactory::getApplication()->input->getInt('ricid', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"einid\" value=\"" . trim(JFactory::getApplication()->input->getInt('einid', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"limit\" value=\"" . trim(JFactory::getApplication()->input->getInt('limit', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"limitstart\" value=\"" . trim(JFactory::getApplication()->input->getInt('limitstart', 0)) . "\">";
    $html .= "<input type=\"hidden\" name=\"search\" value=\"" . trim(strtolower(JFactory::getApplication()->input->get('search', '', 'string'))) . "\">";
    return $html;
}

// +++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe eines Prozentbalkens
// +++++++++++++++++++++++++++++++++++++++

function sem_f016($done)
{
    $max = 100;
    if ($done < 0) {
        $done = 0;
    }
    if ($done > $max) {
        $done = $max;
    }
    $displayValue = $done / $max * 100;
    $displayValue = number_format($displayValue, 0, '.', '');
    return "<span style=\"white-space: nowrap;\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "3000.png\" height=\"10\" width=\"" . $displayValue . "\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "3001.png\" height=\"10\" width=\"" . (100 - $displayValue) . "\"></span>";
}

//// ++++++++++++++++++++++++++++++++++
//// +++ Aray mit Zusatzfeldern erzeugen
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f017($row)
//{
//    $zusfeld = array();
//    $zusfeld[] = array($row->zusatz1, $row->zusatz2, $row->zusatz3, $row->zusatz4, $row->zusatz5, $row->zusatz6, $row->zusatz7, $row->zusatz8, $row->zusatz9, $row->zusatz10, $row->zusatz11, $row->zusatz12, $row->zusatz13, $row->zusatz14, $row->zusatz15, $row->zusatz16, $row->zusatz17, $row->zusatz18, $row->zusatz19, $row->zusatz20);
//    if (isset($row->zusatz1hint)) {
//        $zusfeld[] = array($row->zusatz1hint, $row->zusatz2hint, $row->zusatz3hint, $row->zusatz4hint, $row->zusatz5hint, $row->zusatz6hint, $row->zusatz7hint, $row->zusatz8hint, $row->zusatz9hint, $row->zusatz10hint, $row->zusatz11hint, $row->zusatz12hint, $row->zusatz13hint, $row->zusatz14hint, $row->zusatz15hint, $row->zusatz16hint, $row->zusatz17hint, $row->zusatz18hint, $row->zusatz19hint, $row->zusatz20hint);
//        $zusfeld[] = array($row->zusatz1show, $row->zusatz2show, $row->zusatz3show, $row->zusatz4show, $row->zusatz5show, $row->zusatz6show, $row->zusatz7show, $row->zusatz8show, $row->zusatz9show, $row->zusatz10show, $row->zusatz11show, $row->zusatz12show, $row->zusatz13show, $row->zusatz14show, $row->zusatz15show, $row->zusatz16show, $row->zusatz17show, $row->zusatz18show, $row->zusatz19show, $row->zusatz20show);
//    }
//    return $zusfeld;
//}

// ++++++++++++++++++++++++++++++++++
// +++ Text von HTML befreien
// ++++++++++++++++++++++++++++++++++

//function sem_f018($text)
//{
//    $text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
//    $text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text);
//    $text = preg_replace('/<!--.+?-->/', '', $text);
//    $text = preg_replace('/{.+?}/', '', $text);
//    $text = preg_replace('/&nbsp;/', ' ', $text);
//    $text = preg_replace('/&amp;/', ' ', $text);
//    $text = str_replace("\'", "'", $text);
//    $text = str_replace('\"', '"', $text);
//    $text = strip_tags($text);
//    return $text;
//}

//// ++++++++++++++++++++++++++++++++++
//// +++ Pathway erweitern
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f019($text, $link)
//{
//    $mainframe = JFactory::getApplication();
//    $pathway = $mainframe->getPathWay();
//    $pathway->addItem($text, $link);
//}

//// ++++++++++++++++++++++++++++++++++
//// +++ Berechne die gebuchten Plaetze
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f020($row)
//{
//    $database = JFactory::getDBO();
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $row->id . "'");
//    $temps = $database->loadObjectList();
//    $gebucht = 0;
//    $zertifiziert = 0;
//    $bezahlt = 0;
//    foreach ($temps as $el) {
//        $gebucht = $gebucht + $el->nrbooked;
//        $zertifiziert = $zertifiziert + $el->certificated;
//        $bezahlt = $bezahlt + $el->paid;
//    }
//    $zurueck->booked = $gebucht;
//    $zurueck->certificated = $zertifiziert;
//    $zurueck->paid = $bezahlt;
//    $zurueck->number = count($temps);
//    return $zurueck;
//}

//// ++++++++++++++++++++++++++++++++++
//// +++ ist Kurs noch buchbar
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f021($art, $row, $usrid)
//{
//    $database = JFactory::getDBO();
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' ORDER BY id");
//    $temps = $database->loadObjectList();
//    $gebucht = 0;
//    foreach ($temps as $el) {
//        $gebucht = $gebucht + $el->nrbooked;
//    }
//
//    if ($usrid < 0) {
//        $sid = $usrid * -1;
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$sid'");
//        $userid = 0;
//    } else {
//        if ($usrid == 0) {
//            $usrid = -1;
//        }
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' AND userid='$usrid'");
//    }
//    $temp = $database->loadObjectList();
//
//    $freieplaetze = $row->maxpupil - $gebucht;
//    if ($freieplaetze < 0) {
//        $freieplaetze = 0;
//    }
//    $buchbar = 3;
//    $buchgraf = 2;
//    $altbild = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    if ($neudatum > $row->booked) {
//        $buchbar = 1;
//        $buchgraf = 0;
//        $altbild = JTEXT::_('COM_MATUKIO_REGISTRATION_END');
//    } else if ($row->cancelled == 1 OR ($freieplaetze < 1 AND $row->stopbooking == 1) OR ($usrid == $row->publisher
//        AND MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0)
//    ) {
//        $buchbar = 1;
//        $buchgraf = 0;
//        $altbild = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
//    } else if ($freieplaetze < 1 AND ($row->stopbooking == 0 OR $row->stopbooking == 2)) {
//        $buchgraf = 1;
//        $altbild = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
//    }
//    if (count($temp) > 0) {
//        $buchbar = 2;
//        $buchgraf = 0;
//        $altbild = JTEXT::_('COM_MATUKIO_ALREADY_BOOKED');
//    }
//    if ($reglevel < 1) {
//        $buchbar = 0;
//    }
//    if ($art == 1) {
//        $buchgraf = 2;
//        $altbild = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
//        $gebucht = sem_f020($row);
//        if ($gebucht->booked > $row->maxpupil) {
//            if ($row->stopbooking == 0 OR $row->stopbooking == 2) {
//                $summe = 0;
//                for ($l = 0, $m = count($temps); $l < $m; $l++) {
//                    $summe = $summe + $temps[$l]->nrbooked;
//                    if ($temps[$l]->userid == $usrid) {
//                        break;
//                    }
//                }
//                if ($summe > $row->maxpupil) {
//                    $buchgraf = 1;
//                    $altbild = JTEXT::_('COM_MATUKIO_WAITLIST');
//                }
//            } else {
//                $buchgraf = 0;
//                $altbild = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
//            }
//        }
//        if ($row->cancelled == 1) {
//            $buchgraf = 0;
//            $altbild = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
//        }
//    }
//    if ($art == 2) {
//        $buchgraf = 2;
//        $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_NOT_STARTED_YET');
//        if ($neudatum > $row->end) {
//            $buchgraf = 0;
//            $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_EVDED');
//        } else if ($neudatum > $row->begin) {
//            $buchgraf = 1;
//            $altbild = JTEXT::_('COM_MATUKIO_EVENT_IS_RUNNING');
//        }
//    }
//    return array($buchbar, $altbild, $temp, $buchgraf, $freieplaetze);
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Tabellenzelle ausgeben
//// ++++++++++++++++++++++++++++++++++++++
//// sem_f022(text,art,align,width,class,colspan)
//
//function sem_f022()
//{
//    $args = func_get_args();
//    $html = "\n<t" . $args[1];
//    if (count($args) > 4) {
//        if ($args[4] != "") {
//            $html .= " class=\"" . $args[4] . "\"";
//        }
//    }
//    if (count($args) > 2) {
//        if ($args[2] != "") {
//            $html .= " style=\"text-align:";
//            switch ($args[2]) {
//                case "l":
//                    $html .= "left";
//                    break;
//                case "r":
//                    $html .= "right";
//                    break;
//                case "c":
//                    $html .= "center";
//                    break;
//            }
//            $html .= ";\"";
//        }
//    }
//    if (count($args) > 3) {
//        if ($args[3] != "") {
//            $html .= " width=\"" . $args[3] . "\"";
//        }
//    }
//    if (count($args) > 5) {
//        if ($args[5]) {
//            $html .= " colspan=\"" . $args[5] . "\"";
//        }
//    }
//    $html .= ">" . $args[0] . "</t" . $args[1] . ">";
//    return $html;
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Tabellenkopf ausgeben
// ++++++++++++++++++++++++++++++++++++++

//function sem_f023()
//{
//    $args = func_get_args();
//    if (is_numeric($args[0])) {
//        $html = "\n<table cellpadding=\"" . $args[0] . "\" cellspacing=\"0\" border=\"0\"";
//        if (count($args) == 2) {
//            $html .= " class=\"" . $args[1] . "\"";
//        }
//        $html .= " width=\"100%\">";
//    } else {
//        $html = "\n</table>";
//    }
//    return $html;
//}

//// +++++++++++++++++++++++++++++++++++++++
//// +++ Ausgabe einer Tabellenzeile     +++
//// +++++++++++++++++++++++++++++++++++++++
//
//function sem_f024($art, $var1, $var2, $werte, $klasse)
//{
//    $zurueck = "<tr";
//    if ($klasse <> "") {
//        $zurueck .= " class=\"" . $klasse . "\"";
//    }
//    $zurueck .= ">";
//
//    $n = count($werte);
//    for ($l = 0, $n; $l < $n; $l++) {
//        $format1 = "";
//        if (is_array($var1)) {
//            switch ($var1[$l]) {
//                case "c2":
//                    $format1 .= " colspan=\"2\"";
//                    break;
//                case "nw":
//                    $format1 .= " nowrap=\"nowrap\"";
//                    break;
//                case "l":
//                    $format1 .= " style=\"text-align:left;\"";
//                    break;
//                case "r":
//                    $format1 .= " style=\"text-align:right;\"";
//                    break;
//                case "c":
//                    $format1 .= " style=\"text-align:center;\"";
//                    break;
//            }
//        }
//        $format2 = "";
//        if (is_array($var2)) {
//            switch ($var2[$l]) {
//                case "c2":
//                    $format1 .= " colspan=\"2\"";
//                    break;
//                case "nw":
//                    $format1 .= " nowrap=\"nowrap\"";
//                    break;
//                case "l":
//                    $format1 .= " style=\"text-align:left;\"";
//                    break;
//                case "r":
//                    $format1 .= " style=\"text-align:right;\"";
//                    break;
//                case "c":
//                    $format1 .= " style=\"text-align:center;\"";
//                    break;
//            }
//        }
//        $zurueck .= "<" . $art . $format1 . $format2 . ">" . $werte[$l] . "</" . $art . ">";
//    }
//
//    $zurueck .= "</tr>";
//    return $zurueck;
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Fensterstatus loeschen
// ++++++++++++++++++++++++++++++++++++++

function sem_f025($status)
{
    return "onmouseover=\"window.status='" . $status . "';return true;\" onmouseout=\"window.status='';return true;\"";
}

// ++++++++++++++++++++++++++++++++++++++
// +++ Formularstart ausgeben
// ++++++++++++++++++++++++++++++++++++++

//function sem_f026($art)
//{
//    $htxt = "FrontForm";
//    if ($art == 2 OR $art == 4) {
//        $htxt = "adminForm";
//    }
//    $type = "";
//    if ($art > 2) {
//        $type = " enctype=\"multipart/form-data\"";
//    }
//    echo "<form action=\"\" method=\"post\" name=\"" . $htxt . "\" id=\"" . $htxt . "\"" . $type . ">";
//}

// ++++++++++++++++++++++++++++++++++
// +++ Ausgabe Javascript        FOR COMPATIBILITY REASONS, ONLY BACKEND!!111
// ++++++++++++++++++++++++++++++++++

function sem_f027($art)
{
    $my = JFactory::getuser();
    $html = "\n<script type=\"text/javascript\">";
    if ($art == 4 OR $art == 6 OR $art == 8) {
        $html .= "\nwmtt = null;";
        $html .= "\ndocument.onmousemove = semTip";
        $html .= "\nfunction semTip(e) {";
        $html .= "\nif (wmtt != null) {";
        $html .= "\nx = (document.all) ? window.event.x + wmtt.offsetParent.scrollLeft : e.pageX;";
        $html .= "\ny = (document.all) ? window.event.y + wmtt.offsetParent.scrollTop  : e.pageY;";
        $html .= "\nwmtt.style.left = (x + 20) + 'px';";
        $html .= "\nwmtt.style.top   = (y + 20) + 'px';";
        $html .= "\n}}";
        $html .= "\nfunction showSemTip(id) {";
        $html .= "\nif (document.getElementById(\"image\").value!='') {";
        $html .= "\ndocument.getElementById(\"toolbild\").src='" . MatukioHelperUtilsBasic::getEventImagePath(1) . "' + document.getElementById(\"image\").value;";
        $html .= "\n} else if (document.getElementById(\"caid\").value!='0') {";
        $html .= "\nimid = document.getElementById(\"caid\").value;";
        $html .= "\nif (isNaN(document.getElementById(\"im\" + imid))) {";
        $html .= "\ndocument.getElementById(\"toolbild\").src='" . MatukioHelperUtilsBasic::getEventImagePath(0) . "' + document.getElementById(\"im\" + imid).value;";
        $html .= "\n} else {";
        $html .= "\nid = 2;";
        $html .= "\n}";
        $html .= "\n} else {";
        $html .= "\nid = 2;";
        $html .= "\n}";
        $html .= "\nwmtt = document.getElementById(id);";
        $html .= "\nwmtt.style.display = 'block'";
        $html .= "\n}";
        $html .= "\nfunction hideSemTip() {";
        $html .= "\nwmtt.style.display = 'none';";
        $html .= "\n}";
    }
    if ($art != 2.3) {
        if (round($art) == 2) {
            $html .= "\nfunction chmail(s) {";
            $html .= "\n var a = false;";
            $html .= "\n var res = false;";
            $html .= "\n if(typeof(RegExp) == 'function') {";
            $html .= "\n  var b = new RegExp('abc');";
            $html .= "\n  if(b.test('abc') == true) a = true;";
            $html .= "\n }";
            $html .= "\n if(a == true) {";
            $html .= "\n  reg = new RegExp('^([a-zA-Z0-9\-\.\_]+)'+ '(\@)([a-zA-Z0-9\-\.]+)'+ '(\.)([a-zA-Z]{2,4})$');";
            $html .= "\n  res = (reg.test(s));";
            $html .= "\n } else {";
            $html .= "\n  res = (s.search('@') >= 1 && s.lastIndexOf('.') > s.search('@') && s.lastIndexOf('.') >= s.length-5);";
            $html .= "\n }";
            $html .= "\n return(res);";
            $html .= "\n}";
        }
    }
    if ($art < 5) {
        $html .= "\nfunction los(stask,scid,suid) {";
        $html .= "\n var form = document.FrontForm;";
        $html .= "\n form.task.value = stask;";
        $html .= "\n if(scid != '') form.cid.value = scid;";
        $html .= "\n if(suid != '') form.uid.value = suid;";
        $html .= "\n form.submit();";
        $html .= "\n}";
        $html .= "\nfunction semauf(stask,scid,suid) {";
        $html .= "\n var form = document.FrontForm;";
    }
    if (round($art) > 2 AND $art < 5) {
        $html .= "\n if (stask == \"10\") {";
        $html .= "\n  if (form.title.value == \"\") {";
        $html .= "\n   alert(unescape( \"" . JTEXT::_('COM_MATUKIO_FILL_IN_TITLE_OF_EVENT') . "\" ));";
        $html .= "\n  } else if (form.semnum.value == \"\") {";
        $html .= "\n   alert(unescape( \"" . JTEXT::_('COM_MATUKIO_PLEASE_ENTER_NUMBER') . "\" ));";
        $html .= "\n  } else if (form.caid.selectedIndex == 0) {";
        $html .= "\n   alert(unescape( \"" . JTEXT::_('COM_MATUKIO_PLEASE_SELECT_CAT') . "\" ));";
        $html .= "\n  } else if (form.shortdesc.value == \"\") {";
        $html .= "\n   alert(unescape( \"" . JTEXT::_('COM_MATUKIO_FILL_IN_BRIEF_DESCRIPTION_OF_EVENT') . "\" ));";
        $html .= "\n  } else if (form.place.value == \"\") {";
        $html .= "\n   alert(unescape( \"" . JTEXT::_('COM_MATUKIO_FILL_IN_PLACE_OF_EVENT') . "\" ));";
        $html .= "\n  } else {";
        $html .= "\n   if (form.vorlage.type == \"select-one\") {";
        $html .= "\n    form.id.value = \"\";";
        $html .= "\n   };";
        $html .= "\n   form.pattern.value = \"\";";
        $html .= "\n   los(stask,scid,suid);";
        $html .= "\n  };";
        $html .= "\n } else if (stask == \"11\") {";
        $html .= "\n  if (confirm(unescape(\"" . JTEXT::_('COM_MATUKIO_REMOVE_EVENT_FROM_OFFERINGS') . "\"))) {";
        $html .= "\n   los(stask,scid,suid);";
        $html .= "\n  }";
        $html .= "\n } else";
    }
    if ($art < 5) {
        if ($art != 2.3) {
            $html .= "\n if (stask == \"6\" || stask == \"7\") {";
            if (MatukioHelperSettings::getSettings('booking_stornoconfirmation', 1) > 0) {
                $html .= "\n  if (confirm(unescape(\"" . JTEXT::_('COM_MATUKIO_CANCELL_BOOKING') . "\"))) {";
            }
            $html .= "\n  los(stask, scid, suid);";
            if (MatukioHelperSettings::getSettings('booking_stornoconfirmation', 1) > 0) {
                $html .= "\n  }";
            }
            if (round($art) == 2) {
                $html .= "\n } else if (stask == \"5\" || stask==\"26\" || stask==\"29\") {";
                $html .= "\n  var abbruch = false;";
                $html .= "\n  var meldung = unescape(\"" . JTEXT::_('COM_MATUKIO_HAVENT_SUBMITTED_DATA') . "\");";
                $html .= "\n  for (var z=1; z<21; z++) {";
                $html .= "\n   ename = \"zusatz\" + z;";
                $html .= "\n   oname = \"opt\" + z;";
                $html .= "\n   if (document.FrontForm.elements[ename].type == \"text\" || document.FrontForm.elements[ename].type == \"textarea\") {";
                $html .= "\n    document.FrontForm.elements[ename].className=\"sem_inputbox\";";
                $html .= "\n    if (document.FrontForm.elements[ename].value == \"\" && document.getElementById(oname).value == 1) {";
                $html .= "\n     document.FrontForm.elements[ename].className=\"sem_alertbox\";";
                $html .= "\n     abbruch = true;";
                $html .= "\n    } else if (document.FrontForm.elements[ename].value != \"\") {";
                $html .= "\n     if (document.FrontForm.elements[ename].id.match(/email/)) {";
                $html .= "\n      if (chmail(document.FrontForm.elements[ename].value) == false) {";
                $html .= "\n       document.FrontForm.elements[ename].className=\"sem_alertbox\";";
                $html .= "\n       meldung = meldung.concat(unescape(\"\\n" . JTEXT::_('COM_MATUKIO_ENTER_VALID_EMAIL_ADDRESS') . "\"));";
                $html .= "\n       abbruch = true;";
                $html .= "\n      }";
                $html .= "\n     }";
                $html .= "\n    }";
                $html .= "\n   }";
                $html .= "\n   if (document.FrontForm.elements[ename].type == \"select-one\") {";
                $html .= "\n    document.FrontForm.elements[ename].className=\"sem_inputbox\";";
                $html .= "\n    if (document.FrontForm.elements[ename].options.selectedIndex == \"0\" && document.getElementById(oname).value == 1) {";
                $html .= "\n    document.FrontForm.elements[ename].className=\"sem_alertbox\";";
                $html .= "\n     abbruch = true;";
                $html .= "\n    }";
                $html .= "\n   }";
                $html .= "\n  }";
                if (MatukioHelperSettings::getSettings('booking_unregistered', 1) > 0 AND ($my->id == 0 OR $art == 2.2)) {
                    $html .= "\n  document.FrontForm.name.className=\"sem_inputbox\";";
                    $html .= "\n  if (document.FrontForm.name.value == '') {";
                    $html .= "\n   document.FrontForm.name.className=\"sem_alertbox\";";
                    $html .= "\n   abbruch = true;";
                    $html .= "\n  }";
                    $html .= "\n  document.FrontForm.email.className=\"sem_inputbox\";";
                    $html .= "\n  if (document.FrontForm.email.value == '') {";
                    $html .= "\n   document.FrontForm.email.className=\"sem_alertbox\";";
                    $html .= "\n   abbruch = true;";
                    $html .= "\n  }";
                    $html .= "\n  if (document.FrontForm.email.value != '' && chmail(document.FrontForm.email.value) == false) {";
                    $html .= "\n   document.FrontForm.email.className=\"sem_alertbox\";";
                    $html .= "\n   meldung = meldung.concat(unescape(\"\\n" . JTEXT::_('COM_MATUKIO_ENTER_VALID_EMAIL_ADDRESS') . "\"));";
                    $html .= "\n   abbruch = true;";
                    $html .= "\n  }";
                }
                $html .= "\n  if (abbruch == true) {";
                $html .= "\n   alert(meldung);";
                if (MatukioHelperSettings::getSettings('agb_text', '') != "") {
                    $html .= "\n  } else if(document.FrontForm.veragb.checked == 0) {";
                    $html .= "\n    document.FrontForm.veragb.className=\"sem_alertbox\";";
                    $html .= "\n    alert(unescape( \"" . JTEXT::_('COM_MATUKIO_AGREE_TO_TERMS_AND_CERVICES') . "\" ));";
                    $html .= "\n  } else if(document.FrontForm.veragb.checked == 1) {";
                    $html .= "\n    document.FrontForm.veragb.className=\"sem_inputbox\";";
                } else {
                    $html .= "\n  } else {";
                }
                if (MatukioHelperSettings::getSettings('booking_confirmation', 0) > 0) {
                    $html .= "\n   if (confirm(unescape(\"" . JTEXT::_('COM_MATUKIO_CONFIRM_INFORMATION') . "\"))) {";
                }
                $html .= "\n   los(stask,scid,suid);";
                if (MatukioHelperSettings::getSettings('booking_confirmation', 0) > 0) {
                    $html .= "\n   }";
                }
                $html .= "\n  }";
            }
            $html .= "\n } else {";
        }
        $html .= "\n  los(stask,scid,suid);";
        if ($art != 2.3) {
            $html .= "\n }";
        }
        $html .= "\n}";
    }
    $html .= "\n</script>";
    return $html;
}

//// ++++++++++++++++++++++++++++++++++
//// +++ Copyright ausgeben         +++
//// ++++++++++++++++++++++++++++++++++
//
//function getCopyright()
//{
//    if (MatukioHelperSettings::getSettings('frontend_showfooter', 1) == 1) {
//        $html = "<div id=\"copyright_box\">Powered by
//           <a href=\"http://compojoom.com\" target=\"_new\">Matukio - Joomla Event Manager</a></div>";
//    } else {
//        $html = "<div id=\"copyright_box\" style=\"display: none\">Powered by
//           <a href=\"http://compojoom.com\" target=\"_new\">Matukio - Joomla Event Manager</a></div>";
//    }
//    return $html;
//}
//
//// ++++++++++++++++++++++++++++++++++
//// +++ Farbbeschreibung anzeigen  +++
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f029($green, $yellow, $red)
//{
//    $html = sem_f023(4) . "<tr>";
//    if ($green != "") {
//        $html .= sem_f022("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2502.png\" border=\"0\" align=\"absmiddle\"> " . $green, 'd', 'c', '', 'sem_nav');
//    }
//    if ($yellow != "") {
//        $html .= sem_f022("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2501.png\" border=\"0\" align=\"absmiddle\"> " . $yellow, 'd', 'c', '', 'sem_nav');
//    }
//    if ($red != "") {
//        $html .= sem_f022("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2500.png\" border=\"0\" align=\"absmiddle\"> " . $red, 'd', 'c', '', 'sem_nav');
//    }
//    $html .= "</tr>" . sem_f023('e');
//    return $html;
//}

// ++++++++++++++++++++++++++++++++++
// +++ CSS ausgeben               +++
// ++++++++++++++++++++++++++++++++++

//function sem_f030()
//{
//    return "<link rel=\"stylesheet\" href=\"" . MatukioHelperUtilsBasic::getComponentPath() . "css/matukio." . MatukioHelperSettings::getSettings('template_color', 0) . ".css\" type=\"text/css\" />";
//}

// ++++++++++++++++++++++++++++++++++
// +++ HTML-Kopf ausgeben         +++
// ++++++++++++++++++++++++++++++++++

//function sem_f031()
//{
//    $lang = JFactory::getLanguage();
//    $html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
//    $html .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"" . $lang->getName() . "\" lang=\"" . $lang->getName() . "\" >";
//    $html .= "\n<head>";
//    $html .= "\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />";
//    $html .= sem_f030();
//    $html .= "\n</head>";
//    return $html;
//}


//// ++++++++++++++++++++++++++++++++++
//// +++ Kopf-Bereiche ausgeben     +++
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f032($tab)
//{
//    $confusers = JComponentHelper::getParams('com_users');
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    switch ($tab) {
//        case "2":
//            $tabnum = array(0, 1, 0);
//            break;
//        case "3":
//            $tabnum = array(0, 0, 1);
//            break;
//        default:
//            $tabnum = array(1, 0, 0);
//            break;
//    }
//    $html = "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";
//    if ($reglevel > 1) {
//        $html .= "\n<td class=\"sem_tab" . $tabnum[0] . "\">";
//        $html .= "\n<a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='0';semauf(0,'','');\" title=\"" . JTEXT::_('COM_MATUKIO_EVENTS') . "\" " . sem_f025(JTEXT::_('COM_MATUKIO_EVENTS')) . "><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2600.png\" border=\"0\" align=\"absmiddle\"> " . JTEXT::_('COM_MATUKIO_EVENTS') . "</a>";
//        $html .= "</td>";
//        $html .= "\n<td class=\"sem_tab" . $tabnum[1] . "\">";
//        $html .= "\n<a class=\"sem_tab\" title=\"" . JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . "\" href=\"javascript:document.FrontForm.limitstart.value='0';semauf(1,'','');\" " . sem_f025(JTEXT::_('COM_MATUKIO_MY_BOOKINGS')) . "><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2700.png\" border=\"0\" align=\"absmiddle\"> " . JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . "</a>";
//        $html .= "\n</td>";
//        if ($reglevel >= MatukioHelperSettings::getSettings('frontend_createevents', 0)) {
//            $html .= "\n<td class=\"sem_tab" . $tabnum[2] . "\">";
//            $html .= "\n<a class=\"sem_tab\" title=\"" . JTEXT::_('COM_MATUKIO_MY_OFFERS') . "\" href=\"javascript:document.FrontForm.limitstart.value='0';semauf(2,'','');\" " . sem_f025(JTEXT::_('COM_MATUKIO_MY_OFFERS')) . "><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2800.png\" border=\"0\" align=\"absmiddle\"> " . JTEXT::_('COM_MATUKIO_MY_OFFERS') . "</a>";
//            $html .= "\n</td>";
//        }
//    } else if (MatukioHelperSettings::getSettings('frontend_unregisteredshowlogin', 1) > 0) {
//
//        // Joomla > 1.6 com_users !
//        $baseuserurl = "index.php?option=com_user";
//        if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
//            $baseuserurl = "index.php?option=com_users";
//        }
//
//        $registrationurl = "&amp;view=register";
//        if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
//            $registrationurl = "&amp;view=registration";
//        }
//
//        $html .= "<td class=\"sem_notableft\">";
//        $html .= "<input type=\"text\" name=\"semusername\" value=\"" . JTEXT::_('USERNAME') . "\" class=\"sem_inputbox\" style=\"background-image:url("
//            . MatukioHelperUtilsBasic::getComponentImagePath() . "0004.png);background-repeat:no-repeat;background-position:2px;padding-left:18px;width:100px;vertical-align:middle;\" onFocus=\"if(this.value=='"
//            . JTEXT::_('USERNAME') . "') this.value='';\" onBlur=\"if(this.value=='') {this.value='"
//            . JTEXT::_('USERNAME') . "';form.semlogin.disabled=true;}\" onKeyup=\"if(this.value!='') form.semlogin.disabled=false;\"> ";
//        $html .= "<input type=\"password\" name=\"sempassword\" value=\"" . JTEXT::_('PASSWORD') . "\" class=\"sem_inputbox\" style=\"background-image:url("
//            . MatukioHelperUtilsBasic::getComponentImagePath() . "0005.png);background-repeat:no-repeat;background-position:2px;padding-left:18px;width:100px;vertical-align:middle;\" onFocus=\"if(this.value=='"
//            . JTEXT::_('PASSWORD') . "') this.value='';\" onBlur=\"if(this.value=='') this.value='" . JTEXT::_('PASSWORD') . "';\"> ";
//
//        $html .= "<button class=\"button\" type=\"submit\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
//            . JTEXT::_('LOGIN') . "\" id=\"semlogin\" disabled><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0007.png\" style=\"vertical-align:middle;\"></button>";
//        $html .= "&nbsp;&nbsp;&nbsp;";
//        $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
//            . JTEXT::_('COM_MATUKIO_FORGOTTEN_USERNAME') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . "&amp;view=remind'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0008.png\" style=\"vertical-align:middle;\"></button>";
//        $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
//            . JTEXT::_('COM_MATUKIO_CHANGE_PASSWORD') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . "&amp;view=reset'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0009.png\" style=\"vertical-align:middle;\"></button>";
//        if ($confusers->get('allowUserRegistration', 0) > 0) {
//            $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
//                . JTEXT::_('COM_MATUKIO_REGISTER') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . $registrationurl . "'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0006.png\" style=\"vertical-align:middle;\"></button>";
//        }
//        $html .= "</td>";
//    }
//    $html .= "<td class=\"sem_notab\">&nbsp;";
//    $knopfunten = "";
//    if ($reglevel > 1 and MatukioHelperSettings::getSettings('frontend_unregisteredshowlogin', 1) > 0) {
//        $html .= JHTML::_('link', "javascript:semauf(32,'','')", JHTML::_('image', MatukioHelperUtilsBasic::getComponentImagePath() . '3232.png', null, array('border' => '0', 'align' => 'absmiddle')), array('title' => JTEXT::_('COM_MATUKIO_LOGOUT'))) . "&nbsp;&nbsp;";
//        $knopfunten .= "<button class=\"button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"semauf(32,'','');\">" . JHTML::_('image', MatukioHelperUtilsBasic::getComponentImagePath() . '3216.png', null, array('border' => '0', 'align' => 'absmiddle')) . "&nbsp;" . JTEXT::_('COM_MATUKIO_LOGOUT') . "</button>";
//    }
//    echo $html;
//    return $knopfunten;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Ende des Kopfbereichs ausgeben +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f033()
//{
//    echo "</td></tr>" . sem_f023('e') . sem_f023(4) . "<tr><td class=\"sem_anzeige\">";
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ E-Mail-Fenster ausgeben
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f034($dir, $cid, $art)
//{
//
//    $html = "";
//    $href = MatukioHelperUtilsBasic::getSitePath() . "index.php?tmpl=component&s=" . sem_f036() . "&option=" . JRequest::getCmd('option') . "&cid=" . $cid . "&task=";
//    $x = 500;
//    $y = 350;
//    $htxt = "<a class=\"modal\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\" href=\"" . $href;
//    if ($art == 1 AND MatukioHelperUtilsBasic::getUserLevel() > 1 AND MatukioHelperSettings::getSettings('sendmail_contact', 1) > 0) {
//        $html = $htxt . "19\" title=\"" . JTEXT::_('COM_MATUKIO_CONTACT') . "\"><img src=\"" . $dir . "1732.png\" border=\"0\" align=\"absmiddle\"></a>";
//    } else if ($art == 2 AND MatukioHelperUtilsBasic::getUserLevel() > 1 AND MatukioHelperSettings::getSettings('sendmail_contact', 1) > 0) {
//        $html = $htxt . "19\"><button class=\"button\" type=\"button\"><img src=\"" . $dir . "1716.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_CONTACT') . "</button></a>";
//    } else if ($art == 3 AND MatukioHelperUtilsBasic::getUserLevel() > 2) {
//        $html = $htxt . "30\" title=\"" . JTEXT::_('COM_MATUKIO_CONTACT') . "\"><img src=\"" . $dir . "1732.png\" border=\"0\" align=\"absmiddle\"></a>";
//    } else if ($art == 4 AND MatukioHelperUtilsBasic::getUserLevel() > 2) {
//        $html = $htxt . "30\"><button class=\"button\" type=\"button\"><img src=\"" . $dir . "1716.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_CONTACT') . "</button></a>";
//    }
//    return $html;
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Bewertungsfenster ausgeben
// ++++++++++++++++++++++++++++++++++++++

//function sem_f035($dir, $cid, $imgid)
//{
//    if (MatukioHelperUtilsBasic::getUserLevel() > 1) {
//        $image = "240" . $imgid;
//        $titel = JTEXT::_('COM_MATUKIO_YOUR_RATING');
//        $href = JURI::ROOT() . "index.php?tmpl=component&s=" . sem_f036() . "&option=" . JRequest::getCmd('option') . "&cid=" . $cid . "&task=20";
//        $x = 500;
//        $y = 280;
//        return "<a title=\"" . $titel . "\" class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\"><img id=\"graduate" . $cid . "\" src=\"" . $dir . $image . ".png\" border=\"0\" align=\"absmiddle\"></a>";
//    }
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ zufaellige Zeichen ausgeben
// ++++++++++++++++++++++++++++++++++++++

//function sem_f036()
//{
//    $zufall = "";
//    for ($i = 0; $i <= 200; $i++) {
//        $gkl = rand(1, 3);
//        if ($gkl == 1) {
//            $zufall .= chr(rand(97, 121));
//        } else if ($gkl == 0) {
//            $zufall .= chr(rand(65, 90));
//        } else {
//            $zufall .= rand(0, 9);
//        }
//    }
//    return $zufall;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Druckfenster im Frontend ausgeben
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f037($art, $cid, $uid, $knopf)
//{
//
//    //  if(MatukioHelperUtilsBasic::getUserLevel() > 1) {
//    $dateid = trim(JFactory::getApplication()->input->getInt('dateid', 1));
//    $catid = trim(JFactory::getApplication()->input->getInt('catid', 0));
//    $search = trim(strtolower(JFactory::getApplication()->input->get('search', '')));
//    $limit = trim(JFactory::getApplication()->input->get('limit', MatukioHelperSettings::getSettings('event_showanzahl', 10)));
//    $limitstart = trim(JFactory::getApplication()->input->get('limitstart', 0));
//    if ($knopf == "") {
//        $image = "1932";
//    } else {
//        $image = "1916";
//    }
//    $titel = JTEXT::_('COM_MATUKIO_PRINT');
//    $href = JURI::ROOT() . "index.php?tmpl=component&s=" . sem_f036() . "&option=" . JRequest::getCmd('option')
//           . "&amp;dateid=" . $dateid . "&amp;catid=" . $catid . "&amp;search=" . $search . "&amp;limit=" . $limit
//     . "&amp;limitstart=" . $limitstart . "&amp;cid=" . $cid . "&amp;uid=" . $uid . "&amp;OIO=";
//    $x = 500;
//    $y = 350;
//    switch ($art) {
//        case 1:
//// Zertifikat
//            $image = "2900";
//            $titel = JTEXT::_('COM_MATUKIO_PRINT_CERTIFICATE');
//            $href .= "764576O987985&amp;task=16";
//            break;
//        case 2:
//// Kursuebersicht
//            $href .= "65O9805443904&amp;task=15";
//            break;
//        case 3:
//// gebuchte Kurse
//            $href .= "6530387504345&amp;task=15";
//            break;
//        case 4:
//// Kursangebot
//            $href .= "653O875032490&amp;task=15";
//            break;
//        case 5:
//// Teilnehmerliste1
//            $href .= "3728763872762&amp;task=17";
//            if ($knopf == "") {
//                $image = "2032";
//            } else {
//                $image = "2016";
//            }
//            break;
//        case 6:
//// Buchungsbestaetigung
//            $href .= "1495735268456&amp;task=printbook";
//            break;
//        case 7:
//// Teilnehmerliste2
//            $href .= "4525487566184&task=18";
//            break;
//    }
//    if (($art > 1 && MatukioHelperSettings::getSettings('frontend_userprintlists', 1) > 0 OR ($art == 1 &&
//        MatukioHelperSettings::getSettings('frontend_userprintcertificate', 0) > 0 &&
//        MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0))
//    ) {
//        if ($knopf == "") {
//            return "<a title=\"" . $titel . "\" class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" align=\"absmiddle\"></a>";
//        } else {
//            return "<a class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\"><button class=\"button\" style=\"cursor:pointer;\" type=\"button\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" align=\"absmiddle\">&nbsp;" . $titel . "</button></a>";
//        }
//    } else if ($art == 1 AND MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
//        return "\n<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2900.png\" border=\"0\" align=\"absmiddle\">";
//        //     } else {
//        //       return "&nbsp;";
//    }
//    //  }
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Druckfenster im Backend ausgeben
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f038($art, $cid)
//{
//    $katid = trim(JFactory::getApplication()->input->get('katid', 0));
//    $ordid = trim(JFactory::getApplication()->input->get('ordid', 0));
//    $ricid = trim(JFactory::getApplication()->input->get('ricid', 0));
//    $einid = trim(JFactory::getApplication()->input->get('einid', 0));
//    $search = trim(strtolower(JFactory::getApplication()->input->get('search', '')));
//    $limit = trim(JFactory::getApplication()->input->get('limit', 5));
//    $limitstart = trim(JFactory::getApplication()->input->get('limitstart', 0));
//    $uid = trim(JFactory::getApplication()->input->get('uid', 0));
//
//    $zufall = MatukioHelperUtilsBasic::getRandomChar();
//    $href = "index.php?tmpl=component&s=" . $zufall . "&option=com_matukio&katid=" . $katid . "&ordid=" . $ordid . "&ricid=" . $ricid . "&einid=" . $einid . "&search=" . $search . "&limit=" . $limit . "&limitstart=" . $limitstart . "&uid=" . $uid . "&task=";
//    $x = 550;
//    $y = 300;
//    $image = "1932";
//    $title = JTEXT::_('COM_MATUKIO_PRINT');
//    switch ($art) {
//        case 1:
//            $href .= "36";
//            break;
//        case 2:
//            $href .= "34&cid=" . $cid;
//            $image = "1932";
//            break;
//        case 3:
//            $href .= "35&cid=" . $cid;
//            $image = "2900";
//            $title = JTEXT::_('COM_MATUKIO_PRINT_CERTIFICATE');
//            break;
//        case 4:
//            $href .= "33&cid=" . $cid;
//            $image = "2032";
//            break;
//        case 5:
//            $href = "index.php?tmpl=component&s=" . $zufall . "&option=com_matukio&task=32&cid=" . $cid;
//            $image = "1632";
//            $title = JTEXT::_('COM_MATUKIO_DOWNLOAD_CSV_FILE');
//            break;
//    }
//    if ($art != 5) {
//        $html = "<a title=\"" . $title . "\" class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\">";
//    } else {
//        $html = "<a title=\"" . $title . "\" href=\"" . $href . "\">";
//    }
//    $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" valign=\"absmiddle\" alt=\"" . $title . "\"></a>";
//    return $html;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Seitennavigation bereinigen    +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f039($total, $limit, $limitstart)
//{
//    $pagenav = array();
//    $navi = "";
//    $pageone = 1;
//    $seiten = 1;
//    $kurse = "";
//    if ($limit > 0) {
//        $pageone = $limitstart / $limit + 1;
//        $seiten = ceil($total / $limit);
//        if ($pageone > 1) {
//            $navi .= "<a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='0';document.FrontForm.submit();\">" . JTEXT::_('START') . "</a>";
//            $navi .= " - <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='" . ($limitstart - $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('PREV') . "</a>";
//        } else {
//            $navi .= JTEXT::_('START');
//            $navi .= " - " . JTEXT::_('PREV');
//        }
//        $start = 0;
//        $ende = $seiten;
//        $navi .= " -";
//        if ($seiten > 5) {
//            if ($pageone > 3) {
//                $navi .= " ...";
//                if ($seiten - 2 >= $pageone) {
//                    $start = $pageone - 3;
//                    $ende = $pageone + 2;
//                } else {
//                    $start = $seiten - 5;
//                    $ende = $seiten;
//                }
//            } else {
//                $ende = 5;
//            }
//        }
//        for ($i = $start; $i < $ende; $i++) {
//            if ($i * $limit != $limitstart) {
//                $navi .= " <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='" . ($i * $limit) . "';document.FrontForm.submit();\">" . ($i + 1) . "</a>";
//            } else {
//                $navi .= " " . ($i + 1);
//                $kurs1 = (($i * $limit) + 1);
//                $kurs2 = (($i + 1) * $limit);
//                if ($kurs2 > $total) {
//                    $kurs2 = $total;
//                }
//                if ($kurs1 == $kurs2) {
//                    $kurse = $kurs2 . "/" . $total;
//                } else {
//                    $kurse = $kurs1 . "-" . $kurs2 . "/" . $total;
//                }
//            }
//        }
//        if ($seiten > 5) {
//            if ($pageone + 2 < $seiten) {
//                $navi .= " ...";
//            }
//        }
//        $navi .= " -";
//        if ($pageone < $seiten) {
//            $navi .= " <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='" . ($limitstart + $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('NEXT') . "</a>";
//            $navi .= " - <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='" . ($seiten * $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('END') . "</a>";
//        } else {
//            $navi .= " " . JTEXT::_('NEXT');
//            $navi .= " - " . JTEXT::_('END');
//        }
//    }
//    $seite = JTEXT::_('PAGE') . "&nbsp;" . $pageone . "/" . ($seiten);
//    return "\n" . sem_f023(4) . "<tr>" . sem_f022($seite, 'd', 'l', '', 'sem_nav') . sem_f022($navi, 'd', 'c', '', 'sem_nav') . sem_f022($kurse, 'd', 'r', '', 'sem_nav') . "</tr>" . sem_f023('e');
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Limitbox fuer Seitennavigation +++
// ++++++++++++++++++++++++++++++++++++++

function sem_f040($art, $limit)
{
    $limits = array();
    $htxt = "FrontForm";
    if ($art == 2) {
        $htxt = "adminForm";
    }
    $limits[] = JHTML::_('select.option', '3');
    for ($i = 5; $i <= 30; $i += 5) {
        $limits[] = JHTML::_('select.option', "$i");
    }
    $limits[] = JHTML::_('select.option', '50');
    $limits[] = JHTML::_('select.option', '100');
    $limits[] = JHTML::_('select.option', '0', JText::_('all'));
    return JHTML::_('select.genericlist', $limits, 'limit', 'class="sem_inputbox" size="1" onchange="document.' . $htxt . '.limitstart.value=0;document.' . $htxt . '.submit()"', 'value', 'text', $limit);
}

// +++++++++++++++++++++++++++++++++++++++
// +++ Anzeige der Ueberschrift          +
// +++++++++++++++++++++++++++++++++++++++

//function sem_f041($temp1, $temp2)
//{
//    $html = "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
//    $html .= "\n<tr><td class=\"sem_cat_title\">" . $temp1 . "</td></tr>";
//    if ($temp2 != "") {
//        $html .= "\n<tr><td class=\"sem_cat_desc\">" . $temp2 . "</td></tr>";
//    }
//    $html .= "\n</table>";
//    echo $html;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Benutzerlevel festlegen        +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function MatukioHelperUtilsBasic::getUserLevel()
//{
//    $my = JFactory::getuser();
//
//    // Zugriffslevel festlegen
//    $utype = strtolower($my->usertype);
//
//    // > Joomla 1.5
//    if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
//        $utype = MatukioHelperUtilsBasic::getUserTypeID($my);
//    }
//
//    if (MatukioHelperUtilsBasic::getJoomlaVersion() == '1.5') {
//        switch ($utype) {
//            case "registered":
//                $reglevel = 2;
//                break;
//            case "author":
//                $reglevel = 3;
//                break;
//            case "editor":
//                $reglevel = 4;
//                break;
//            case "publisher":
//                $reglevel = 5;
//                break;
//            case "manager":
//                $reglevel = 6;
//                break;
//            case "administrator":
//                $reglevel = 7;
//                break;
//            case "super administrator":
//                $reglevel = 8;
//                break;
//            eventlist:
//                $reglevel = 0;
//                if (MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1) {
//                    $reglevel = 1;
//                }
//                break;
//        }
//    } else {
//        $reglevel = $utype;
//
//        if ($utype == -1) {
//            $reglevel = 0;
//            if (MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1) {
//                $reglevel = 1;
//            }
//        }
//    }
//    return $reglevel;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Auf Benutzerlevel testen       +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function MatukioHelperUtilsBasic::checkUserLevel($temp)
//{
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    if ($reglevel < $temp) {
//        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
//        exit;
//    }
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Schuetze den HTML-Text         +++
// ++++++++++++++++++++++++++++++++++++++

// function semSchutz() {
//   return "<div style=\"position:fixed; top:0; left:0; width:100%; height:100%; z-Index:10000; \"><img src=\"".MatukioHelperUtilsBasic::getComponentImagePath()."blind.gif\" width=\"100%\" height=\"100%\" style=\"width:100%; height:100%;\"></div>";
// }

// ++++++++++++++++++++++++++++++++++++++
// +++ Waehrung formatieren           +++
// ++++++++++++++++++++++++++++++++++++++

//function MatukioHelperUtilsBasic::getFormatedCurrency($betrag)
//{
//    return $betrag; // TODO FIX THIS
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ FREIE FUNKTION                 +++
// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f045()
//{
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Aktuelles Datum ausgeben       +++
// ++++++++++++++++++++++++++++++++++++++
//
//function MatukioHelperUtilsDate::getCurrentDate()
//{
//
//    $app = JFactory::getApplication();
//    $offset = $app->getCfg('offset');
//    $date = JFactory::getDate();
//    $date->setOffset($offset);
//    return $date->toformat();
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ FREIE FUNKTION                 +++
// ++++++++++++++++++++++++++++++++++++++

//function sem_f047()
//{
//}

// +++++++++++++++++++++++++++++
// +++ CSV-Datei senden      +++    Moved to csv.php
// +++++++++++++++++++++++++++++

// TODO Remove ... just for admin here

function sem_f048()
{
    $database = JFactory::getDBO();

    $cid = trim(JFactory::getApplication()->input->getInt('cid', ''));
    $kurs = new mosSeminar($database);
    $kurs->load($cid);
    $database->setQuery("SELECT a.*, cc.*, a.id AS sid, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$kurs->id' ORDER BY a.id");
    $rows = $database->loadObjectList();
    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    $csvdata = "\"#\",\"" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . "\",\"" . JTEXT::_('COM_MATUKIO_NAME') . "\",\"" . JTEXT::_('COM_MATUKIO_EMAIL') . "\",\"" . JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING') . "\",\"" . JTEXT::_('COM_MATUKIO_TIME_OF_BOOKING') . "\",\"" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . "\",\"" . JTEXT::_('COM_MATUKIO_STATUS');
    if ($kurs->fees > 0) {
        $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_PAID');
    }
    if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
        $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_CERTIFICATES');
    }
    if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
        $csvdata .= "\",\"" . JTEXT::_('COM_MATUKIO_RATING') . "\",\"" . JTEXT::_('COM_MATUKIO_COMMENT');
    }
    $zusatz1 = sem_f017($kurs);
    foreach ($zusatz1[0] AS $el) {
        if ($el != "") {
            $el = explode("|", $el);
            $csvdata .= "\",\"" . str_replace("\"", "'", $el[0]);
        }
    }
    $csvdata .= "\"\r\n";

    $summe = 0;
    $i = 0;
    foreach ($rows AS $row) {
        if ($row->userid == 0) {
            $row->name = $row->aname;
            $row->email = $row->aemail;
        }
        $i++;
        $summe = $summe + $row->nrbooked;
        $temp9 = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
        if ($summe > $kurs->maxpupil) {
            if ($kurs->stopbooking < 1) {
                $temp9 = JTEXT::_('COM_MATUKIO_WAITLIST');
            } else {
                $temp9 = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
            }
        }
        $temp6 = JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
        $temp7 = JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('time_format', 'H:i'));
        $temp8 = $i;
        $csvdata .= "\"" . $temp8 . "\",\"" . getBookingId($row->sid) . "\",\"" . str_replace("\"", "'", $row->name) . "\",\"" . $row->email . "\",\"" . $temp6 . "\",\"" . $temp7 . "\",\"" . $row->nrbooked . "\",\"" . $temp9;
        if ($kurs->fees > 0) {
            $temp7 = JTEXT::_('COM_MATUKIO_NO');
            if ($row->paid == 1) {
                $temp7 = JTEXT::_('COM_MATUKIO_YES');
            }
            $csvdata .= "\",\"" . $temp7;
        }
        if (MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
            $temp7 = JTEXT::_('COM_MATUKIO_NO');
            if ($row->certificated == 1) {
                $temp7 = JTEXT::_('COM_MATUKIO_YES');
            }
            $csvdata .= "\",\"" . $temp7;
        }
        if (MatukioHelperSettings::getSettings('frontend_ratingsystem', 0) > 0) {
            $csvdata .= "\",\"" . $row->grade . "\",\"" . str_replace("\"", "'", $row->comment);
        }
        $zusatz2 = sem_f017($row);
        for ($l = 0, $m = count($zusatz2[0]); $l < $m; $l++) {
            if ($zusatz1[0][$l] != "") {
                $csvdata .= "\",\"" . str_replace("\"", "'", $zusatz2[0][$l]);
            }
        }
        $csvdata .= "\"\r\n";
    }
    $konvert = MatukioHelperSettings::getSettings('csv_export_charset', 'ISO-8859-15');
    $csvdata = iconv("UTF-8", $konvert, $csvdata);

    header("content-type: application/csv-tab-delimited-table; charset=" . $konvert);
    header("content-length: " . strlen($csvdata));
    header("content-disposition: attachment; filename=\"$kurs->title.csv\"");
    header('Pragma: no-cache');
    echo $csvdata;
    exit;
}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Email-Koerper ausgeben         +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f049($row, $buchung, $user)
//{
//
//    $gebucht = sem_f020($row);
//    $gebucht = $gebucht->booked;
//    $freieplaetze = $row->maxpupil - $gebucht;
//    if ($freieplaetze < 0) {
//        $freieplaetze = 0;
//    }
//    $body = "<p>\n<table cellpadding=\"2\" border=\"0\" width=\"100%\">";
//    $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_NAME') . "</b>: </td><td>" . $user->name . "</td></tr>";
//    $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_EMAIL') . "</b>: </td><td>" . $user->email . "</td></tr>";
//    if (count($buchung) > 0) {
//        $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . "</b>: </td><td>" . getBookingId($buchung->id) . "</td></tr>";
//        $body .= "\n<tr><td colspan=\"2\"><hr></td></tr>";
//        $body .= "\n<tr><td colspan=\"2\"><b>" . JTEXT::_('COM_MATUKIO_ADDITIONAL_INFO') . "</b></td></tr>";
//        $zusfeld = sem_f017($row);
//        $zusbuch = sem_f017($buchung);
//        for ($i = 0; $i < count($zusfeld[0]); $i++) {
//            if ($zusfeld[0][$i] != "") {
//                $zusart = explode("|", $zusfeld[0][$i]);
//                $body .= "\n<tr><td>" . $zusart[0] . ": </td><td>" . $zusbuch[0][$i] . "</td></tr>";
//            }
//        }
//        if ($row->nrbooked > 1) {
//            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ": </td><td>" . $buchung->nrbooked . "</td></tr>";
//        }
//    }
//    $body .= "\n<tr><td colspan=\"2\"><hr></td></tr>";
//    $body .= "\n<tr><td colspan=\"2\"><b>" . $row->title . "</b></td></tr>";
//    $body .= "\n<tr><td colspan=\"2\">" . $row->shortdesc . "</td></tr>";
//    if ($row->semnum != "") {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_NUMBER') . ": </td><td>" . $row->semnum . "</td></tr>";
//    }
//    if ($row->showbegin > 0) {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BEGIN') . ": </td><td>" . JHTML::_('date', $row->begin,
//            MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
//    }
//    if ($row->showend > 0) {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_END') . ": </td><td>" . JHTML::_('date', $row->end,
//            MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
//    }
//    if ($row->showbooked > 0) {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ": </td><td>" . JHTML::_('date', $row->booked,
//            MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
//    }
//    if ($row->teacher != "") {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_TUTOR') . ": </td><td>" . $row->teacher . "</td></tr>";
//    }
//    if ($row->target != "") {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ": </td><td>" . $row->target . "</td></tr>";
//    }
//    $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CITY') . ": </td><td>" . $row->place . "</td></tr>";
//    if (MatukioHelperSettings::getSettings('event_showinfoline', 1) > 0) {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . ": </td><td>" . $row->maxpupil . "</td></tr>";
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKINGS') . ": </td><td>" . $gebucht . "</td></tr>";
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKABLE') . ": </td><td>" . $freieplaetze . "</td></tr>";
//    }
//    if ($row->fees > 0) {
//        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_FEES') . ": </td><td>" . MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . $row->fees;
//        if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
//            $body .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
//        }
//        $body .= "</td></tr>";
//    }
//    if ($row->description != "") {
//        $body .= "\n<tr><td colspan=\"2\">" . sem_f066($row->description) . "</td></tr>";
//    }
//    $body .= "</table><p>";
//    $htxt = str_replace('SEM_HOMEPAGE', "<a href=\"" . JURI::root() . "\">" . JURI::root() . "</a>", JTEXT::_('COM_MATUKIO_FOR_MORE_INFO_VISIT'));
//    $body .= $htxt . "</body>";
//    return $body;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Bestaetigungs-Emails versenden +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f050($cid, $uid, $art)
//{
//    jimport('joomla.mail.helper');
//    $mainframe = JFactory::getApplication();
//
//    if (MatukioHelperSettings::getSettings('sendmail_teilnehmer', 1) > 0 OR MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0) {
//        $database = JFactory::getDBO();
//        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//        $rows = $database->loadObjectList();
//        $row = &$rows[0];
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$uid'");
//        $rows = $database->loadObjectList();
//        if ($rows[0]->userid == 0) {
//            $user->name = $rows[0]->name;
//            $user->email = $rows[0]->email;
//        } else {
//            $user = JFactory::getuser($rows[0]->userid);
//        }
//        $publisher = JFactory::getuser($row->publisher);
//        $body1 = "<p><span style=\"font-size:10pt;\">" . JTEXT::_('COM_MATUKIO_PLEASE_DONT_ANSWER_THIS_EMAIL') . "</span><p>";
//        $body2 = $body1;
//        $gebucht = sem_f020($row);
//        $gebucht = $gebucht->booked;
//        switch ($art) {
//            case 1:
//                if ($gebucht > $row->maxpupil) {
//                    if ($row->stopbooking = 0) {
//                        $body1 .= JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
//                    } else {
//                        $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_BOOKED_EVENT_FOR_YOU') . " " . JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
//                    }
//                } else {
//                    $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_BOOKED_EVENT_FOR_YOU');
//                }
//                $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_MADE_FOLLOWING_RESERVATION');
//                break;
//            case 2:
//                $body1 .= JTEXT::_('COM_MATUKIO_YOU_HAVE_CANCELLED');
//                $body2 .= JTEXT::_('COM_MATUKIO_BOOKING_FOR_EVENT_CANCELLED');
//                break;
//            case 3:
//                $body1 .= JTEXT::_('COM_MATUKIO_BOOKING_CANCELED');
//                $body2 .= JTEXT::_('COM_MATUKIO_THE_ADMIN_CANCELED_THE_BOOKING_OF_FOLLOWING');
//                break;
//            case 4:
//                $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_DELETED_THE_FOLLOWING_EVENT');
//                $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_DELETED_EVENT');
//                break;
//            case 5:
//                $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_PUBLISHED_EVENT_YOUR_BOOKING_IS_VALID');
//                $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_PUBLISHED_EVENT_THE_BOOKING_OF_PARTICIPANTS_IS_VALID');
//                break;
//            case 6:
//                $body1 .= JTEXT::_('COM_MATUKIO_THE_ADMIN_CERTIFIED_YOU');
//                $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_HAS_CERTIFICATED_FOLLOWING_PARTICIPANT');
//                if (MatukioHelperSettings::getSettings('frontend_userprintcertificate', 0) > 0) {
//                    $body1 .= " " . JTEXT::_('COM_MATUKIO_YOU_CAN_PRINT_YOUR_CERTIFICATE');
//                }
//                break;
//            case 7:
//                $body1 .= JTEXT::_('COM_MATUKIO_CERTIFICAT_REVOKED');
//                $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_HAS_WITHDRAWN_CERTIFICATE_FOR_FOLLOWNG_PARITICIPANTS');
//                break;
//            case 8:
//                if ($gebucht > $row->maxpupil) {
//                    if ($row->stopbooking = 0) {
//                        $body1 .= JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
//                    } else {
//                        $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_REGISTERED_YOU') . " " . JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
//                    }
//                } else {
//                    $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_REGISTERED_YOU');
//                }
//                $body2 .= JTEXT::_('COM_MATUKIO_YOU_HAVE_REGISTRED_PARTICIPANT_FOR');
//                break;
//            case 9:
//                $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_HAS_REPUBLISHED_EVENT');
//                $body2 .= JTEXT::_('COM_MATUKIO_THE_BOOKING_OF_THE_PARTICIPANT_IS_VALID_AGAIN');
//                break;
//            case 10:
//                $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_CANCELLED');
//                $body2 .= JTEXT::_('COM_MATUKIO_BOOKING_NO_LONGER_VALID');
//                break;
//        }
//        $abody = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
//        $sender = $mainframe->getCfg('fromname');
//        $from = $mainframe->getCfg('mailfrom');
//        $htxt = "";
//        if ($row->semnum != "") {
//            $htxt = " " . $row->semnum;
//        }
//        $subject = JTEXT::_('COM_MATUKIO_EVENT') . $htxt . ": " . $row->title;
//        $subject = JMailHelper::cleanSubject($subject);
//        if (MatukioHelperSettings::getSettings('sendmail_teilnehmer', 1) > 0 OR $art < 11) {
//            $replyname = $publisher->name;
//            $replyto = $publisher->email;
//            $email = $user->email;
//            $body = $abody . $body1 . sem_f049($row, $rows[0], $user);
//            var_dump($email);
//            JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
//        }
//        if (MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0 AND $art < 11) {
//            $replyname = $user->name;
//            $replyto = $user->email;
//            $email = $publisher->email;
//            $body = $abody . $body2 . sem_f049($row, $rows[0], $user);
//            JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
//        }
//    }
//}


// +++++++++++++++++++++++++++++++++++++++++++++++
// +++ Ausdruck des Zertifikats                +++
// +++++++++++++++++++++++++++++++++++++++++++++++

function sem_f051($cid)
{
    $database = JFactory::getDBO();
    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$cid'");
    $rows = $database->loadObjectList();
    $booking = &$rows[0];
    $database->setQuery("SELECT * FROM #__matukio WHERE id='$booking->semid'");
    $rows = $database->loadObjectList();
    $row = &$rows[0];
    if ($booking->userid == 0) {
        $user->name = $booking->name;
        $user->email = $booking->email;
    } else {
        $user = JFactory::getuser($booking->userid);
    }
    $html = "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";

    if (MatukioHelperSettings::getSettings('certificate_htmlcode', '') != "") {
        $html .= MatukioHelperSettings::getSettings('certificate_htmlcode', '');
    } else {
        $html .= JTEXT::_('SEM_0056');
    }
    $html .= "</body></html>";
    echo sem_f054($html, $row, $user);
    exit;
}

// ++++++++++++++++++++++++++++++++++++++
// +++ Ausdruck der Benutzerliste     +++
// ++++++++++++++++++++++++++++++++++++++

function sem_f052($art)
{
//    $database = JFactory::getDBO();
//
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    $cid = trim(JFactory::getApplication()->input->getInt('cid', ''));
//    $kurs = new mosSeminar($database);
//    $kurs->load($cid);
//    $database->setQuery("SELECT a.*, cc.*, a.id AS sid, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$kurs->id' ORDER BY a.id");
//    $rows = $database->loadObjectList();
//
//    $html = "";
//    if ($art > 2) {
//        $html .= sem_f031();
//        $art -= 2;
//    }
//
//    $html .= "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";
//    $html .= "\n<br /><center><span class=\"sem_list_title\">" . JTEXT::_('COM_MATUKIO_LIST_PARTICIPANTS') . "</span></center><br />";
//    $gebucht = sem_f020($kurs);
//    $gebucht = $gebucht->booked;
//    $freieplaetze = $kurs->maxpupil - $gebucht;
//    if ($freieplaetze < 0) {
//        $freieplaetze = 0;
//    }
//    $html .= "\n" . sem_f023(2);
//
//    // Kursnummer
//    if ($kurs->semnum != "") {
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_NUMBER') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($kurs->semnum, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//    }
//
//    // Titel
//    $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TITLE') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($kurs->title, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//
//    // Seminarleiter
//    if ($kurs->teacher != "") {
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TUTOR') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($kurs->teacher, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//    }
//
//    // Beginn
//    if ($kurs->showbegin > 0) {
//        $htxt = JHTML::_('date', $kurs->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
//        if ($kurs->cancelled > 0) {
//            $htxt = JTEXT::_('COM_MATUKIO_CANCELLED') . " (<del>" . $htxt . "</del>)";
//        }
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BEGIN') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//    }
//
//    // Ende
//    if ($kurs->showend > 0) {
//        $htxt = JHTML::_('date', $kurs->end, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
//        if ($kurs->cancelled > 0) {
//            $htxt = JTEXT::_('COM_MATUKIO_CANCELLED') . " (<del>" . $htxt . "</del>)";
//        }
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_END') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//    }
//
//    // Gebuehr
//    if ($kurs->fees > 0) {
//        $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . $kurs->fees;
//        if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
//            $htxt .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
//        }
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_FEES') . ':', 'd', 'l', '5%', 'sem_list_blank') . sem_f022($htxt, 'd', 'l', '95%', 'sem_list_blank') . "</tr>";
//    }
//
//    $html .= "\n" . sem_f023('e');
//    if ($art == 1) {
//        $html .= "\n<br />" . sem_f023(2, 'sem_list');
//        $html .= "\n<tr>" . sem_f022('#', 'h', 'c', '10px', 'sem_list_head') . sem_f022(JTEXT::_('COM_MATUKIO_BOOKING_ID'), 'h', 'l', '40px', 'sem_list_head') . sem_f022(JTEXT::_('COM_MATUKIO_NAME'), 'h', 'l', '', 'sem_list_head') . sem_f022(JTEXT::_('COM_MATUKIO_SIGN'), 'h', 'l', '', 'sem_list_head') . "</tr>";
//        $i = 1;
//        foreach ($rows AS $row) {
//            if ($row->userid == 0) {
//                $row->name = $row->aname;
//                $row->email = $row->aemail;
//            }
//            $html .= "\n<tr>" . sem_f022($i . '.<br />&nbsp;', 'd', 'r', '10px', 'sem_list_row') . sem_f022(getBookingId($row->sid), 'd', 'l', '40px', 'sem_list_row') . sem_f022($row->name, 'd', 'l', '', 'sem_list_row') . sem_f022('&nbsp;', 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $i++;
//            for ($j = 1, $n = $row->nrbooked; $j < $n; $j++) {
//                $html .= "\n<tr>" . sem_f022($i . '<br />&nbsp;', 'd', 'r', '10px', 'sem_list_row') . sem_f022(getBookingId($row->sid), 'd', 'l', '40px', 'sem_list_row') . sem_f022('&nbsp;', 'd', 'l', '', 'sem_list_row') . sem_f022('&nbsp;', 'd', 'l', '', 'sem_list_row') . "</tr>";
//                $i++;
//            }
//        }
//        $html .= "\n" . sem_f023('e');
//    } else {
//        $i = 1;
//        foreach ($rows AS $row) {
//            if ($row->userid == 0) {
//                $row->name = $row->aname;
//                $row->email = $row->aemail;
//            }
//            $htxt = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
//            if ($i >= $kurs->maxpupil) {
//                if ($kurs->stopbooking < 1) {
//                    $htxt = JTEXT::_('COM_MATUKIO_WAITLIST');
//                } else {
//                    $htxt = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
//                }
//            }
//            if ($kurs->cancelled > 0) {
//                $htxt = JTEXT::_('COM_MATUKIO_CANCELLED');
//            }
//            $html .= "\n<br />" . sem_f023(2, 'sem_list');
//            $html .= "\n<tr>" . sem_f022($i . '.', 'd', 'r', '', 'sem_list_head') . sem_f022(JTEXT::_('COM_MATUKIO_NAME') . ":", 'd', 'l', '', 'sem_list_head') . sem_f022($row->name, 'd', 'l', '', 'sem_list_head') . "</tr>";
//            $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_EMAIL') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($row->email, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022(getBookingId($row->sid), 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022(JHTML::_('date', $row->bookingdate, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_STATUS') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            if ($kurs->nrbooked > 1 AND MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
//                $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($row->nrbooked, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            }
//            if ($kurs->fees > 0) {
//                $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . number_format((str_replace(",", ".", $kurs->fees) * $row->nrbooked), 2, ",", "");
//                if ($kurs->nrbooked > 1) {
//                    $htxt .= " (" . MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . number_format(str_replace(",", ".", $kurs->fees), 2, ",", "") . " " . JTEXT::_('COM_MATUKIO_PRO_PERSON') . ")";
//                }
//                $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_FEES') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";
//                $htxt = JTEXT::_('COM_MATUKIO_NO');
//                if ($row->paid == 1) {
//                    $htxt = JTEXT::_('COM_MATUKIO_YES');
//                }
//                $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . JTEXT::_('COM_MATUKIO_PAID') . ":</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            }
//            $zusfeld = sem_f017($kurs);
//            $zuserg = sem_f017($row);
//            for ($z = 0; $z < count($zusfeld[0]); $z++) {
//                if ($zusfeld[0][$z] != "") {
//                    $zusart = explode("|", $zusfeld[0][$z]);
//                    $html .= "\n<tr>" . sem_f022('&nbsp;', 'd', 'r', '', 'sem_list_row') . sem_f022("<b>" . $zusart[0] . "</b>", 'd', 'l', '', 'sem_list_row') . sem_f022($zuserg[0][$z], 'd', 'l', '', 'sem_list_row') . "</tr>";
//                }
//            }
//            $html .= "\n<tr>" . sem_f022(getBookingIdCodePicture($row->sid), 'd', 'c', '', 'sem_list_row', 3) . "</tr></table>";
//            $i++;
//        }
//    }
//    $html .= "<br />" . getCopyright();
//    $html .= "</body></html>";
//    echo $html;
//    exit;
}

// ++++++++++++++++++++++++++++++++++++++++
// +++ Code fuer Copyright ueberpruefen +++
// ++++++++++++++++++++++++++++++++++++++++

function sem_f053()
{

    return false;
}

//// ++++++++++++++++++++++++++++++++++++++++
//// +++ Konstanten in Text austauschen   +++
//// ++++++++++++++++++++++++++++++++++++++++
//
//function sem_f054($html, $row, $user)
//{
//
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//
//    $html = str_replace('SEM_IMAGEDIR', MatukioHelperUtilsBasic::getComponentImagePath(), $html);
//
//    $html = str_replace('SEM_BEGIN_EXPR', JTEXT::_('COM_MATUKIO_BEGIN'), $html);
//    $html = str_replace('SEM_END_EXPR', JTEXT::_('COM_MATUKIO_END'), $html);
//    $html = str_replace('SEM_LOCATION_EXPR', JTEXT::_('COM_MATUKIO_CITY'), $html);
//    $html = str_replace('SEM_TUTOR_EXPR', JTEXT::_('COM_MATUKIO_TUTOR'), $html);
//    $html = str_replace('SEM_DATE_EXPR', JTEXT::_('COM_MATUKIO_DATE'), $html);
//    $html = str_replace('SEM_TIME_EXPR', JTEXT::_('COM_MATUKIO_TIME'), $html);
//
//    $html = str_replace('SEM_COURSE', $row->title, $html);
//    $html = str_replace('SEM_TITLE', $row->title, $html);
//    $html = str_replace('SEM_COURSENUMBER', $row->semnum, $html);
//    $html = str_replace('SEM_NUMBER', $row->semnum, $html);
//    $html = str_replace('SEM_ID', $row->id, $html);
//    $html = str_replace('SEM_LOCATION', $row->place, $html);
//    $html = str_replace('SEM_TEACHER', $row->teacher, $html);
//    $html = str_replace('SEM_TUTOR', $row->teacher, $html);
//
//    $html = str_replace('SEM_BEGIN', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_BEGIN_OVERVIEW', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_BEGIN_DETAIL', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_BEGIN_LIST', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_BEGIN_DATE', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
//    $html = str_replace('SEM_BEGIN_TIME', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
//    $html = str_replace('SEM_END', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_END_OVERVIEW', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_END_DETAIL', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_END_LIST', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_END_DATE', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
//    $html = str_replace('SEM_END_TIME', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
//    $html = str_replace('SEM_TODAY', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
//    $html = str_replace('SEM_NOW', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
//    $html = str_replace('SEM_NOW_OVERVIEW', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_NOW_DETAIL', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_NOW_LIST', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
//    $html = str_replace('SEM_NOW_DATE', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
//    $html = str_replace('SEM_NOW_TIME', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
//
//    $html = str_replace('SEM_NAME', $user->name, $html);
//    $html = str_replace('SEM_EMAIL', $user->email, $html);
//
//    return $html;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Tooltip erzeugen               +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f055($text)
//{
//    $html = "";
//    if ($text != "") {
//        $text = explode("|", $text);
//        if (count($text) > 1) {
//            $hinttext = $text[0] . "::" . $text[1];
//        } else {
//            $hinttext = JTEXT::_('COM_MATUKIO_FIELD_TIP') . "::" . $text[0];
//        }
//        $html = " <span class=\"editlinktip hasTip\" title=\"" . $hinttext . "\" style=\"text-decoration: none;cursor: help;\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0012.png\" border=\"0\" style=\"vertical-align: absmiddle;\"/></span>";
//    }
//    return $html;
//}

// +++++++++++++++++++++++++++++++++++++++++++++++
// +++ Ausdruck der Kurslisten                 +++
// +++++++++++++++++++++++++++++++++++++++++++++++

function sem_f056()
{

//    $args = func_get_args();
//    $rows = $args[0];
//    $status = $args[1];
//    $html = "";
//    if (count($args) > 2) {
//        $headertext = $args[2];
//    } else {
//        $headertext = JTEXT::_('COM_MATUKIO_EVENTS');
//        $html .= sem_f031();
//    }
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    $html .= "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";
//    $html .= "\n<br /><center><span class=\"sem_list_title\">" . $headertext . "</span><br /><span class=\"sem_list_date\">" . JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')) . "</span></center><br />";
//    $k = 0;
//    for ($i = 0, $n = count($rows); $i < $n; $i++) {
//        $row = $rows[$i];
//        $gebucht = sem_f020($row);
//        $gebucht = $gebucht->booked;
//        $freieplaetze = $row->maxpupil - $gebucht;
//        if ($freieplaetze < 0) {
//            $freieplaetze = 0;
//        }
//        $html .= sem_f023(4, "sem_list");
//        $html .= "<tr>" . sem_f022($row->title, 'd', 'c', '100%', 'sem_list_head', 2) . "</tr>";
//        $html .= "<tr>" . sem_f022($row->shortdesc, 'd', 'l', '100%', 'sem_list_row', 2) . "</tr>";
//        if ($row->semnum != "") {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_NUMBER') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($row->semnum, 'd', 'l', '90%', 'sem_list_row') . "</tr>";
//        }
//        $htxt = $status[$i];
//        if ($row->nrbooked < 1) {
//            $htxt = JTEXT::_('COM_MATUKIO_CANNOT_BOOK_ONLINE');
//        }
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_STATUS') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($htxt, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        if ($row->codepic != "") {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BOOKING_ID') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022(getBookingId($row->codepic), 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->showbegin > 0) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BEGIN') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022(JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->showend > 0) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_END') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022(JHTML::_('date', $row->end), 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->showbooked > 0) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022(JHTML::_('date', $row->booked), 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->teacher != "") {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TUTOR') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($row->teacher, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->target != "") {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($row->target, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_CITY') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($row->place, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        if ($row->nrbooked > 0) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($row->maxpupil, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BOOKINGS') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($gebucht, 'd', 'l', '', 'sem_list_row') . "</tr>";
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_BOOKABLE') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022($freieplaetze, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->fees > 0) {
//            $html .= "<tr>" . sem_f022(JTEXT::_('COM_MATUKIO_FEES') . ":", 'd', 'l', '', 'sem_list_row') . sem_f022(MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . $row->fees, 'd', 'l', '', 'sem_list_row') . "</tr>";
//        }
//        if ($row->description != "") {
//            if (count($args) == 2) {
//                $row->description = str_replace("images/", "../images/", $row->description);
//            }
//            $html .= "<tr>" . sem_f022(sem_f066($row->description), 'd', 'l', '100%', 'sem_list_row', 2) . "</tr>";
//        }
//        if ($row->codepic != "") {
//            $html .= "<tr>" . sem_f022(getBookingIdCodePicture($row->codepic), 'd', 'c', '100%', 'sem_list_row', 2) . "</tr>";
//        }
//        $html .= "\n" . sem_f023('e') . "<br />";
//    }
//    $html .= getCopyright();
//    $html .= "</body></html>";
//    echo $html;
//    exit;
}

//// +++++++++++++++++++++++++++++++++++++++++++++++
//// +++ Templateliste erstellen                 +++
//// +++++++++++++++++++++++++++++++++++++++++++++++
//
//function sem_f057($vorlage, $art)
//{
//    $html = "";
//    $database = JFactory::getDBO();
//
//    $my = JFactory::getuser();
//    $where = array();
//
//    // Nur veroeffentlichte Kurse anzeigen
//    $where[] = "published = '1'";
//    $where[] = "pattern != ''";
//    $where[] = "publisher = '" . $my->id . "'";
//
//    // nur Kurse anzeigen, deren Kategorie fuer den Benutzer erlaubt ist
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $accesslvl = 1;
//    if ($reglevel >= 6) {
//        $accesslvl = 3;
//    } else if ($reglevel >= 2) {
//        $accesslvl = 2;
//    }
//    $database->setQuery("SELECT id, access FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
//    $cats = $database->loadObjectList();
//    $allowedcat = array();
//    $allowedcat[] = 0;
//    foreach ((array)$cats AS $cat) {
//        if ($cat->access < $accesslvl) {
//            $allowedcat[] = $cat->id;
//        }
//    }
//    if (count($allowedcat) > 0) {
//        $allowedcat = implode(',', $allowedcat);
//        $where[] = "catid IN ($allowedcat)";
//    }
//    $database->setQuery("SELECT * FROM #__matukio"
//            . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//            . "\nORDER BY pattern"
//    );
//    $rows = $database->loadObjectList();
//    $patterns = array();
//    $patterns[] = JHTML::_('select.option', '', JTEXT::_('COM_MATUKIO_CHOOSE_TEMPLATE'));
//    foreach ($rows AS $row) {
//        $patterns[] = JHTML::_('select.option', $row->id, $row->pattern);
//    }
//    $htxt = JTEXT::_('COM_MATUKIO_TEMPLATE') . ": ";
//    $disabled = "";
//    if ($vorlage == 0) {
//        $disabled = " disabled";
//    }
//    if ($art == 1) {
//        if (count($patterns) > 1) {
//            $htxt .= JHTML::_('select.genericlist', $patterns, 'vorlage', 'class="sem_inputbox" size="1" onChange="form.cid.value=form.vorlage.value;form.task.value=9;form.submit();"', 'value', 'text', $vorlage);
//            $htxt .= " <button class=\"button\" id=\"tmpldel\" style=\"cursor:pointer;\" type=\"button\" onclick=\"form.cid.value=form.vorlage.value;form.task.value=11;form.submit();\"" . $disabled . "><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "1516.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_DELETE') . "</button>";
//        } else {
//            $htxt .= "<input type=\"hidden\" name=\"vorlage\" value=\"0\">";
//        }
//        $htxt .= " <input type=\"text\" name=\"pattern\" id=\"pattern\" class=\"sem_inputbox\" value=\"\" onKeyup=\"if(this.value=='') {form.tmplsave.disabled=true;} else {form.tmplsave.disabled=false;}\">";
//        $htxt .= " <button class=\"button\" id=\"tmplsave\" style=\"cursor:pointer;\" type=\"button\" onclick=\"form.task.value=10;form.submit();\" disabled><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "1416.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_SAVE') . "</button>";
//        $html = "<tr>" . sem_f022($htxt, 'd', 'c', '80%', 'sem_nav', 2) . "</tr>";
//    } else if ($art == 2) {
//        if (count($patterns) > 1) {
//            $htxt .= JHTML::_('select.genericlist', $patterns, 'vorlage', 'class="sem_inputbox" size="1" onChange="form.id.value=form.vorlage.value;form.task.value=\'12\';form.submit();"', 'value', 'text', $vorlage);
//            $html = "<tr>" . sem_f022($htxt, 'd', 'c', '80%', 'sem_nav', 2) . "</tr>";
//        }
//    }
//    return $html;
//}


// ++++++++++++++++++++++++++++++++++++++
// +++ Benutzer anmelden              +++
// ++++++++++++++++++++++++++++++++++++++
//
//function loginUser()
//{
//    $mainframe = JFactory::getApplication();
//    $username = JFactory::getApplication()->input->get('semusername', JTEXT::_('USERNAME'));
//    $password = JFactory::getApplication()->input->get('sempassword', JTEXT::_('PASSWORD'));
//    if ($username != JTEXT::_('USERNAME')) {
//        $data['username'] = $username;
//        $data['password'] = $password;
//        $option['remember'] = true;
//        $option['silent'] = true;
//        $mainframe->login($data, $option);
//    }
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ ICS-Datei senden               +++
// ++++++++++++++++++++++++++++++++++++++

function sem_f059()
{
    $database = JFactory::getDBO();

//    $cid = trim(JFactory::getApplication()->input->get('cid', 0));
//    $kurs = new mosSeminar($database);
//    $kurs->load($cid);
//    $user = JFactory::getuser($kurs->publisher);
//    $icsdata = "BEGIN:VCALENDAR\n";
//    $icsdata .= "VERSION:2.0\n";
//    $icsdata .= "PRODID:" . MatukioHelperUtilsBasic::getSitePath() . "\n";
//    $icsdata .= "METHOD:PUBLISH\n";
//    $icsdata .= "BEGIN:VEVENT\n";
//    $icsdata .= "UID:" . getBookingId($kurs->id) . "\n";
//    $icsdata .= "ORGANIZER;CN=\"" . $user->name . "\":MAILTO:" . $user->email . "\n";
//    $icsdata .= "SUMMARY:" . $kurs->title . "\n";
//    $icsdata .= "LOCATION:" . ereg_replace("(\r\n|\n|\r)", ", ", $kurs->place) . "\n";
//    $icsdata .= "DESCRIPTION:" . ereg_replace("(\r\n|\n|\r)", " ", $kurs->shortdesc) . "\n";
//    $icsdata .= "CLASS:PUBLIC\n";
//    $icsdata .= "DTSTART:" . strftime("%Y%m%dT%H%M%S", strtotime($kurs->begin)) . "\n";
//    $icsdata .= "DTEND:" . strftime("%Y%m%dT%H%M%S", strtotime($kurs->end)) . "\n";
//    $icsdata .= "DTSTAMP:" . strftime("%Y%m%dT%H%M%S", strtotime(MatukioHelperUtilsDate::getCurrentDate())) . "\n";
//    $icsdata .= "BEGIN:VALARM\n";
//    $icsdata .= "TRIGGER:-PT1440M\n";
//    $icsdata .= "ACTION:DISPLAY\n";
//    $icsdata .= "DESCRIPTION:Reminder\n";
//    $icsdata .= "END:VALARM\n";
//    $icsdata .= "END:VEVENT\n";
//    $icsdata .= "END:VCALENDAR";
//    header("Content-Type: text/calendar; charset=utf-8");
//    header("Content-Length: " . strlen($icsdata));
//    header("Content-Disposition: attachment; filename=\"$kurs->title.ics\"");
//    header('Pragma: no-cache');
//    echo $icsdata;
    exit;
}

//// ++++++++++++++++++++++++++++++++++
//// +++ Aray mit Dateien erzeugen
//// ++++++++++++++++++++++++++++++++++
//
//function sem_f060($row)
//{
//    $zusfeld = array();
//    $zusfeld[] = array($row->file1, $row->file2, $row->file3, $row->file4, $row->file5);
//    $zusfeld[] = array($row->file1desc, $row->file2desc, $row->file3desc, $row->file4desc, $row->file5desc);
//    $zusfeld[] = array($row->file1down, $row->file2down, $row->file3down, $row->file4down, $row->file5down);
//    return $zusfeld;
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Datei senden                   +++
// ++++++++++++++++++++++++++++++++++++++

function sem_f061()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//
//    $daten = trim(JRequest::getVar('a6d5dgdee4cu7eho8e7fc6ed4e76z', ''));
//    $cid = substr($daten, 40);
//    $dat = substr($daten, 0, 40);
//    $kurs = new mosSeminar($database);
//    $kurs->load($cid);
//    $datfeld = sem_f060($kurs);
//    for ($i = 0; $i < count($datfeld[0]); $i++) {
//        if (sha1(md5($datfeld[0][$i])) == $dat AND ($datfeld[2][$i] == 0 OR ($my->id > 0 AND $datfeld[2][$i] > 0))) {
//            $datname = $datfeld[0][$i];
//            $datcode = "file" . ($i + 1) . "code";
//            $daten = base64_decode($kurs->$datcode);
//            $datext = array_pop(explode(".", strtolower($datname)));
//            header("Content-Type: application/$datext");
//            header("Content-Length: " . strlen($daten));
//            header("Content-Disposition: attachment; filename=\"$datname\"");
//            header('Pragma: no-cache');
//            echo $daten;
//            exit;
//        }
//    }
//    JError::raiseError(403, JText::_("ALERTNOTAUTH"));

}

// ++++++++++++++++++++++++++++++++++++++
// +++ Spendenzeile ausgeben          +++
// ++++++++++++++++++++++++++++++++++++++

function sem_f062()
{
    //$html = "<br /><center><table><tr><td align=\"right\">";
    //$html .= "</tr></table></center>";
    return "";
}


//// ++++++++++++++++++++++++++++++++++++++
//// +++ Plugins in Texten aktivieren   +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f063($text)
//{
//    $text = JHtml::_('content.prepare', $text);
//    return $text;
//}

// ++++++++++++++++++++++++++++++++++++++
// +++ Neue Seminarnummer erzeugen    +++
// ++++++++++++++++++++++++++++++++++++++

//function MatukioHelperUtilsEvents::createNewEventNumber($newyear)
//{
//    $database = JFactory::getDBO();
//    $database->setQuery("SELECT * FROM #__matukio_number WHERE year = '$newyear'");
//
//    $temp = $database->loadObjectList();
//
//    if (count($temp) == 0) {
//        $neu = new mossemnumber($database);
//
//        if (!$neu->bind($_POST)) {
//            var_dump($neu->getErrors());
//            exit();
//        }
//
//        $neu->year = $newyear;
//        $neu->number = "1";
//
//        if (!$neu->store()) {
//            var_dump($neu->getErrors());
//            exit();
//        }
//
//        $neu->checkin();
//    } else {
//        $database->setQuery("UPDATE #__matukio_number SET number = number+1 WHERE year = '$newyear'");
//        if (!$database->query()) {
//            die($database->getErrorMsg(false));
//            exit();
//        }
//    }
//    $database->setQuery("SELECT * FROM #__matukio_number WHERE year = '$newyear'");
//    $zaehlers = $database->loadObjectList();
//    $zaehler = &$zaehlers[0];
//    return $zaehler->number . "/" . substr($newyear, 2);
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Ausgabe parsen                 +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f065($text, $status)
//{
//    preg_match_all("`\[" . $status . "\](.*)\[/" . $status . "\]`U", $text, $ausgabe);
//    for ($i = 0; $i < count($ausgabe[0]); $i++) {
//        $text = str_replace($ausgabe[0][$i], $ausgabe[1][$i], $text);
//    }
//    preg_match_all("`\[sem_[^\]]+\](.*)\[/sem_[^\]]+\]`U", $text, $ausgabe);
//    for ($i = 0; $i < count($ausgabe[0]); $i++) {
//        $text = str_replace($ausgabe[0][$i], "", $text);
//    }
//    return $text;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Ausgabe saeubern                +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f066($text)
//{
//    preg_match_all("`\[sem_[^\]]+\](.*)\[/sem_[^\]]+\]`U", $text, $ausgabe);
//    for ($i = 0; $i < count($ausgabe[0]); $i++) {
//        $text = str_replace($ausgabe[0][$i], "", $text);
//    }
//    preg_match_all("`\{[^\}]+\}`U", $text, $ausgabe);
//    for ($i = 0; $i < count($ausgabe[0]); $i++) {
//        $text = str_replace($ausgabe[0][$i], "", $text);
//    }
//    return $text;
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ Eingabe prüfen                 +++
//// ++++++++++++++++++++++++++++++++++++++
//
//function sem_f067($text, $art = 'leer')
//{
//    $htxt = false;
//    switch ($art) {
//// texteingabe prüfen - alle eingaben auf leere eingaben prüfen
//        case 'leer':
//            $text = trim($text);
//            if ($text != '') {
//                $htxt = true;
//            }
//            break;
//// auf nur zahlen prüfen
//        case 'nummer':
//            if (preg_match("#^[0-9]+$#", $text)) {
//                $htxt = true;
//            }
//            break;
//// auf telefonnummer prüfen mit min. 6 zahlen
//        case 'telefon':
//            if (preg_match("#^[ 0-9\/-+]{6,}+$#", $text)) {
//                $htxt = true;
//            }
//            break;
//// auf nur buchstaben prüfen
//        case 'buchstabe':
//            if (preg_match("/^[ a-za-zäöüß]+$/i", $text)) {
//                $htxt = true;
//            }
//            break;
//// auf nur ein wort prüfen
//        case 'wort':
//            if (preg_match("/^[a-za-zäöüß]+$/i", $text)) {
//                $htxt = true;
//            }
//            break;
//// url prüfen
//        case 'url':
//            $text = trim($text);
//            if (preg_match("#^(http|https)+(://www.)+([a-z0-9-_.]{2,}\.[a-z]{2,4})$#i", $text)) {
//                $htxt = true;
//            }
//            break;
//// email-adresse prüfen
//        case 'email':
//            $text = trim($text);
//            if ($text != '') {
//                $_pat = "^[_a-za-z0-9-]+(.[_a-za-z0-9-]+)*@([a-z0-9-]{3,})+.([a-za-z]{2,4})$";
//                if (!preg_match("|$_pat|i", $text)) {
//                    $htxt = false;
//                }
//            } else {
//                $htxt = false;
//            }
//            break;
//// Zahl der Laenge art pruefen
//        default:
//            if (preg_match("/^[0-9]{$art}$/", $cvalue)) {
//                $htxt = true;
//            }
//            break;
//    }
//    return $htxt;
//}


//// ++++++++++++++++++++++++++++++++++++++
//// +++ DB fuer Buchungen              +++
//// ++++++++++++++++++++++++++++++++++++++
//
//class mosSembookings extends JTable
//{
//    var $id = null;
//    var $name = null;
//    var $email = 0;
//    var $sid = null;
//    var $semid = null;
//    var $userid = null;
//    var $bookingdate = null;
//    var $updated = null;
//    var $certificated = null;
//    var $grade = null;
//    var $comment = null;
//    var $paid = null;
//    var $nrbooked = null;
//    var $zusatz1 = null;
//    var $zusatz2 = null;
//    var $zusatz3 = null;
//    var $zusatz4 = null;
//    var $zusatz5 = null;
//    var $zusatz6 = null;
//    var $zusatz7 = null;
//    var $zusatz8 = null;
//    var $zusatz9 = null;
//    var $zusatz10 = null;
//    var $zusatz11 = null;
//    var $zusatz12 = null;
//    var $zusatz13 = null;
//    var $zusatz14 = null;
//    var $zusatz15 = null;
//    var $zusatz16 = null;
//    var $zusatz17 = null;
//    var $zusatz18 = null;
//    var $zusatz19 = null;
//    var $zusatz20 = null;
//
//    function mosSembookings(&$db)
//    {
//        parent::__construct('#__matukio_bookings', 'id', $db);
//    }
//}
//
//// ++++++++++++++++++++++++++++++++++++++
//// +++ DB fuer Veranstaltungen        +++
//// ++++++++++++++++++++++++++++++++++++++
//
//class mosSeminar extends JTable
//{
//    var $id = null;
//    var $sid = 0;
//    var $catid = 1;
//    var $semnum = "";
//    var $title = "";
//    var $target = "";
//    var $shortdesc = "";
//    var $description = "";
//    var $place = "";
//    var $teacher = "";
//    var $fees = 0;
//    var $maxpupil = 12;
//    var $bookedpupil = 0;
//    var $stopbooking = 0;
//    var $cancelled = 0;
//    var $begin = "0000-00-00 00:00:00";
//    var $end = "0000-00-00 00:00:00";
//    var $booked = "0000-00-00 00:00:00";
//    var $showbegin = 1;
//    var $showend = 1;
//    var $showbooked = 1;
//    var $checked_out = 0;
//    var $checked_out_time = "0000-00-00 00:00:00";
//    var $ordering = 0;
//    var $published = 0;
//    var $publishdate = "0000-00-00 00:00:00";
//    var $updated = null;
//    var $publisher = "";
//    var $access = 0;
//    var $hits = 0;
//    var $grade = 0;
//    var $certificated = 0;
//    var $paid = 0;
//    var $gmaploc = "";
//    var $nrbooked = 1;
//    var $pattern = "";
//    var $zusatz1 = "";
//    var $zusatz2 = "";
//    var $zusatz3 = "";
//    var $zusatz4 = "";
//    var $zusatz5 = "";
//    var $zusatz6 = "";
//    var $zusatz7 = "";
//    var $zusatz8 = "";
//    var $zusatz9 = "";
//    var $zusatz10 = "";
//    var $zusatz11 = "";
//    var $zusatz12 = "";
//    var $zusatz13 = "";
//    var $zusatz14 = "";
//    var $zusatz15 = "";
//    var $zusatz16 = "";
//    var $zusatz17 = "";
//    var $zusatz18 = "";
//    var $zusatz19 = "";
//    var $zusatz20 = "";
//    var $zusatz1hint = "";
//    var $zusatz2hint = "";
//    var $zusatz3hint = "";
//    var $zusatz4hint = "";
//    var $zusatz5hint = "";
//    var $zusatz6hint = "";
//    var $zusatz7hint = "";
//    var $zusatz8hint = "";
//    var $zusatz9hint = "";
//    var $zusatz10hint = "";
//    var $zusatz11hint = "";
//    var $zusatz12hint = "";
//    var $zusatz13hint = "";
//    var $zusatz14hint = "";
//    var $zusatz15hint = "";
//    var $zusatz16hint = "";
//    var $zusatz17hint = "";
//    var $zusatz18hint = "";
//    var $zusatz19hint = "";
//    var $zusatz20hint = "";
//    var $zusatz1show = 0;
//    var $zusatz2show = 0;
//    var $zusatz3show = 0;
//    var $zusatz4show = 0;
//    var $zusatz5show = 0;
//    var $zusatz6show = 0;
//    var $zusatz7show = 0;
//    var $zusatz8show = 0;
//    var $zusatz9show = 0;
//    var $zusatz10show = 0;
//    var $zusatz11show = 0;
//    var $zusatz12show = 0;
//    var $zusatz13show = 0;
//    var $zusatz14show = 0;
//    var $zusatz15show = 0;
//    var $zusatz16show = 0;
//    var $zusatz17show = 0;
//    var $zusatz18show = 0;
//    var $zusatz19show = 0;
//    var $zusatz20show = 0;
//    var $image = "";
//    var $file1 = "";
//    var $file2 = "";
//    var $file3 = "";
//    var $file4 = "";
//    var $file5 = "";
//    var $file1desc = "";
//    var $file2desc = "";
//    var $file3desc = "";
//    var $file4desc = "";
//    var $file5desc = "";
//    var $file1down = 0;
//    var $file2down = 0;
//    var $file3down = 0;
//    var $file4down = 0;
//    var $file5down = 0;
//    var $file1code = "";
//    var $file2code = "";
//    var $file3code = "";
//    var $file4code = "";
//    var $file5code = "";
//
//    function mosSeminar(&$db)
//    {
//        parent::__construct('#__matukio', 'id', $db);
//    }
//}

//// ++++++++++++++++++++++++++++++++++++++
//// +++ DB fuer Seminarzaehler         +++
//// ++++++++++++++++++++++++++++++++++++++
//
//class mosSemnumber extends JTable
//{
//    var $id = null;
//    var $number = null;
//    var $year = null;
//
//    function mosSemnumber(&$db)
//    {
//        parent::__construct('#__matukio_number', 'id', $db);
//    }
//}


?>
