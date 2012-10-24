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

class MatukioHelperUtilsEvents
{
    private static $instance;

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Neue Seminarnummer erzeugen    +++  sem_f064
    // ++++++++++++++++++++++++++++++++++++++
    // MatukioHelperUtilsEvents::createNewEventNumber

    public static function createNewEventNumber($newyear)
    {
        $database = &JFactory::getDBO();
        $database->setQuery("SELECT * FROM #__matukio_number WHERE year = '$newyear'");

        $temp = $database->loadObjectList();

        if (count($temp) == 0) {
            //$neu = new mossemnumber($database);
            $neu = JTable::getInstance("Number", "Table");

            if (!$neu->bind(JRequest::get( 'post' ))) {
                return JError::raiseError(500, $database->stderr());
            }

            $neu->year = $newyear;
            $neu->number = "1";

            if (!$neu->store()) {
                return JError::raiseError(500, $database->stderr());
            }

            $neu->checkin();
        } else {
            $database->setQuery("UPDATE #__matukio_number SET number = number+1 WHERE year = '$newyear'");
            if (!$database->query()) {
                die($database->getErrorMsg(false));
            }
        }
        $database->setQuery("SELECT * FROM #__matukio_number WHERE year = '$newyear'");
        $zaehlers = $database->loadObjectList();
        $zaehler = &$zaehlers[0];
        return $zaehler->number . "/" . substr($newyear, 2);
    }


    // ++++++++++++++++++++++++++++++++++++++
    // +++ Waehrung formatieren           +++    sem_f044
    // ++++++++++++++++++++++++++++++++++++++

    public static function getFormatedCurrency($betrag)
    {

        return $betrag; // TODO FIX THIS
    }


    // ++++++++++++++++++++++++++++++++++++++
    // +++ Seitennavigation bereinigen    +++    sem_f039
    // ++++++++++++++++++++++++++++++++++++++

    public static function cleanSiteNavigation($total, $limit, $limitstart)
    {
        $pagenav = array();
        $navi = "";
        $pageone = 1;
        $seiten = 1;
        $kurse = "";
        if ($limit > 0) {
            $pageone = $limitstart / $limit + 1;
            $seiten = ceil($total / $limit);
            if ($pageone > 1) {
                $navi .= "<a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='0';document.FrontForm.submit();\">"
                    . JTEXT::_('COM_MATUKIO_START') . "</a>";
                $navi .= " - <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='"
                    . ($limitstart - $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('COM_MATUKIO_PREV') . "</a>";
            } else {
                $navi .= JTEXT::_('COM_MATUKIO_START');
                $navi .= " - " . JTEXT::_('COM_MATUKIO_PREV');
            }
            $start = 0;
            $ende = $seiten;
            $navi .= " -";
            if ($seiten > 5) {
                if ($pageone > 3) {
                    $navi .= " ...";
                    if ($seiten - 2 >= $pageone) {
                        $start = $pageone - 3;
                        $ende = $pageone + 2;
                    } else {
                        $start = $seiten - 5;
                        $ende = $seiten;
                    }
                } else {
                    $ende = 5;
                }
            }
            for ($i = $start; $i < $ende; $i++) {
                if ($i * $limit != $limitstart) {
                    $navi .= " <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='"
                        . ($i * $limit) . "';document.FrontForm.submit();\">" . ($i + 1) . "</a>";
                } else {
                    $navi .= " " . ($i + 1);
                    $kurs1 = (($i * $limit) + 1);
                    $kurs2 = (($i + 1) * $limit);
                    if ($kurs2 > $total) {
                        $kurs2 = $total;
                    }
                    if ($kurs1 == $kurs2) {
                        $kurse = $kurs2 . "/" . $total;
                    } else {
                        $kurse = $kurs1 . "-" . $kurs2 . "/" . $total;
                    }
                }
            }
            if ($seiten > 5) {
                if ($pageone + 2 < $seiten) {
                    $navi .= " ...";
                }
            }
            $navi .= " -";
            if ($pageone < $seiten) {
                $navi .= " <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='"
                    . ($limitstart + $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('COM_MATUKIO_NEXT') . "</a>";
                $navi .= " - <a class=\"sem_tab\" href=\"javascript:document.FrontForm.limitstart.value='"
                    . ($seiten * $limit) . "';document.FrontForm.submit();\">" . JTEXT::_('COM_MATUKIO_END') . "</a>";
            } else {
                $navi .= " " . JTEXT::_('COM_MATUKIO_NEXT');
                $navi .= " - " . JTEXT::_('COM_MATUKIO_END');
            }
        }
        $seite = JTEXT::_('COM_MATUKIO_PAGE') . "&nbsp;" . $pageone . "/" . ($seiten);
        return "\n" . MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>" . MatukioHelperUtilsEvents::getTableCell($seite, 'd', 'l', '', 'sem_nav')
            . MatukioHelperUtilsEvents::getTableCell($navi, 'd', 'c', '', 'sem_nav')
            . MatukioHelperUtilsEvents::getTableCell($kurse, 'd', 'r', '', 'sem_nav') . "</tr>" . MatukioHelperUtilsEvents::getTableHeader('e');
    }


    // ++++++++++++++++++++++++++++++++++++++
    // +++ Tabellenkopf ausgeben       sem_f023
    // ++++++++++++++++++++++++++++++++++++++

    public static function getTableHeader()
    {
        $args = func_get_args();
        if (is_numeric($args[0])) {
            $html = "\n<table cellpadding=\"" . $args[0] . "\" cellspacing=\"0\" border=\"0\"";
            if (count($args) == 2) {
                $html .= " class=\"" . $args[1] . "\"";
            }
            $html .= " width=\"100%\">";
        } else {
            $html = "\n</table>";
        }
        return $html;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Tabellenzelle ausgeben                sem_f022
// ++++++++++++++++++++++++++++++++++++++
// sem_f022(text,art,align,width,class,colspan)

    public static function getTableCell()
    {
        $args = func_get_args();
        $html = "\n<t" . $args[1];
        if (count($args) > 4) {
            if ($args[4] != "") {
                $html .= " class=\"" . $args[4] . "\"";
            }
        }
        if (count($args) > 2) {
            if ($args[2] != "") {
                $html .= " style=\"text-align:";
                switch ($args[2]) {
                    case "l":
                        $html .= "left";
                        break;
                    case "r":
                        $html .= "right";
                        break;
                    case "c":
                        $html .= "center";
                        break;
                }
                $html .= ";\"";
            }
        }
        if (count($args) > 3) {
            if ($args[3] != "") {
                $html .= " width=\"" . $args[3] . "\"";
            }
        }
        if (count($args) > 5) {
            if ($args[5]) {
                $html .= " colspan=\"" . $args[5] . "\"";
            }
        }
        $html .= ">" . $args[0] . "</t" . $args[1] . ">";
        return $html;
    }


    // ++++++++++++++++++++++++++++++++++
    // +++ Kopf-Bereiche ausgeben     +++         sem_f032
    // ++++++++++++++++++++++++++++++++++

    public static function getEventlistHeader($tab)
    {
        $confusers = &JComponentHelper::getParams('com_users');
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        switch ($tab) {
            case "2":
                $tabnum = array(0, 1, 0);
                break;
            case "3":
                $tabnum = array(0, 0, 1);
                break;
            default:
                $tabnum = array(1, 0, 0);
                break;
        }
        $html = "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";
        if ($reglevel > 1) {

            // Default View
            // semauf(0,'','');
            $defaultlink = JRoute::_("index.php?option=com_matukio");
            $html .= "\n<td class=\"sem_tab" . $tabnum[0] . "\">";
            $html .= "\n<a class=\"sem_tab\" href=\"". $defaultlink . "\" title=\""
                . JTEXT::_('COM_MATUKIO_EVENTS') . "\" " . MatukioHelperUtilsBasic::getMouseOverWindowStatus(JTEXT::_('COM_MATUKIO_EVENTS')) . ">
                <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2600.png\" border=\"0\" align=\"absmiddle\"> "
                . JTEXT::_('COM_MATUKIO_EVENTS') . "</a>";
            $html .= "</td>";
            $html .= "\n<td class=\"sem_tab" . $tabnum[1] . "\">";

            // Own Booking
            //javascript:document.FrontForm.limitstart.value='0';semauf(1,'','');
            $linkownbook = JRoute::_("index.php?option=com_matukio&art=1");

            $html .= "\n<a class=\"sem_tab\" title=\"" . JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . "\" href=\"" .$linkownbook . "\" "
                . MatukioHelperUtilsBasic::getMouseOverWindowStatus(JTEXT::_('COM_MATUKIO_MY_BOOKINGS')) . "><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath()
                . "2700.png\" border=\"0\" align=\"absmiddle\"> " . JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . "</a>";
            $html .= "\n</td>";

            //MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));

            // Own Events
            //javascript:document.FrontForm.limitstart.value='0';semauf(2,'','');
            $linkownevents = JRoute::_("index.php?option=com_matukio&art=2");

            $create_frontendevents = MatukioHelperSettings::getSettings('frontend_createevents', 0);

            //if(!empty($create_frontendevents)) {
//                if ($reglevel >= $create_frontendevents) {
            if (JFactory::getUser()->authorise('core.create', 'com_matukio.frontend.')) {

                    $html .= "\n<td class=\"sem_tab" . $tabnum[2] . "\">";
                $html .= "\n<a class=\"sem_tab\" title=\"" . JTEXT::_('COM_MATUKIO_MY_OFFERS') . "\" href=\"" . $linkownevents . "\" "
                    . MatukioHelperUtilsBasic::getMouseOverWindowStatus(JTEXT::_('COM_MATUKIO_MY_OFFERS')) . "><img src=\""
                    . MatukioHelperUtilsBasic::getComponentImagePath()
                    . "2800.png\" border=\"0\" align=\"absmiddle\"> " . JTEXT::_('COM_MATUKIO_MY_OFFERS') . "</a>";
                $html .= "\n</td>";
            }
                //}
            //}
        } else if (MatukioHelperSettings::getSettings('frontend_unregisteredshowlogin', 1) > 0) {

            // Joomla > 1.6 com_users !
            $baseuserurl = "index.php?option=com_user";
            if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
                $baseuserurl = "index.php?option=com_users";
            }

            $registrationurl = "&amp;view=register";
            if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
                $registrationurl = "&amp;view=registration";
            }

            $html .= "<td class=\"sem_notableft\">";
            $html .= "<input type=\"text\" name=\"semusername\" value=\"" . JTEXT::_('USERNAME') . "\" class=\"sem_inputbox\" style=\"background-image:url("
                . MatukioHelperUtilsBasic::getComponentImagePath() . "0004.png);background-repeat:no-repeat;background-position:2px;padding-left:18px;width:100px;vertical-align:middle;\" onFocus=\"if(this.value=='"
                . JTEXT::_('USERNAME') . "') this.value='';\" onBlur=\"if(this.value=='') {this.value='"
                . JTEXT::_('USERNAME') . "';form.semlogin.disabled=true;}\" onKeyup=\"if(this.value!='') form.semlogin.disabled=false;\"> ";
            $html .= "<input type=\"password\" name=\"sempassword\" value=\"" . JTEXT::_('PASSWORD') . "\" class=\"sem_inputbox\" style=\"background-image:url("
                . MatukioHelperUtilsBasic::getComponentImagePath() . "0005.png);background-repeat:no-repeat;background-position:2px;padding-left:18px;width:100px;vertical-align:middle;\" onFocus=\"if(this.value=='"
                . JTEXT::_('PASSWORD') . "') this.value='';\" onBlur=\"if(this.value=='') this.value='" . JTEXT::_('PASSWORD') . "';\"> ";

            $html .= "<button class=\"button\" type=\"submit\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
                . JTEXT::_('LOGIN') . "\" id=\"semlogin\" disabled><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0007.png\" style=\"vertical-align:middle;\"></button>";
            $html .= "&nbsp;&nbsp;&nbsp;";
            $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
                . JTEXT::_('COM_MATUKIO_FORGOTTEN_USERNAME') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . "&amp;view=remind'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0008.png\" style=\"vertical-align:middle;\"></button>";
            $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
                . JTEXT::_('COM_MATUKIO_CHANGE_PASSWORD') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . "&amp;view=reset'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0009.png\" style=\"vertical-align:middle;\"></button>";
            if ($confusers->get('allowUserRegistration', 0) > 0) {
                $html .= " <button class=\"button\" type=\"button\" style=\"cursor:pointer;vertical-align:middle;padding-left:0pt;padding-right:0pt;padding-top:0pt;padding-bottom:0pt;\" title=\""
                    . JTEXT::_('COM_MATUKIO_REGISTER') . "\" onClick=\"location.href='" . MatukioHelperUtilsBasic::getSitePath() . $baseuserurl . $registrationurl . "'\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0006.png\" style=\"vertical-align:middle;\"></button>";
            }
            $html .= "</td>";
        }
        $html .= "<td class=\"sem_notab\">&nbsp;";
        $knopfunten = "";

        if ($reglevel > 1 and MatukioHelperSettings::getSettings('frontend_unregisteredshowlogin', 1) > 0) {
            $logoutlink = JRoute::_("index.php?option=com_matukio&view=matukio&task=logoutUser");

            $html .= JHTML::_('link', $logoutlink, JHTML::_('image', MatukioHelperUtilsBasic::getComponentImagePath() . '3232.png', null,
                array('border' => '0', 'align' => 'absmiddle')), array('title' => JTEXT::_('COM_MATUKIO_LOGOUT'))) . "&nbsp;&nbsp;";

            $knopfunten .= "<a href=\"" . $logoutlink . "\"><span class=\"mat_button\" style=\"cursor:pointer;\">" . JHTML::_('image',
                MatukioHelperUtilsBasic::getComponentImagePath() . '3216.png', null, array('border' => '0',
                    'align' => 'absmiddle')) . "&nbsp;" . JTEXT::_('COM_MATUKIO_LOGOUT') . "</span></a>";
        }
        echo $html;
        return $knopfunten;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Druckfenster im Frontend ausgeben        sem_f037
// ++++++++++++++++++++++++++++++++++++++

    public static function getPrintWindow($art, $cid, $uid, $knopf, $class = "default")
    {

        //  if(MatukioHelperUtilsBasic::getUserLevel() > 1) {
        $dateid = trim(JRequest::getVar('dateid', 1));
        $catid = trim(JRequest::getVar('catid', 0));
        $search = trim(strtolower(JRequest::getVar('search', '')));
        $limit = trim(JRequest::getVar('limit', MatukioHelperSettings::getSettings('event_showanzahl', 10)));
        $limitstart = trim(JRequest::getVar('limitstart', 0));
        if ($knopf == "") {
            $image = "1932";
        } else {
            $image = "1916";
        }
        $titel = JTEXT::_('COM_MATUKIO_PRINT');
        $href = JURI::ROOT() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&option=" . JRequest::getCmd('option')
            . "&view=printeventlist&dateid=" . $dateid . "&catid=" . $catid . "&search=" . $search . "&amp;limit=" . $limit . "&limitstart="
            . $limitstart . "&cid=" . $cid . "&uid=" . $uid . "&todo=";
        $x = 800;
        $y = 600;

        /**
         * 65O9805443904 =    public ?!                                 -done     ?
         * 653O875032490 =    Meine Angebote                                -- done ?
         * 6530387504345 =  Meine Buchungen / Buchungsbestätigung ?! -- done  ?
         * 4525487566184 = Teilnehmerliste     -- done
         * 3728763872762 = Unterschriftsliste = Teilnehmerliste & art = 1      -- done
         * 764576O987985 = cert
         * // print_eventlist, print_booking, print_myevents
         *
         */

        //die("Art:  " . $art);

        switch ($art) {
            case 1:
// Zertifikat
                $image = "2900";
                $titel = JTEXT::_('COM_MATUKIO_PRINT_CERTIFICATE');
                $href .= "certificate";
                break;
            case 2:
// Kursuebersicht
                $href .= "print_eventlist";
                break;
            case 3:
// gebuchte Kurse
                $href .= "print_booking";
                break;
            case 4:
// Kursangebot
                $href .= "print_myevents";
                break;
            case 5:
// Teilnehmerliste1
                $href .= "print_teilnehmerliste&art=1";
                if ($knopf == "") {
                    $image = "2032";
                } else {
                    $image = "2016";
                }
                break;
            case 6:
// Buchungsbestaetigung
                $href .= "1495735268456&amp;task=printbook";
                break;
            case 7:
// Teilnehmerliste2
                $href .= "print_teilnehmerliste";
                break;
        }

//        $btnclass = "button";
//
//        if($class == "modern"){
            $btnclass = "mat_button";
        //}

        if (($art > 1 && MatukioHelperSettings::getSettings('frontend_userprintlists', 1) > 0 OR ($art == 1 &&
            MatukioHelperSettings::getSettings('frontend_userprintcertificate', 0) > 0 &&
            MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0)))
        {
            if ($knopf == "") {
                return "<a title=\"" . $titel . "\" class=\"modal\" href=\"" . $href
                    . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\"><img src=\""
                    . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" align=\"absmiddle\"></a>";
            } else {
                return "<a class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: "
                    . $x . ", y: " . $y . "}}\"><span class=\"" . $btnclass . "\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
                    . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                    . $titel . "</span></a>";
            }
        } else if ($art == 1 AND MatukioHelperSettings::getSettings('frontend_certificatesystem', 0) > 0) {
            return "\n<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2900.png\" border=\"0\" align=\"absmiddle\">";
            //     } else {
            //       return "&nbsp;";
        }
        //  }
    }

    // ++++++++++++++++++++++++++++++++++++++
// +++ Bewertungsfenster ausgeben            sem_f035
// ++++++++++++++++++++++++++++++++++++++

    public static function getRatingPopup($dir, $cid, $imgid)
    {
        if (MatukioHelperUtilsBasic::getUserLevel() > 1) {
            $image = "240" . $imgid;
            $titel = JTEXT::_('COM_MATUKIO_YOUR_RATING');
            $href = JURI::ROOT() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&option="
                 . JRequest::getCmd('option') . "&cid=" . $cid . "&task=20";
            $x = 500;
            $y = 280;
            return "<a title=\"" . $titel . "\" class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: "
                . $x . ", y: " . $y . "}}\"><img id=\"graduate" . $cid . "\" src=\"" . $dir . $image
                . ".png\" border=\"0\" align=\"absmiddle\"></a>";
        }
    }

    // ++++++++++++++++++++++++++++++++++++++
// +++ Ende des Kopfbereichs ausgeben +++     sem_f033
// ++++++++++++++++++++++++++++++++++++++

    public static function getEventlistHeaderEnd()
    {
        echo "</td></tr>" . MatukioHelperUtilsEvents::getTableHeader('e') . MatukioHelperUtilsEvents::getTableHeader(4)
                . "<tr><td class=\"sem_anzeige\">";
    }



    // +++++++++++++++++++++++++++++++++++++++
// +++ Anzeige der Ueberschrift          +     sem_f041
// +++++++++++++++++++++++++++++++++++++++

    public static function printHeading($temp1, $temp2)
    {
        $html = "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
        $html .= "\n<tr><td class=\"sem_cat_title\">" . $temp1 . "</td></tr>";
        if ($temp2 != "") {
            $html .= "\n<tr><td class=\"sem_cat_desc\">" . $temp2 . "</td></tr>";
        }
        $html .= "\n</table>";
        echo $html;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Limitbox fuer Seitennavigation +++       sem_f040
// ++++++++++++++++++++++++++++++++++++++

    public static function getLimitboxSiteNav($art, $limit, $where = "eventlist")
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
//        return JHTML::_('select.genericlist', $limits, 'limit', 'class="sem_inputbox" size="1" onchange="document.'
//            . $htxt . '.limitstart.value=0;document.' . $htxt . '.submit()"', 'value', 'text', $limit);
        return JHTML::_('select.genericlist', $limits, 'limit', 'class="sem_inputbox" size="1" onchange="changeLimitEventlist()"', 'value', 'text', $limit);

    }

    // ++++++++++++++++++++++++++++++++++
    // +++ ist Kurs noch buchbar           sem_f021
    // ++++++++++++++++++++++++++++++++++

    public static function getEventBookableArray($art, $row, $usrid)    // usrid == uid
    {
        //echo "Eventbooking";
        //var_dump($row);
        //die("art: " . $art . " usrid : " . $usrid);
        $database = &JFactory::getDBO();
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' ORDER BY id");
        $temps = $database->loadObjectList();
        $gebucht = 0;
        foreach ($temps as $el) {
            $gebucht = $gebucht + $el->nrbooked;
        }

        if ($usrid < 0) {
            $sid = $usrid * -1;
            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$sid'");
            $userid = 0;
        } else {
            if ($usrid == 0) {
                $usrid = -1;
            }
            $query = "SELECT * FROM #__matukio_bookings WHERE semid='" . $row->id . "' AND userid = '" . $usrid . "'";
            $database->setQuery($query);
            //echo $query;
        }
        $temp = $database->loadObjectList();

        //var_dump($temp);
        //die("asdf");

        $freieplaetze = $row->maxpupil - $gebucht;
        if ($freieplaetze < 0) {
            $freieplaetze = 0;
        }
        $buchbar = 3;
        $buchgraf = 2;
        $altbild = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $neudatum = MatukioHelperUtilsDate::getCurrentDate();
        if ($neudatum > $row->booked) {
            $buchbar = 1;
            $buchgraf = 0;
            $altbild = JTEXT::_('COM_MATUKIO_REGISTRATION_END');
        } else if ($row->cancelled == 1 OR ($freieplaetze < 1
            AND $row->stopbooking == 1) OR ($usrid == $row->publisher
            AND MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0)
        ) {
            $buchbar = 1;
            $buchgraf = 0;
            $altbild = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
        } else if ($freieplaetze < 1 AND ($row->stopbooking == 0 OR $row->stopbooking == 2)) {
            $buchgraf = 1;
            $altbild = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
        }
        if (count($temp) > 0) {
            $buchbar = 2;
            $buchgraf = 0;
            $altbild = JTEXT::_('COM_MATUKIO_ALREADY_BOOKED');
        }
        if ($reglevel < 1) {
            $buchbar = 0;
        }
        if ($art == 1) {
            $buchgraf = 2;
            $altbild = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
            $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
            if ($gebucht->booked > $row->maxpupil) {
                if ($row->stopbooking == 0 OR $row->stopbooking == 2) {
                    $summe = 0;
                    for ($l = 0, $m = count($temps); $l < $m; $l++) {
                        $summe = $summe + $temps[$l]->nrbooked;
                        if ($temps[$l]->userid == $usrid) {
                            break;
                        }
                    }
                    if ($summe > $row->maxpupil) {
                        $buchgraf = 1;
                        $altbild = JTEXT::_('COM_MATUKIO_WAITLIST');
                    }
                } else {
                    $buchgraf = 0;
                    $altbild = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
                }
            }
            if ($row->cancelled == 1) {
                $buchgraf = 0;
                $altbild = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
            }
        }
        if ($art == 2) {
            $buchgraf = 2;
            $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_NOT_STARTED_YET');
            if ($neudatum > $row->end) {
                $buchgraf = 0;
                $altbild = JTEXT::_('COM_MATUKIO_EVENT_HAS_EVDED');
            } else if ($neudatum > $row->begin) {
                $buchgraf = 1;
                $altbild = JTEXT::_('COM_MATUKIO_EVENT_IS_RUNNING');
            }
        }
        return array($buchbar, $altbild, $temp, $buchgraf, $freieplaetze);
    }

    // ++++++++++++++++++++++++++++++++++
    // +++ Berechne die gebuchten Plaetze          sem_f020
    // ++++++++++++++++++++++++++++++++++

    public static function calculateBookedPlaces($row)
    {
        $zurueck = new stdClass();
        $database = &JFactory::getDBO();
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $row->id . "'");
        $temps = $database->loadObjectList();
        $gebucht = 0;
        $zertifiziert = 0;
        $bezahlt = 0;
        foreach ($temps as $el) {
            $gebucht = $gebucht + $el->nrbooked;
            $zertifiziert = $zertifiziert + $el->certificated;
            $bezahlt = $bezahlt + $el->paid;
        }
        $zurueck->booked = $gebucht;
        $zurueck->certificated = $zertifiziert;
        $zurueck->paid = $bezahlt;
        $zurueck->number = count($temps);
        return $zurueck;
    }


    // ++++++++++++++++++++++++++++++++++
    // +++ Farbbeschreibung anzeigen  +++       sem_f029
    // ++++++++++++++++++++++++++++++++++

    public static function getColorDescriptions($green, $yellow, $red)
    {
        $html = MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>";
        if ($green != "") {
            $html .= MatukioHelperUtilsEvents::getTableCell("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "2502.png\" border=\"0\" align=\"absmiddle\"> " . $green, 'd', 'c', '', 'sem_nav');
        }
        if ($yellow != "") {
            $html .= MatukioHelperUtilsEvents::getTableCell("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "2501.png\" border=\"0\" align=\"absmiddle\"> " . $yellow, 'd', 'c', '', 'sem_nav');
        }
        if ($red != "") {
            $html .= MatukioHelperUtilsEvents::getTableCell("<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "2500.png\" border=\"0\" align=\"absmiddle\"> " . $red, 'd', 'c', '', 'sem_nav');
        }
        $html .= "</tr>" . MatukioHelperUtilsEvents::getTableHeader('e');
        return $html;
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++
    // +++ Anzeige der versteckten Variablen im Frontend +       sem_f014
    // +++++++++++++++++++++++++++++++++++++++++++++++++++

    public static function getHiddenFormElements($task, $catid, $search, $limit, $limitstart, $cid, $dateid, $uid)
    {
        $html = "<input type=\"hidden\" name=\"task\" value=\"" . $task . "\" />";
        $html .= "<input type=\"hidden\" name=\"limitstart\" value=\"" . $limitstart . "\" />";
        $html .= "<input type=\"hidden\" name=\"cid\" value=\"" . $cid . "\" />";
        if ($catid != "") {
            $html .= "<input type=\"hidden\" name=\"catid\" value=\"" . $catid . "\" />";
        }
        if ($search != "") {
            $html .= "<input type=\"hidden\" name=\"search\" value=\"" . $search . "\" />";
        }
        if ($limit != "") {
            $html .= "<input type=\"hidden\" name=\"limit\" value=\"" . $limit . "\" />";
        }
        if ($uid != "") {
            if ($uid == -1) {
                $uid = "";
            }
            $html .= "<input type=\"hidden\" name=\"uid\" value=\"" . $uid . "\" />";
        }
        if ($dateid != "") {
            $html .= "<input type=\"hidden\" name=\"dateid\" value=\"" . $dateid . "\" />";
        }
        return $html;
    }

    // ++++++++++++++++++++++++++++++++++
    // +++ Aray mit Zusatzfeldern erzeugen     sem_f017
    // ++++++++++++++++++++++++++++++++++

    public static function getAdditionalFieldsFrontend($row)
    {
        $zusfeld = array();
        $zusfeld[] = array($row->zusatz1, $row->zusatz2, $row->zusatz3, $row->zusatz4, $row->zusatz5, $row->zusatz6, $row->zusatz7, $row->zusatz8,
            $row->zusatz9, $row->zusatz10, $row->zusatz11, $row->zusatz12, $row->zusatz13, $row->zusatz14, $row->zusatz15, $row->zusatz16,
            $row->zusatz17, $row->zusatz18, $row->zusatz19, $row->zusatz20);
        if (isset($row->zusatz1hint)) {
            $zusfeld[] = array($row->zusatz1hint, $row->zusatz2hint, $row->zusatz3hint, $row->zusatz4hint, $row->zusatz5hint,
                $row->zusatz6hint, $row->zusatz7hint, $row->zusatz8hint, $row->zusatz9hint, $row->zusatz10hint, $row->zusatz11hint,
                $row->zusatz12hint, $row->zusatz13hint, $row->zusatz14hint, $row->zusatz15hint, $row->zusatz16hint,
                $row->zusatz17hint, $row->zusatz18hint, $row->zusatz19hint, $row->zusatz20hint);
            $zusfeld[] = array($row->zusatz1show, $row->zusatz2show, $row->zusatz3show, $row->zusatz4show, $row->zusatz5show,
                $row->zusatz6show, $row->zusatz7show, $row->zusatz8show, $row->zusatz9show, $row->zusatz10show, $row->zusatz11show,
                $row->zusatz12show, $row->zusatz13show, $row->zusatz14show, $row->zusatz15show, $row->zusatz16show, $row->zusatz17show,
                $row->zusatz18show, $row->zusatz19show, $row->zusatz20show);
        }
        return $zusfeld;
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ E-Mail-Fenster ausgeben                         sem_f034
    // ++++++++++++++++++++++++++++++++++++++

    public static function getEmailWindow($dir, $cid, $art = 0, $class = "default")
    {
        $html = "";
        $href = MatukioHelperUtilsBasic::getSitePath() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar()
            . "&option=" . JRequest::getCmd('option') . "&view=contactorganizer&cid=" . $cid . "&task=";
        $x = 500;
        $y = 350;
        $htxt = "<a class=\"modal\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\" href=\"" . $href;

//        $btnclass = "button";
//
//        if($class == "modern"){
            $btnclass = "mat_button";
        //}

        if ($art == 1 AND MatukioHelperUtilsBasic::getUserLevel() > 1 AND MatukioHelperSettings::getSettings('sendmail_contact', 1) > 0) {
            $html = $htxt . "19\" title=\"" . JTEXT::_('COM_MATUKIO_CONTACT') . "\"><img src=\"" . $dir . "1732.png\" border=\"0\" align=\"absmiddle\"></a>";
        } else if ($art == 2 AND MatukioHelperUtilsBasic::getUserLevel() > 1 AND MatukioHelperSettings::getSettings('sendmail_contact', 1) > 0) {
            $html = $htxt . "19\"><span class=\"" . $btnclass . "\" type=\"button\"><img src=\"" . $dir . "1716.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_CONTACT') . "</span></a>";
        } else if ($art == 3 AND MatukioHelperUtilsBasic::getUserLevel() > 2) {
            $html = $htxt . "30\" title=\"" . JTEXT::_('COM_MATUKIO_CONTACT') . "\"><img src=\"" . $dir . "1732.png\" border=\"0\" align=\"absmiddle\"></a>";
        } else if ($art == 4 AND MatukioHelperUtilsBasic::getUserLevel() > 2) {
            $html = $htxt . "30\"><span class=\"" . $btnclass . "\" type=\"button\"><img src=\"" . $dir . "1716.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_CONTACT') . "</span></a>";
        } else if ($art == 2) {
            $html = $htxt . "19\"><span class=\"" . $btnclass . "\" type=\"button\"><img src=\"" . $dir . "1716.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_CONTACT') . "</span></a>";
        }
        return $html;
    }

    // ++++++++++++++++++++++++++++++++++
    // +++ Aray mit Dateien erzeugen       sem_f060
    // ++++++++++++++++++++++++++++++++++

    public static function getEventFileArray($row)
    {
        $zusfeld = array();
        $zusfeld[] = array($row->file1, $row->file2, $row->file3, $row->file4, $row->file5);
        $zusfeld[] = array($row->file1desc, $row->file2desc, $row->file3desc, $row->file4desc, $row->file5desc);
        $zusfeld[] = array($row->file1down, $row->file2down, $row->file3down, $row->file4down, $row->file5down);
        return $zusfeld;
    }



    // ++++++++++++++++++++++++++++++++++++++
    // +++ Bestaetigungs-Emails versenden +++         sem_f050
    // ++++++++++++++++++++++++++++++++++++++

    public static function sendBookingConfirmationMail($cid, $uid, $art, $cancel = false)
    {

        jimport('joomla.mail.helper');
        $mainframe = JFactory::getApplication();

        if (MatukioHelperSettings::getSettings('sendmail_teilnehmer', 1) > 0
            OR MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0) {

            $database = &JFactory::getDBO();
            $database->setQuery("SELECT * FROM #__matukio WHERE id = '" . $cid . "'");

            $event = $database->loadObject();

            if(!$cancel) {
                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id = " . $uid );
            } else {
                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid = " . $cid . " AND userid = " . $uid );
            }
            $booking = $database->loadObject();

            if ($booking->userid == 0) {
                $user->name = $booking->name;
                $user->email = $booking->email;
            } else {
                $user = &JFactory::getuser($booking->userid);
            }

            $publisher = &JFactory::getuser($event->publisher);

            $body1 = "<p><span style=\"font-size:10pt;\">" . JTEXT::_('COM_MATUKIO_PLEASE_DONT_ANSWER_THIS_EMAIL') . "</span><p>";
            $body2 = $body1;
            $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($event);
            $gebucht = $gebucht->booked;
            switch ($art) {
                case 1:
                    if ($gebucht > $event->maxpupil) {
                        if ($event->stopbooking = 0) {
                            $body1 .= JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
                        } else {
                            $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_BOOKED_EVENT_FOR_YOU') . " " . JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
                        }
                    } else {
                        $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_BOOKED_EVENT_FOR_YOU');
                    }
                    $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_MADE_FOLLOWING_RESERVATION');
                    break;
                case 2:
                    $body1 .= JTEXT::_('COM_MATUKIO_YOU_HAVE_CANCELLED');
                    $body2 .= JTEXT::_('COM_MATUKIO_BOOKING_FOR_EVENT_CANCELLED');
                    break;
                case 3:
                    $body1 .= JTEXT::_('COM_MATUKIO_BOOKING_CANCELED');
                    $body2 .= JTEXT::_('COM_MATUKIO_THE_ADMIN_CANCELED_THE_BOOKING_OF_FOLLOWING');
                    break;
                case 4:
                    $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_DELETED_THE_FOLLOWING_EVENT');
                    $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_DELETED_EVENT');
                    break;
                case 5:
                    $body1 .= JTEXT::_('COM_MATUKIO_ADMIN_PUBLISHED_EVENT_YOUR_BOOKING_IS_VALID');
                    $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_PUBLISHED_EVENT_THE_BOOKING_OF_PARTICIPANTS_IS_VALID');
                    break;
                case 6:
                    $body1 .= JTEXT::_('COM_MATUKIO_THE_ADMIN_CERTIFIED_YOU');
                    $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_HAS_CERTIFICATED_FOLLOWING_PARTICIPANT');
                    if (MatukioHelperSettings::getSettings('frontend_userprintcertificate', 0) > 0) {
                        $body1 .= " " . JTEXT::_('COM_MATUKIO_YOU_CAN_PRINT_YOUR_CERTIFICATE');
                    }
                    break;
                case 7:
                    $body1 .= JTEXT::_('COM_MATUKIO_CERTIFICAT_REVOKED');
                    $body2 .= JTEXT::_('COM_MATUKIO_ADMIN_HAS_WITHDRAWN_CERTIFICATE_FOR_FOLLOWNG_PARITICIPANTS');
                    break;
                case 8:
                    if ($gebucht > $event->maxpupil) {
                        if ($event->stopbooking = 0) {
                            $body1 .= JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
                        } else {
                            $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_REGISTERED_YOU') . " " . JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
                        }
                    } else {
                        $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_REGISTERED_YOU');
                    }
                    $body2 .= JTEXT::_('COM_MATUKIO_YOU_HAVE_REGISTRED_PARTICIPANT_FOR');
                    break;
                case 9:
                    $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_HAS_REPUBLISHED_EVENT');
                    $body2 .= JTEXT::_('COM_MATUKIO_THE_BOOKING_OF_THE_PARTICIPANT_IS_VALID_AGAIN');
                    break;
                case 10:
                    $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_CANCELLED');
                    $body2 .= JTEXT::_('COM_MATUKIO_BOOKING_NO_LONGER_VALID');
                    break;
                case 11:
                    $body1 .= JTEXT::_('COM_MATUKIO_ORGANISER_UPDATED_YOUR_BOOKING');
                    $body2 .= JTEXT::_('');
                    break;

            }
            $abody = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
            $sender = $mainframe->getCfg('fromname');
            $from = $mainframe->getCfg('mailfrom');
            $htxt = "";
            if ($event->semnum != "") {
                $htxt = " " . $event->semnum;
            }
            $subject = JTEXT::_('COM_MATUKIO_EVENT') . $htxt . ": " . $event->title;
            $subject = JMailHelper::cleanSubject($subject);
            if (MatukioHelperSettings::getSettings('sendmail_teilnehmer', 1) > 0 OR $art < 11) {
                $replyname = $publisher->name;
                $replyto = $publisher->email;
                $email = $user->email;
                $body = $abody . $body1 . MatukioHelperUtilsEvents::getEmailBody($event, $booking, $user);
                //var_dump($email);
                $success = JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
            }
            if (MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0 AND $art < 11) {
                $replyname = $user->name;
                $replyto = $user->email;
                $email = $publisher->email;
                //var_dump($email);
                $body = $abody . $body2 . MatukioHelperUtilsEvents::getEmailBody($event, $booking, $user);
                $success = JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
                //die($cid . " " .  $uid . " " . $art . " " . $success);
            }
        }
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Email-Koerper ausgeben         +++        sem_f049
    // ++++++++++++++++++++++++++++++++++++++

    public static function getEmailBody($row, $buchung, $user)
    {
        $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
        $gebucht = $gebucht->booked;
        $freieplaetze = $row->maxpupil - $gebucht;
        if ($freieplaetze < 0) {
            $freieplaetze = 0;
        }
        $body = "<p>\n<table cellpadding=\"2\" border=\"0\" width=\"100%\">";
        $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_NAME') . "</b>: </td><td>" . $buchung->name . " (" . $user->name . ")" . "</td></tr>";
        $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_EMAIL') . "</b>: </td><td>" . $user->email . "</td></tr>";
        if (count($buchung) > 0) {
            $body .= "\n<tr><td><b>" . JTEXT::_('COM_MATUKIO_BOOKING_ID') . "</b>: </td><td>" . MatukioHelperUtilsBooking::getBookingId($buchung->id) . "</td></tr>";
            $body .= "\n<tr><td colspan=\"2\"><hr></td></tr>";
            $body .= "\n<tr><td colspan=\"2\"><b>" . JTEXT::_('COM_MATUKIO_ADDITIONAL_INFO') . "</b></td></tr>";
            $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);
            $zusbuch = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($buchung);
            for ($i = 0; $i < count($zusfeld[0]); $i++) {
                if ($zusfeld[0][$i] != "") {
                    $zusart = explode("|", $zusfeld[0][$i]);
                    $body .= "\n<tr><td>" . $zusart[0] . ": </td><td>" . $zusbuch[0][$i] . "</td></tr>";
                }
            }
            if ($row->nrbooked > 1) {
                $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ": </td><td>" . $buchung->nrbooked . "</td></tr>";
            }
        }
        $body .= "\n<tr><td colspan=\"2\"><hr></td></tr>";
        $body .= "\n<tr><td colspan=\"2\"><b>" . $row->title . "</b></td></tr>";
        $body .= "\n<tr><td colspan=\"2\">" . $row->shortdesc . "</td></tr>";
        if ($row->semnum != "") {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_NUMBER') . ": </td><td>" . $row->semnum . "</td></tr>";
        }
        if ($row->showbegin > 0) {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BEGIN') . ": </td><td>" . JHTML::_('date', $row->begin,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($row->showend > 0) {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_END') . ": </td><td>" . JHTML::_('date', $row->end,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($row->showbooked > 0) {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ": </td><td>" . JHTML::_('date', $row->booked,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($row->teacher != "") {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_TUTOR') . ": </td><td>" . $row->teacher . "</td></tr>";
        }
        if ($row->target != "") {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ": </td><td>" . $row->target . "</td></tr>";
        }
        $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CITY') . ": </td><td>" . $row->place . "</td></tr>";
        if (MatukioHelperSettings::getSettings('event_showinfoline', 1) > 0) {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . ": </td><td>" . $row->maxpupil . "</td></tr>";
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKINGS') . ": </td><td>" . $gebucht . "</td></tr>";
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_BOOKABLE') . ": </td><td>" . $freieplaetze . "</td></tr>";
        }
        if ($row->fees > 0) {
            $body .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_FEES') . ": </td><td>" . MatukioHelperSettings::getSettings('currency_symbol', '$') . " " . $buchung->payment_brutto;
            if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
                // $body .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
            }
            $body .= "</td></tr>";
        }
        if ($row->description != "") {
            $body .= "\n<tr><td colspan=\"2\">" . MatukioHelperUtilsEvents::getCleanedMailText($row->description) . "</td></tr>";
        }
        $body .= "</table><p>";
        $htxt = str_replace('SEM_HOMEPAGE', "<a href=\"" . JURI::root() . "\">" . JURI::root() . "</a>", JTEXT::_('COM_MATUKIO_FOR_MORE_INFO_VISIT'));
        $body .= $htxt . "</body>";
        return $body;
    }

    // ++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe saeubern                +++       sem_f066
// ++++++++++++++++++++++++++++++++++++++

    public static function getCleanedMailText($text)
    {
        preg_match_all("`\[sem_[^\]]+\](.*)\[/sem_[^\]]+\]`U", $text, $ausgabe);
        for ($i = 0; $i < count($ausgabe[0]); $i++) {
            $text = str_replace($ausgabe[0][$i], "", $text);
        }
        preg_match_all("`\{[^\}]+\}`U", $text, $ausgabe);
        for ($i = 0; $i < count($ausgabe[0]); $i++) {
            $text = str_replace($ausgabe[0][$i], "", $text);
        }
        return $text;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Kategorienliste ausgeben     +++        sem_f010
// ++++++++++++++++++++++++++++++++++++++

    public static function getCategoryListArray($catid)
    {
        jimport('joomla.database.table');
        $database = JFactory::getDBO();
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $accesslvl = 1;
        if ($reglevel >= 6) {
            $accesslvl = 3;
        } else if ($reglevel >= 2) {
            $accesslvl = 2;
        }
        $categories[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_CHOOSE_CATEGORY'));
        //  $database->setQuery( "SELECT id AS value, title AS text, image AS image FROM #__categories". " WHERE extension='".JRequest::getCmd('option')."'" );
        $database->setQuery("Select id AS value, title AS text FROM #__categories WHERE extension='com_matukio'");
        $dats = $database->loadObjectList();

        $categories = array_merge($categories, (array)$dats);
        $clist = JHTML::_('select.genericlist', $categories, 'caid', 'class="sem_inputbox" size="1"', 'value', 'text', intval($catid));
        $ilist = array();

        foreach ((array)$dats as $el) {
            $el->image = "";
            $bild = "";
            if ($el->image != "") {
                $bild->id = $el->value;
                $bild->image = $el->image;
                $ilist[] = $bild;
            }
        }
        return array($clist, $ilist);
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++
// +++ Templateliste erstellen                 +++        sem_f057
// +++++++++++++++++++++++++++++++++++++++++++++++

    public static function getTemplateListSelect($vorlage, $art)
    {
        $html = "";
        $database = JFactory::getDBO();

        $my = JFactory::getuser();
        $where = array();

        // Nur veroeffentlichte Kurse anzeigen
        $where[] = "published = '1'";
        $where[] = "pattern != ''";
        $where[] = "publisher = '" . $my->id . "'";

        // nur Kurse anzeigen, deren Kategorie fuer den Benutzer erlaubt ist
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $accesslvl = 1;
        if ($reglevel >= 6) {
            $accesslvl = 3;
        } else if ($reglevel >= 2) {
            $accesslvl = 2;
        }
        $database->setQuery("SELECT id, access FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
        $cats = $database->loadObjectList();
        $allowedcat = array();
        $allowedcat[] = 0;
        foreach ((array)$cats AS $cat) {
            if ($cat->access < $accesslvl) {
                $allowedcat[] = $cat->id;
            }
        }
        if (count($allowedcat) > 0) {
            $allowedcat = implode(',', $allowedcat);
            $where[] = "catid IN ($allowedcat)";
        }
        $database->setQuery("SELECT * FROM #__matukio"
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . "\nORDER BY pattern"
        );
        $rows = $database->loadObjectList();
        $patterns = array();
        $patterns[] = JHTML::_('select.option', '', JTEXT::_('COM_MATUKIO_CHOOSE_TEMPLATE'));
        foreach ($rows AS $row) {
            $patterns[] = JHTML::_('select.option', $row->id, $row->pattern);
        }
        $htxt = JTEXT::_('COM_MATUKIO_TEMPLATE') . ": ";
        $disabled = "";
        if ($vorlage == 0) {
            $disabled = " disabled";
        }
        if ($art == 1) {
            if (count($patterns) > 1) {
                $htxt .= JHTML::_('select.genericlist', $patterns, 'vorlage', 'class="sem_inputbox" size="1"
                    onChange="form.cid.value=form.vorlage.value;form.task.value=9;form.submit();"', 'value', 'text', $vorlage);
                $htxt .= " <button class=\"button\" id=\"tmpldel\" style=\"cursor:pointer;\" type=\"button\"
                    onclick=\"form.cid.value=form.vorlage.value;form.task.value=11;form.submit();\"" . $disabled . ">
                    <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "1516.png\" border=\"0\"
                    align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_DELETE') . "</button>";
            } else {
                $htxt .= "<input type=\"hidden\" name=\"vorlage\" value=\"0\">";
            }
            $htxt .= " <input type=\"text\" name=\"pattern\" id=\"pattern\" class=\"sem_inputbox\" value=\"\"
            onKeyup=\"if(this.value=='') {form.tmplsave.disabled=true;} else {form.tmplsave.disabled=false;}\">";
            $htxt .= " <button class=\"button\" id=\"tmplsave\" style=\"cursor:pointer;\" type=\"button\"
                onclick=\"form.task.value=10;form.submit();\" disabled><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "1416.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_SAVE') . "</button>";
            $html = "<tr>" . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', '80%', 'sem_nav', 2) . "</tr>";
        } else if ($art == 2) {
            if (count($patterns) > 1) {
                $htxt .= JHTML::_('select.genericlist', $patterns, 'vorlage', 'class="sem_inputbox" size="1"
                onChange="form.id.value=form.vorlage.value;form.task.value=\'12\';form.submit();"', 'value', 'text', $vorlage);
                $html = "<tr>" . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'c', '80%', 'sem_nav', 2) . "</tr>";
            }
        }
        return $html;
    }

    // ++++++++++++++++++++++++++++++++++++++
// +++ Veranstalterliste ausgeben     +++     sem_f009
// ++++++++++++++++++++++++++++++++++++++

    public static function getOranizerList($pub)
    {
        // TODO update
        $publevel = MatukioHelperSettings::getSettings('frontend_createevents', 0); //SettingsHelper::getSettings('frontend_createevents', 0);
        $database = &JFactory::getDBO();
        $where = array();
        $where [] = "usertype<>'Registered'";
        if ($publevel > 3) {
            $where [] = "usertype<>'Author'";
        } else if ($publevel > 4) {
            $where [] = "usertype<>'Editor'";
        } else if ($publevel > 5) {
            $where [] = "usertype<>'Publisher'";
        } else if ($publevel > 6) {
            $where [] = "usertype<>'Manager'";
        } else if ($publevel > 7) {
            $where [] = "usertype<>'Administrator'";
        }
        $database->setQuery("SELECT id AS value, name AS text FROM #__users"
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . "\nORDER BY name"
        );
        $benutzer = $database->loadObjectList();
        return JHTML::_('select.genericlist', array_merge($benutzer), 'publisher', 'class="sem_inputbox" size="1"',
            'value', 'text', $pub);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++
// +++ Editierbereich der Seminare ausgeben +++        sem_f008
// ++++++++++++++++++++++++++++++++++++++++++++

    public static function getEventEdit($row, $art)
    {
        jimport('joomla.database.table');
        jimport('joomla.html.pane');
        $database = &JFactory::getDBO();
        $editor = &JFactory::getEditor();
        $catlist = MatukioHelperUtilsEvents::getCategoryListArray($row->catid);
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $reqfield = " <span class=\"sem_reqfield\">*</span>";

        // Vorlage
        $html = "";
        if ($art == 1 OR $art == 2) {
            $html = "<input type=\"hidden\" name=\"pattern\" value=\"\"><input type=\"hidden\" name=\"vorlage\" value=\"0\">";
        }
        if ($row->id == 0 AND ($art == 1 OR $art == 2)) {
            $html = MatukioHelperUtilsEvents::getTemplateListSelect($row->vorlage, $art);
        }
        $html .= "<tr><td width=\"100%\">";

        $pane =& JPane::getInstance('sliders', array('allowAllClose' => true));
        $html .= $pane->startPane('pane');

        // ### Panel 1 ###

        $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_BASIC_SETTINGS'), 'panel1');
        $html .= "<table>";
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_SETTINGS_NEEDED'), 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";

        // Vorlagenname und Besitzer
        if ($art == 3) {
            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TEMPLATE') . ':', 'd', 'r', '20%', 'sem_edit')
                . MatukioHelperUtilsEvents::getTableCell(
                "<input class=\"sem_inputbox\" type=\"text\" name=\"pattern\" size=\"50\" maxlength=\"100\"
        value=\"" . $row->pattern . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_OWNER') . ':', 'd', 'r', '20%', 'sem_edit')
                . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsEvents::getOranizerList($row->publisher) . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
            $reqfield = "";
        }

        // ID der Veranstaltung
        if ($row->id < 1) {
            $htxt = JTEXT::_('COM_MATUKIO_ID_NOT_CREATED');
            $htx2 = JTEXT::_('COM_MATUKIO_SHOULD_REGISTERED_USERS_RECEIVE_MAIL');
            $htx3 = JTEXT::_('COM_MATUKIO_NEW_EVENT_PUBLISHED_INTERESTED_SEE_HOMEPAGE');
            $htx4 = "";
            $htx5 = " checked=\"checked\"";
        } else {
            $htxt = $row->id;
            $htx2 = JTEXT::_('COM_MATUKIO_INFORM_PER_EMAIL');
            $htx3 = JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED');
            if ($row->cancelled == 0) {
                $htx4 = "";
                $htx5 = " checked=\"checked\"";
                if ($art != 3) {
                    $htx4 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_ORGANISER_CANCELLED') . "'\"";
                    $htx5 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED') . "'\"" . $htx5;
                }
            } else {
                $htx4 = " checked=\"checked\"";
                $htx5 = "";
                if ($art != 3) {
                    $htx4 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_EVENTS_DATAS_CHANGED') . "'\"" . $htx4;
                    $htx5 = " onClick=\"infotext.value='" . JTEXT::_('COM_MATUKIO_ORGANISER_HAS_REPUBLISHED_EVENT') . "'\"";
                }
            }
        }
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_ID') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_AUTO_ID')), 'd', 'r', '20%', 'sem_edit');
        $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";


        // Kursnummer
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_NUMBER') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_UNIQUE_NUMBER')), 'd', 'r', '20%', 'sem_edit');
        $html .= MatukioHelperUtilsEvents::getTableCell("<input class=\"sem_inputbox\" type=\"text\"
            name=\"semnum\" size=\"10\" maxlength=\"100\" value=\"" . $row->semnum . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Abgesagt
        $htxt = "<input type=\"radio\" name=\"cancel\" id=\"cancel\" value=\"1\" class=\"sem_inputbox\"" . $htx4
            . " /><label for=\"cancel\">" . JTEXT::_('COM_MATUKIO_YES') . "</label> <input type=\"radio\"
            name=\"cancel\" id=\"cancel\" value=\"0\" class=\"sem_inputbox\"" . $htx5 . "/><label for=\"cancel\">"
            . JTEXT::_('COM_MATUKIO_NO') . "</label>";
        $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CANCELLED') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_CANCELLED_EVENT_NO_BOOKINGS')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "<input type=\"hidden\"
            name=\"cancelled\" value=\"" . $row->cancelled . "\"></tr>";

        // Titel
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TITLE') . ':', 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell("<input class=\"sem_inputbox\" type=\"text\" name=\"title\" size=\"50\"
            maxlength=\"250\" value=\"" . $row->title . "\" />" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Kategorie
        $htxt = $catlist[0];
        if (MatukioHelperSettings::getSettings('event_image', 1) == 1) {
            foreach ($catlist[1] as $el) {
                $htxt .= "<input type=\"hidden\" id=\"im" . $el->id . "\" value=\"" . $el->image . "\">";
            }
        }
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CATEGORY') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_EVENT_ASSIGNED_CATEGORY')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        $radios = array();
        $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_YES'));
        $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_NO'));

        // Veranstaltungsbeginn
        $htxt = JHTML::_('calendar', JHtml::_('date',$row->begin, 'Y-m-d H:i:s'), '_begin_date', '_begin_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '22'));
        $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist', $radios, 'showbegin', 'class="sem_inputbox"', 'value', 'text', $row->showbegin);
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BEGIN') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Veranstaltungsende
        $htxt = JHTML::_('calendar', JHtml::_('date',$row->end, 'Y-m-d H:i:s'), '_end_date', '_end_date', '%Y-%m-%d %H:%M:%S',
            array('class' => 'inputbox', 'size' => '22'));
        $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist', $radios, 'showend',
            'class="sem_inputbox"', 'value', 'text', $row->showend);
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_END') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Anmeldeschluss
        $htxt = JHTML::_('calendar', JHtml::_('date',$row->booked, 'Y-m-d H:i:s'), '_booked_date', '_booked_date',
            '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '22', 'filter' => 'USER_UTC'));
        $htxt .= $reqfield . " - " . JTEXT::_('COM_MATUKIO_DISPLAY') . " " . JHTML::_('select.radiolist',
            $radios, 'showbooked', 'class="sem_inputbox"', 'value', 'text', $row->showbooked);
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_DATE_TIME_FORMAT')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Kurzbeschreibung
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_BRIEF_DESCRIPTION') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_BRIEF_DESCRIPTION_DESC')), 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell("<textarea class=\"sem_inputbox\" cols=\"50\" rows=\"3\" name=\"shortdesc\"
            style=\"width:500px\" width=\"500\">" . $row->shortdesc . "</textarea>" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Veranstaltungsort
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_CITY') . ':', 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell("<textarea class=\"sem_inputbox\" cols=\"50\" rows=\"3\" name=\"place\"
            style=\"width:500px\" width=\"500\">" . $row->place . "</textarea>" . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Veranstalter
        if ($reglevel > 5 AND $art != 3) {
            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_ORGANISER') . ':'
                . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_ORGANISER_MANAGE_FRONTEND')), 'd', 'r', '20%', 'sem_edit')
                . MatukioHelperUtilsEvents::getTableCell(MatukioHelperUtilsEvents::getOranizerList($row->publisher) . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
        }

        // Plätze
        $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"maxpupil\" size=\"3\" maxlength=\"5\" value=\""
            . $row->maxpupil . "\" /> - " . JTEXT::_('COM_MATUKIO_IF_FULLY_BOOKED') . ": ";
        $radios = array();
        $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_WAITLIST'));
        $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_END_BOOKING'));
        $radios[] = JHTML::_('select.option', 2, JTEXT::_('COM_MATUKIO_HIDE_EVENT'));
        $htxt .= JHTML::_('select.genericlist', $radios, 'stopbooking', 'class="sem_inputbox" ', 'value', 'text', $row->stopbooking);

        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT') . ':', 'd', 'r', '20%', 'sem_edit')
            . MatukioHelperUtilsEvents::getTableCell($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // max. Buchung
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_MAX_BOOKABLE_PLACES') . ':'
            . MatukioHelperUtilsBasic::createToolTip(JTEXT::_('COM_MATUKIO_CANNOT_BOOK_ONLINE')), 'd', 'r', '20%', 'sem_edit');

        $bookableplaces = $row->nrbooked;

        if(empty($bookableplaces)) {
            $bookableplaces = 1;
        }

        if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 2) > 0) {
            $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"nrbooked\" size=\"3\" maxlength=\"3\" value=\""
                . $bookableplaces . "\" />";
        } else {
            $radios = array();
            $radios[] = JHTML::_('select.option', 0, "0");
            $radios[] = JHTML::_('select.option', 1, "1");
            $htxt = JHTML::_('select.genericlist', $radios, 'nrbooked', 'class="sem_inputbox" ', 'value', 'text', $row->nrbooked);
        }
        $html .= MatukioHelperUtilsEvents::getTableCell($htxt . $reqfield, 'd', 'l', '80%', 'sem_edit') . "</tr>";
        $html .= "</table>";
        $html .= $pane->endPanel();

        // ### Panel 2 ###

        $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_ADDITIONAL_SETTINGS'), 'panel2');
        $html .= "<table>";
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_ADDITIONAL_SETTINGS_DESC'), 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";

        // Beschreibung
        $name = "editor1";
        $htxt = $editor->display("description", $row->description, "500", "300", "50", "5");
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_DESCRIPTION') . ':', 'd', 'r', '20%', 'sem_edit') . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_USE_FOLLOWING_TAGS') . $htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Veranstaltungsbild
        if (MatukioHelperSettings::getSettings('event_image', 1) == 1) {
            jimport('joomla.filesystem.folder');
            $htxt = "";
            if (MatukioHelperSettings::getSettings('image_path', '') != "") {
                $htxt = trim(MatukioHelperSettings::getSettings('image_path', ''), "/") . "/";
            }
            $htxt = JPATH_SITE . "/images/" . $htxt;
            if (!is_dir($htxt)) {
                mkdir($htxt, 0755);
            }
            $imageFiles = JFolder::files($htxt);
            $images = array(JHTML::_('select.option', '', '- ' . JText::_('COM_MATUKIO_STANDARD_IMAGE') . ' -'));
            foreach ($imageFiles as $file) {
                if (preg_match("/jpg|png|gif/i", $file)) {
                    $images[] = JHTML::_('select.option', $file);
                }
            }
            $imagelist = JHTML::_('select.genericlist', $images, 'image', 'class="sem_inputbox" size="1" ', 'value', 'text', $row->image);
            $htxt = "<span style=\"position:absolute;display:none;border:3px solid #FF9900;background-color:#FFFFFF;\" id=\"1\"><img id=\"toolbild\"
        src=\"images/stories/" . $row->image . "\" \></span><span style=\"position:absolute;display:none;border:3px solid #FF9900;background-color:#FFFFFF;\"
        id=\"2\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2601.png\" \></span>";
            $htxt .= $imagelist . "&nbsp;<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "2116.png\" border=\"0\" onmouseover=\"showSemTip('1');\" onmouseout=\"hideSemTip();\" />";
            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_IMAGE_FOR_OVERVIEW') . ':', 'd', 'r', '20%', 'sem_edit')
                . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
        }

        // Google-Map

            $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"gmaploc\" size=\"50\" maxlength=\"250\" value=\"" . $row->gmaploc . "\" /> ";
            $actform = "FrontForm";
            $gmaphref = JURI::BASE();
            if (strstr($gmaphref, "/administrator")) {
                $actform = "adminForm";
            }

            // TODO implement map function with js
//            $maplink = JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&event_id='.$this->event->id);
//
//            $htxt .= "<a href=\"\" title=\"" . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "\" class=\"modal\" onclick=\"href='"
//                . MatukioHelperUtilsBasic::getComponentPath() . "/matukio.gmap.php?key=" . MatukioHelperSettings::getSettings('googlemap_apicode', '') .
//                "&amp;iw=" . MatukioHelperSettings::getSettings('googlemap_booble', 1) . "&amp;ziel=' + unescape(document." . $actform . ".gmaploc.value)
//                 + '&amp;ort=' + unescape(document." . $actform . ".place.value.replace(/\\n/gi, '<br />'));\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\">"
//                . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "</a>";
//
//            $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "\" class=\"modal\" href=\""
//                . JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&ort='. $this->event->id )
//                . "\" rel=\"{handler: 'iframe', size: {x: 500, y: 350}}\">" . JTEXT::_('COM_MATUKIO_TEST_GMPAS') . "</a>";

            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_GMAPS_LOCATION') . ':', 'd', 'r', '20%', 'sem_edit')
                . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";


        // Leitung
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TUTOR') . ':', 'd', 'r', '20%', 'sem_edit') . MatukioHelperUtilsEvents::getTableCell("<input class=\"sem_inputbox\" type=\"text\" name=\"teacher\" size=\"50\" maxlength=\"250\" value=\"" . $row->teacher . "\" />", 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Zielgruppe
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_TARGET_GROUP') . ':', 'd', 'r', '20%', 'sem_edit') . MatukioHelperUtilsEvents::getTableCell("<input class=\"sem_inputbox\" type=\"text\" name=\"target\" size=\"50\" maxlength=\"500\" value=\"" . $row->target . "\" />", 'd', 'l', '80%', 'sem_edit') . "</tr>";

        // Gebuehr
        $htxt = MatukioHelperSettings::getSettings('currency_symbol', '$') . "&nbsp;<input class=\"sem_inputbox\" type=\"text\" name=\"fees\" size=\"8\" maxlength=\"10\" value=\"" . $row->fees . "\" />";
        if (MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 2) > 0) {
            $htxt .= " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');
        }
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FEES') . ':', 'd', 'r', '20%', 'sem_edit') . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
        $html .= "</table>";
        $html .= $pane->endPanel();

        // ### Panel 3 ###

        $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_GENERAL_INPUT_FIELDS'), 'panel3');
        $html .= "<table>";
        $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FILLED_IN_ONCE') . "<br />&nbsp;<br />" . JTEXT::_('COM_MATUKIO_FIELD_INPUT_SPECIFIED') . "<br />&nbsp;<br />" . JTEXT::_('COM_MATUKIO_FIELD_TIPS_SPECIFIED') . "<br />&nbsp;<br />", 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";

        // Zusatzfelder
        $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($row);
        if(!empty($zusfeld)) {
            for ($i = 0; $i < count($zusfeld[0]); $i++) {
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_INPUT') . " " . ($i + 1)
                    . ":", 'd', 'r', '20%', 'sem_edit');
                $htxt = "<input class=\"sem_inputbox\" type=\"text\" name=\"zusatz" . ($i + 1) . "\" size=\"50\" value=\""
                    . $zusfeld[0][$i] . "\" />";
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;", 'd', 'r', '20%', 'sem_edit');

                if(!empty($zusfeld[1])){
                    $htxt = JTEXT::_('COM_MATUKIO_FIELD_TIP') . ": <input class=\"sem_inputbox\" type=\"text\" name=\"zusatz"
                    . ($i + 1) . "hint\" size=\"50\" maxlength=\"250\" value=\"" . $zusfeld[1][$i] . "\" />";
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                }
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;", 'd', 'r', '20%', 'sem_edit');


                $radios = array();
                $radios[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_YES'));
                $radios[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_NO'));
                $htxt = str_replace("SEM_FNUM", $i + 1, JTEXT::_('COM_MATUKIO_DISPLAY_SEM_FNUM'));
                if(!empty($zusfeld[2])){
                    $htxt = $htxt . " " . JHTML::_('select.radiolist', $radios, 'zusatz' . ($i + 1) . 'show', 'class="sem_inputbox" ',
                        'value', 'text', $zusfeld[2][$i]);
                    $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                    }
            }
        }
        $html .= "</table>";
        $html .= $pane->endPanel();

        // ### Panel 5 ###
        if (MatukioHelperSettings::getSettings('file_maxsize', 500) > 0) {
            $html .= $pane->startPanel(JTEXT::_('COM_MATUKIO_FILES'), 'panel4');
            $htxt = str_replace("SEM_FILESIZE", MatukioHelperSettings::getSettings('file_maxsize', 500), JTEXT::_('COM_MATUKIO_FILE_SIZE_UP_TO'));
            $htxt = str_replace("SEM_FILETYPES", strtoupper(MatukioHelperSettings::getSettings('file_endings', 'txt pdf zip jpg')), $htxt);
            $html .= "<table>";
            $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '100%', 'sem_edit', 2) . "</tr>";
            $datfeld = MatukioHelperUtilsEvents::getEventFileArray($row);
            $select = array();
            $select[] = JHTML::_('select.option', 0, JTEXT::_('COM_MATUKIO_EVERYONE'));
            $select[] = JHTML::_('select.option', 1, JTEXT::_('COM_MATUKIO_REGISTERED_USERS'));
            $select[] = JHTML::_('select.option', 2, JTEXT::_('COM_MATUKIO_USERS_BOOKED_EVENT'));
            $select[] = JHTML::_('select.option', 3, JTEXT::_('COM_MATUKIO_USERS_PAID_FOR_EVENT'));
            for ($i = 0; $i < count($datfeld[0]); $i++) {
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_FILE') . " " . ($i + 1) . ":", 'd', 'r', '20%', 'sem_edit');
                if ($datfeld[0][$i] != "") {
                    $htxt = "<b>" . $datfeld[0][$i] . "</b> - <input class=\"sem_inputbox\" type=\"checkbox\" name=\"deldatei" . ($i + 1) . "\"
                        value=\"1\" onClick=\"if(this.checked==true) {datei" . ($i + 1) . ".disabled=true;} else {datei" . ($i + 1) . ".disabled=false;}\"> "
                        . JTEXT::_('COM_MATUKIO_DELETE_FILE');
                    $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                    $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;", 'd', 'r', '20%', 'sem_edit');
                }
                $htxt = "<input class=\"sem_inputbox\" name=\"datei" . ($i + 1) . "\" type=\"file\">";
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;", 'd', 'r', '20%', 'sem_edit');
                $htxt = JTEXT::_('COM_MATUKIO_DESCRIPTION') . ": <input class=\"sem_inputbox\" type=\"text\" name=\"file" . ($i + 1)
                    . "desc\" size=\"50\" maxlength=\"255\" value=\"" . $datfeld[1][$i] . "\" />";
                $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
                $html .= "<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;", 'd', 'r', '20%', 'sem_edit');
                $htxt = JHTML::_('select.genericlist', $select, 'file' . ($i + 1) . 'down', 'class="sem_inputbox" ', 'value', 'text', $datfeld[2][$i]);
                $html .= MatukioHelperUtilsEvents::getTableCell(JTEXT::_('COM_MATUKIO_WHO_MAY_DOWNLOAD') . " " . $htxt, 'd', 'l', '80%', 'sem_edit') . "</tr>";
            }
            $html .= "</table>";
            $html .= $pane->endPanel();
        }

        $html .= $pane->endPane();
        $html .= "\n</td></tr><tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;* " . JTEXT::_('COM_MATUKIO_REQUIRED_FIELD'), 'd', 'r', '100%', 'sem_nav', 2);

        // Benutzer informieren
        //   if($art!=3) {
        //     $html .= "</tr></td></tr>";
        //     $radios = array();
        //     $radios[] = JHTML::_('select.option',1,JTEXT::_('COM_MATUKIO_YES'));
        //     $radios[] = JHTML::_('select.option',0,JTEXT::_('COM_MATUKIO_NO'));
        //     $htx2 .= "<br />".JHTML::_('select.radiolist',$radios,'inform','class="sem_inputbox"','value','text',0);
        //     $htx2 .= "<br />".JTEXT::_('COM_MATUKIO_MESSAGE_TEXT').": <input class=\"sem_inputbox\" type=\"text\" name=\"infotext\" id=\"infotext\" size=\"70\" value=\"".$htx3."\" />";
        //     $html .= "\n<tr>".MatukioHelperUtilsEvents::getTableCell($htx2,'d','c','100%','sem_nav',2);
        //   }

        return $html;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Eingabe prüfen                 +++     sem_f067
// ++++++++++++++++++++++++++++++++++++++

    public static function checkRequiredFieldValues($text, $art = 'leer')
    {
        $htxt = false;
        switch ($art) {
// texteingabe prüfen - alle eingaben auf leere eingaben prüfen
            case 'leer':
                $text = trim($text);
                if ($text != '') {
                    $htxt = true;
                }
                break;
// auf nur zahlen prüfen
            case 'nummer':
                if (preg_match("#^[0-9]+$#", $text)) {
                    $htxt = true;
                }
                break;
// auf telefonnummer prüfen mit min. 6 zahlen
            case 'telefon':
                if (preg_match("#^[ 0-9\/-+]{6,}+$#", $text)) {
                    $htxt = true;
                }
                break;
// auf nur buchstaben prüfen
            case 'buchstabe':
                if (preg_match("/^[ a-za-zäöüß]+$/i", $text)) {
                    $htxt = true;
                }
                break;
// auf nur ein wort prüfen
            case 'wort':
                if (preg_match("/^[a-za-zäöüß]+$/i", $text)) {
                    $htxt = true;
                }
                break;
// url prüfen
            case 'url':
                $text = trim($text);
                if (preg_match("#^(http|https)+(://www.)+([a-z0-9-_.]{2,}\.[a-z]{2,4})$#i", $text)) {
                    $htxt = true;
                }
                break;
// email-adresse prüfen
            case 'email':
                $text = trim($text);
                if ($text != '') {
                    $_pat = "^[_a-za-z0-9-]+(.[_a-za-z0-9-]+)*@([a-z0-9-]{3,})+.([a-za-z]{2,4})$";
                    if (!preg_match("|$_pat|i", $text)) {
                        $htxt = false;
                    }
                } else {
                    $htxt = false;
                }
                break;
// Zahl der Laenge art pruefen
            default:
                if (preg_match("/^[0-9]{$art}$/", $cvalue)) {
                    $htxt = true;
                }
                break;
        }
        return $htxt;
    }

    // ++++++++++++++++++++++++++++++++++++++++
// +++ Konstanten in Text austauschen   +++      sem_f054
// ++++++++++++++++++++++++++++++++++++++++

    public static function replaceSEMConstants($html, $row, $user)
    {

        $neudatum = MatukioHelperUtilsDate::getCurrentDate();

        $html = str_replace('SEM_IMAGEDIR', MatukioHelperUtilsBasic::getComponentImagePath(), $html);

        $html = str_replace('SEM_BEGIN_EXPR', JTEXT::_('COM_MATUKIO_BEGIN'), $html);
        $html = str_replace('SEM_END_EXPR', JTEXT::_('COM_MATUKIO_END'), $html);
        $html = str_replace('SEM_LOCATION_EXPR', JTEXT::_('COM_MATUKIO_CITY'), $html);
        $html = str_replace('SEM_TUTOR_EXPR', JTEXT::_('COM_MATUKIO_TUTOR'), $html);
        $html = str_replace('SEM_DATE_EXPR', JTEXT::_('COM_MATUKIO_DATE'), $html);
        $html = str_replace('SEM_TIME_EXPR', JTEXT::_('COM_MATUKIO_TIME'), $html);

        $html = str_replace('SEM_COURSE', $row->title, $html);
        $html = str_replace('SEM_TITLE', $row->title, $html);
        $html = str_replace('SEM_COURSENUMBER', $row->semnum, $html);
        $html = str_replace('SEM_NUMBER', $row->semnum, $html);
        $html = str_replace('SEM_ID', $row->id, $html);
        $html = str_replace('SEM_LOCATION', $row->place, $html);
        $html = str_replace('SEM_TEACHER', $row->teacher, $html);
        $html = str_replace('SEM_TUTOR', $row->teacher, $html);

        $html = str_replace('SEM_BEGIN', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_BEGIN_OVERVIEW', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_BEGIN_DETAIL', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_BEGIN_LIST', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_BEGIN_DATE', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
        $html = str_replace('SEM_BEGIN_TIME', JHTML::_('date', $row->begin, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
        $html = str_replace('SEM_END', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_END_OVERVIEW', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_END_DETAIL', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_END_LIST', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_END_DATE', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
        $html = str_replace('SEM_END_TIME', JHTML::_('date', $row->end, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
        $html = str_replace('SEM_TODAY', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
        $html = str_replace('SEM_NOW', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);
        $html = str_replace('SEM_NOW_OVERVIEW', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_NOW_DETAIL', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_NOW_LIST', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')), $html);
        $html = str_replace('SEM_NOW_DATE', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y')), $html);
        $html = str_replace('SEM_NOW_TIME', JHTML::_('date', $neudatum, MatukioHelperSettings::getSettings('time_format', 'H:i')), $html);

        $html = str_replace('SEM_NAME', $user->name, $html);
        $html = str_replace('SEM_EMAIL', $user->email, $html);

        return $html;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++
// +++ Name und Beschreibung der Kategorie ausgeben      sem_f012
// ++++++++++++++++++++++++++++++++++++++++++++++++

    public static function getCategoryDescriptionArray($catid)
    {
        $database = &JFactory::getDBO();
        $database->setQuery("Select * FROM #__categories WHERE extension='com_matukio' AND id = '$catid'");
        $rows = $database->loadObjectList();
        return array($rows[0]->title, $rows[0]->description);
    }


    public static function getAdditionalFieldValue($field, $bookingid){
        $database = &JFactory::getDBO();
        $database->setQuery("Select id, " . $field . " FROM #__matukio_bookings WHERE  id = '" . $bookingid . "'");
        $row = $database->loadObject();
        return $row;
    }


    // +++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe des Prozentbalkens             sem_f013
// +++++++++++++++++++++++++++++++++++++++

    function getProcentBar($max, $frei, $art)
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


    /**
     * @static
     * @param $link
     * @return string
     */
    public static function getRoutedLink($link){
        $db =& JFactory::getDBO();
        //$lang =& JFactory::getLanguage()->getTag();
        $uri = 'index.php?option=com_matukio&view=eventlist';
        //echo $lang;

        $db->setQuery('SELECT id FROM #__menu WHERE link LIKE '. $db->Quote( $uri .'%' ) .' AND published = 1 LIMIT 1' );

        $itemId = ($db->getErrorNum())? 0 : intval($db->loadResult());

        $link = $link . "&Itemid=" . $itemId;
        // Routing of a link
        $link = JRoute::_($link);

        return $link;
    }
    
}
