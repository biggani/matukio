<?php
/**
 * Matukio - Frontend
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

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

if(!defined('CJOOMLA_VERSION')) {
    if(substr(JVERSION, 0, 1) == 3) {
        define('CJOOMLA_VERSION', 3);
    } else {
        define('CJOOMLA_VERSION', 2);
    }
}

require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/helper/defines.php');

JLoader::register('MatukioHelperSettings', JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');
JLoader::register('MatukioHelperUtilsBasic', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_basic.php');
JLoader::register('MatukioHelperUtilsBooking', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_booking.php');
JLoader::register('MatukioHelperUtilsDate', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_date.php');
JLoader::register('MatukioHelperUtilsEvents', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_events.php');
JLoader::register('MatukioHelperRoute', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_route.php');
JLoader::register('MatukioHelperCategories', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_categories.php');
JLoader::register('MatukioHelperPayment', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_payment.php');


// thank you for this black magic Nickolas :)
// Magic: merge the eventlist translation with the current translation
$jlang = JFactory::getLanguage();
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, null, true);
$jlang->load('com_matukio', JPATH_SITE, 'en-GB', true);
$jlang->load('com_matukio', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('com_matukio', JPATH_SITE, null, true);

$input=JFactory::getApplication()->input;


JTable::addIncludePath(JPATH_ADMINISTRATOR .  '/components/com_matukio/tables');

// Get the view and controller from the request, or set to eventlist if they weren't set
$input->set('controller', $input->get('view','eventlist')); // Black magic: Get controller based on the selected view

// Require specific controller if requested
if ($controller = $input->get('controller')) {
    $path = JPATH_COMPONENT . '/controllers/' .  $controller . '.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if ($controller == '') {
    require_once(JPATH_COMPONENT .'/controllers/eventlist.php');
    $controller = 'eventlist';
}

// Create the controller
$classname = 'MatukioController' . $controller;
$controller = new $classname( );
$controller->execute($input->get('task'));
$controller->redirect();


return;

// Old stuff - not used anymore!!111  PARTY!11
require_once(JApplicationHelper::getPath('front_html'));
require_once(JApplicationHelper::getPath('class'));
$task = trim($input->getInt('task', 0));
jimport('joomla.database.table');
jimport('joomla.methods');

if ($task != 25 AND $task != 31 AND $task != 33) {
    header("Content-type: text/html; charset=UTF-8");
}

if (($task > 14 AND $task < 23) OR $task == 27 OR $task == 30) {
    echo sem_f031();
} elseif ($task != 25 AND $task != 31 AND $task != 33) {
    $document = JFactory::getDocument();
    $document->addCustomTag(sem_f030());
}

// ++++++++++++++++++++++++++++++++++
// +++ Auswahl der Aktion         +++
// ++++++++++++++++++++++++++++++++++


switch ($task) {

    case "0":
// Veranstaltungen zeigen      --done
        loginUser();
        sem_g001(0);
        break;

    case "1":
    // Gebuchte Kurse zeigen         --done
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g001(1);
        break;

    case "2":
// Angebotene Kurse zeigen                 --done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g001(2);
        break;

    case "3":
// Kursdetails anzeigen   --done
        loginUser();
        sem_g002(0);
        break;

    case "4":
// Details eines gebuchten Kurses zeigen  --done
        MatukioHelperUtilsBasic::checkUserLevel(2);  //
        sem_g002(1);
        break;

    case "5":
// Veranstaltung buchen --done
        MatukioHelperUtilsBasic::checkUserLevel(1);
        sem_g004();
        break;

    case "6":
// Buchung stornieren        -- done
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g005();
        break;

    case "7":
// Buchung durch den Veranstalter stornieren    -- half done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g011();
        break;

    case "8":
// Neue Veranstaltung eingeben       -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g006();
        break;

    case "9":
// Veranstaltung bearbeiten         --done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g006();
        break;

    case "10":
// Veranstaltung speichern        --done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g007();
        break;

    case "11":
// Veranstaltung entfernen           -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g008();
        break;

    case "12":
// Veranstaltung duplizieren     -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g009();
        break;

    case "13":
// Benutzer zertifizieren   -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g013();
        break;

    case "14":
// Buchung als bezahlt markieren    --done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g012();
        break;

    case "15":
// Uebersichten ausdrucken     -- done
//    MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g018();
        break;

    case "16":
// Zertifikat drucken    -- implemented in print and printeventlist view but haven't changed the view ! TODO testing
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g019();
        break;

    case "17":
// Teilnehmerliste als Unterschiftsliste drucken   -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_f052(1);
        break;

    case "18":
// Teilnehmerliste mit Detailangaben drucken               -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_f052(2);
        break;

    case "19":
// Veranstalter eine E-Mail senden    --done view
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g016(1);
        break;

    case "22":
// E-Mail an Veranstalter absenden und Bestaetigung anzeigen   --done controller
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g017();
        break;

    case "20":
// Veranstalter bewerten    -- done
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g014();
        break;

    case "21":
// Bewertung in die Datenbank eintragen und Ajax schliessen     -- done
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g015();
        break;

    case "23":
// Teilnehmer eines Kurses anzeigen        -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g010(2);
        break;

    case "24":
// Teilnehmer eines Kurses anzeigen         -- done
        sem_g010(1);
        break;

    case "25":
// Buchungsliste als CSV herunterladen       -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_f048();
        break;

    case "26":
// Buchungsdaten aendern                -- done
        MatukioHelperUtilsBasic::checkUserLevel(2);
        sem_g003(1);
        break;

    case "27":
// AGB anzeigen            -- done
        sem_g020();
        break;

    case "28":
// Details eines gebuchten Kurses zeigen --done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g002(3);
        break;

    case "29":
// Buchungsdaten eines users aendern    -hmm ?!?!      changebooking task in event
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g003(2);
        break;

    case "30":
// Teilnehmern eine E-Mail senden  -- done
        MatukioHelperUtilsBasic::checkUserLevel(MatukioHelperSettings::getSettings('frontend_createevents', 0));
        sem_g016(2);
        break;

    case "31":
// RSS-Feed erzeugen  -- done
        sem_g023();
        break;

    case "32":
// Benutzer ausloggen     -- done / removed -> to old tasks matukio controller
        sem_g024();
        break;

    case "33":
// Veranstaltung als ICS herunterladen         -- done
        sem_f059();
        break;

    case "34":
// Datei herunterladen     -- done
        sem_f061();
        break;

    default:
        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
        exit();
        break;
}
echo "\n</td></tr></table><br />" . showCopyright() . "</form>";

// ++++++++++++++++++++++++++++++++++++
// +++ Anzeige der Kursuebersichten +++
// ++++++++++++++++++++++++++++++++++++
//
//function sem_g001($art)
//{
//    $database = JFactory::getDBO();
//    $dateid = JRequest::getInt('dateid', 1);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getString('search', '');
//    $search = str_replace("'", "", $search);
//    $search = str_replace("\"", "", $search);
//    $limit = JRequest::getInt('limit', MatukioHelperSettings::getSettings('event_showanzahl', 10));
//    $limitstart = JRequest::getInt('limitstart', 0);
//    $my = JFactory::getuser();
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    $where = array();
//
//    // Nur veroeffentlichte Kurse anzeigen
//    $where[] = "a.published = '1'";
//    $where[] = "a.pattern = ''";
//
//    // nur Kurse anzeigen, deren Kategorie fuer den Benutzer erlaubt ist
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $accesslvl = 1;
//    if ($reglevel > 2) {
//        $accesslvl = 3;
//    } elseif ($reglevel > 1) {
//        $accesslvl = 2;
//    }
//    $database->setQuery("SELECT id, access FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
//    $cats = $database->loadObjectList();
//    $allowedcat = array();
//    foreach ((array)$cats AS $cat) {
//        if ($cat->access < $accesslvl) {
//            $allowedcat[] = $cat->id;
//        }
//    }
//    if (count($allowedcat) > 0) {
//        $allowedcat = implode(',', $allowedcat);
//        $where[] = "a.catid IN ($allowedcat)";
//    }
//    switch ($art) {
//        case "0":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavshowmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//        case "1":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavbookingmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//        case "2":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavoffermodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//    }
//    switch (MatukioHelperSettings::getSettings('event_stopshowing', 2)) {
//        case "0":
//            $showend = "a.begin";
//            break;
//        case "1":
//            $showend = "a.booked";
//            break;
//        default:
//            $showend = "a.end";
//            break;
//    }
//    if (in_array('SEM_TYPES', $navioben)) {
//        switch ($dateid) {
//            case "1":
//                $where[] = "$showend > '$neudatum'";
//                break;
//            case "2":
//                $where[] = "$showend <= '$neudatum'";
//                break;
//        }
//    }
//    switch ($art) {
//        case "0":
//
//// Gesamte Kurse anzeigen
//            if (!in_array('SEM_TYPES', $navioben)) {
//                $where[] = "$showend > '$neudatum'";
//            }
//            if ((isset($_GET["catid"]) OR in_array('SEM_CATEGORIES', $navioben)) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            $leftjoin = "";
//            $bookdate = "";
//            $anztyp = array(JTEXT::_('COM_MATUKIO_EVENTS'), 0);
//            break;
//        case "1":
//
//// Gebuchte Kurse anzeigen
//            if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            $where[] = "cc.userid = '" . $my->id . "'";
//            $leftjoin = "\n LEFT JOIN #__matukio_bookings AS cc ON cc.semid = a.id";
//            $bookdate = ", cc.bookingdate AS bookingdate, cc.id AS sid";
//            $anztyp = array(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), 1);
//            break;
//
//        case "2":
//
//// Eingestellte Kurse anzeigen
//            if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            if (MatukioHelperUtilsBasic::getUserLevel() < 6) {
//                $where[] = "a.publisher = '" . $my->id . "'";
//            }
//            $leftjoin = "";
//            $bookdate = "";
//            $anztyp = array(JTEXT::_('COM_MATUKIO_MY_OFFERS'), 2);
//            break;
//    }
//    $suche = "\nAND (a.semnum LIKE '%" . $search . "%' OR a.gmaploc LIKE '%" . $search . "%' OR a.target LIKE '%" . $search . "%' OR a.place LIKE '%" . $search . "%' OR a.teacher LIKE '%" . $search . "%' OR a.title LIKE '%" . $search . "%' OR a.shortdesc LIKE '%" . $search . "%' OR a.description LIKE '%" . $search . "%')";
//
//    $database->setQuery("SELECT a.* FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
//            . $leftjoin
//            . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//            . $suche
//    );
//    $rows = $database->loadObjectList();
//
//    // Abzug der Kurse, die wegen Ausbuchung nicht angezeigt werden sollen
//    $abzug = 0;
//    $abid = array();
//    if ($art == 0) {
//        foreach ((array)$rows as $row) {
//            if ($row->stopbooking == 2) {
//                $gebucht = sem_f020($row);
//                if ($row->maxpupil - $gebucht->booked < 1) {
//                    $abzug++;
//                    $abid[] = $row->id;
//                }
//                ;
//            }
//        }
//    }
//    if (count($abid) > 0) {
//        $abid = implode(',', $abid);
//        $where[] = "a.id NOT IN ($abid)";
//    }
//    $total = count($rows) - $abzug;
//
//    if (!is_numeric($limitstart)) {
//        $limitstart = explode("=", $limitstart);
//        $limitstart = end($limitstart);
//        if (!is_numeric($limitstart)) {
//            $limitstart = 0;
//        }
//    }
//    if ($total <= $limitstart) {
//        $limitstart = $limitstart - $limit;
//    }
//    if ($limitstart < 0) {
//        $limitstart = 0;
//    }
//    $ttlimit = "";
//    if ($limit > 0) {
//        $ttlimit = "\nLIMIT $limitstart, $limit";
//    }
//    $pageNav = sem_f039($total, $limit, $limitstart);
//
//    $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
//            . $leftjoin
//            . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//            . $suche
//            . "\nORDER BY a.begin"
//            . $ttlimit
//    );
//    $rows = $database->loadObjectList();
//
//    // Kursauswahl erstellen
//    $allekurse = array();
//    $allekurse[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_ALL_EVENTS'));
//    $allekurse[] = JHTML::_('select.option', '1', JTEXT::_('COM_MATUKIO_CURRENT_EVENTS'));
//    $allekurse[] = JHTML::_('select.option', '2', JTEXT::_('COM_MATUKIO_OLD_EVENTS'));
//    $datelist = JHTML::_('select.genericlist', $allekurse, "dateid", "class=\"sem_inputbox\" size=\"1\" onchange=\"document.FrontForm.limitstart.value=0;document.FrontForm.submit();\"", "value", "text", $dateid);
//
//    // Kategorieliste erstellen
//    $reglevel = MatukioHelperUtilsBasic::getUserLevel();
//    $accesslvl = 1;
//    if ($reglevel >= 6) {
//        $accesslvl = 3;
//    } elseif ($reglevel >= 2) {
//        $accesslvl = 2;
//    }
//    $categories[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_ALL_CATS'));
//    $database->setQuery("SELECT id AS value, title AS text FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
//    $categs = array_merge($categories, (array)$database->loadObjectList());
//    $clist = JHTML::_('select.genericlist', $categs, "catid", "class=\"sem_inputbox\" size=\"1\" onchange=\"document.FrontForm.limitstart.value=0;document.FrontForm.submit();\"", "value", "text", $catid);
//    $listen = array($datelist, $dateid, $clist, $catid);
//
//    // Navigationspfad erweitern
//    sem_f019($anztyp[0], "javascript:semauf(" . $anztyp[1] . ",'','');");
//
//    // Ausgabe der Kursuebersicht
//    HTML_FrontMatukio::sem_g001($art, $rows, $pageNav, $search, $limit, $limitstart, $total, $datelist, $dateid, $clist, $catid);
//}

// +++++++++++++++++++++++++++++++++++++
// +++ Anzeige des gewaehlten Kurses +++
// +++++++++++++++++++++++++++++++++++++

//function sem_g002($art)
//{
//    $database = JFactory::getDBO();
//    $dateid = JRequest::getInt('dateid', 1);
//    $cid = JRequest::getInt('cid', 0);
//    $uid = JRequest::getInt('uid', 0);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getVar('search', '');
//    $limit = JRequest::getInt('limit', 5);
//    $limitstart = JRequest::getInt('limitstart', 0);
//
//    // Werte des angegebenen Kurses ermitteln
//    $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    if (count($rows) == 0) {
//        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
//        exit();
//    }
//    $row = &$rows[0];
//    if ($art == 3) {
//        if ($uid > 0) {
//            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$uid'");
//            $temp = $database->loadObjectList();
//            $userid = $temp[0]->userid;
//            if ($userid == 0) {
//                $uid = $uid * -1;
//            } else {
//                $uid = $userid;
//            }
//        }
//    } else {
//        if ($uid > 0) {
//            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$uid'");
//            $temp = $database->loadObjectList();
//            $uid = $temp[0]->userid;
//        }
//    }
//    if ($art == 0) {
//        // Hits erhoehen
//        $database->setQuery("UPDATE #__matukio SET hits=hits+1 WHERE id='$cid'");
//        if (!$database->query()) {
//            JError::raiseError(500, $row->getError());
//            exit();
//        }
//
//        // Ausgabe des Kurses
//        sem_f019(JTEXT::_('COM_MATUKIO_EVENTS'), "javascript:semauf(0,'','');");
//    } elseif ($art == 1 OR $art == 2) {
//        sem_f019(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), "javascript:semauf(1,'','');");
//    } else {
//        sem_f019(JTEXT::_('COM_MATUKIO_MY_OFFERS'), "javascript:semauf(2,'','');");
//    }
//    sem_f019($row->title, "");
//    $ueberschrift = array(JTEXT::_('COM_MATUKIO_DESCRIPTION'), $row->shortdesc);
//
//    HTML_FrontMatukio::sem_g002($art, $row, $uid, $search, $catid, $limit, $limitstart, $dateid, $ueberschrift);
//}

// +++++++++++++++++++++++++++++++++++++
// +++ Buchungsdaten aendern         +++
// +++++++++++++++++++++++++++++++++++++

function sem_g003($art)
{
//    $database = JFactory::getDBO();
//    $neu = new mossembookings($database);
//    if (!$neu->bind($_POST)) {
//        JError::raiseError(500, $database->stderr());
//        exit();
//    }
//    $neu->id = JRequest::getInt('uid', 0);
//    $neu->name = sem_f018($neu->name);
//    $neu->email = sem_f018($neu->email);
//    $neu->zusatz1 = sem_f018($neu->zusatz1);
//    $neu->zusatz2 = sem_f018($neu->zusatz2);
//    $neu->zusatz3 = sem_f018($neu->zusatz3);
//    $neu->zusatz4 = sem_f018($neu->zusatz4);
//    $neu->zusatz5 = sem_f018($neu->zusatz5);
//    $neu->zusatz6 = sem_f018($neu->zusatz6);
//    $neu->zusatz7 = sem_f018($neu->zusatz7);
//    $neu->zusatz8 = sem_f018($neu->zusatz8);
//    $neu->zusatz9 = sem_f018($neu->zusatz9);
//    $neu->zusatz10 = sem_f018($neu->zusatz10);
//    $neu->zusatz11 = sem_f018($neu->zusatz11);
//    $neu->zusatz12 = sem_f018($neu->zusatz12);
//    $neu->zusatz13 = sem_f018($neu->zusatz13);
//    $neu->zusatz14 = sem_f018($neu->zusatz14);
//    $neu->zusatz15 = sem_f018($neu->zusatz15);
//    $neu->zusatz16 = sem_f018($neu->zusatz16);
//    $neu->zusatz17 = sem_f018($neu->zusatz17);
//    $neu->zusatz18 = sem_f018($neu->zusatz18);
//    $neu->zusatz19 = sem_f018($neu->zusatz19);
//    $neu->zusatz20 = sem_f018($neu->zusatz20);
//    if (!$neu->check()) {
//        JError::raiseError(500, $database->stderr());
//        exit();
//    }
//    if (!$neu->store()) {
//        JError::raiseError(500, $database->stderr());
//        exit();
//    }
//    $neu->checkin();
//    if ($art == 1) {
//        sem_g001(1);
//    } else {
//        sem_g010(2);
//    }
}

// +++++++++++++++++++++++++++++++++++++
// +++ Kurs buchen                   +++
// +++++++++++++++++++++++++++++++++++++

function sem_g004()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//    $dateid = JRequest::getInt('dateid', 1);
//    $cid = JRequest::getInt('cid', 0);
//    $uid = JRequest::getInt('uid', 0);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getVar('search', '');
//    $limit = JRequest::getInt('limit', 5);
//    $limitstart = JRequest::getInt('limitstart', 0);
//    $nrbooked = JRequest::getInt('nrbooked', 0);
//    $name = JRequest::getVar('name', '');
//    $email = JRequest::getVar('email', '');
//    $reason = JTEXT::_('COM_MATUKIO_ADMIN_BOOKED_EVENT_FOR_YOU');
//
//    // Werte des angegebenen Kurses ermitteln
//    $row = new mosSeminar($database);
//    $row->load($cid);
//
//    $usrid = $my->id;
//    $art = 2;
//    if ($uid > 0) {
//        $usrid = $uid;
//
//        $art = 4;
//    }
//    $sqlid = $usrid;
//
//    if (($name != "" AND $email != "") OR $usrid == 0) {
//        $usrid = 0;
//        $sqlid = -1;
//    }
//
//    // Pruefung ob Buchung erfolgreich durchfuehrbar
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid' AND userid='$sqlid'");
//    $temp = $database->loadObjectList();
//    $gebucht = sem_f020($row);
//    $gebucht = $gebucht->booked;
//    $allesok = 1;
//    $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
//    if (count($temp) > 0) {
//        $allesok = 0;
//        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//        $reason = JTEXT::_('COM_MATUKIO_REGISTERED_FOR_THIS_EVENT');
//    } else if (MatukioHelperUtilsDate::getCurrentDate() > $row->booked) {
//        $allesok = 0;
//        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//        $reason = JTEXT::_('COM_MATUKIO_EXCEEDED');
//    } else if ($row->maxpupil - $gebucht - $nrbooked < 0 && $row->stopbooking == 1) {
//        $allesok = 0;
//        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_NOT_SUCCESSFULL');
//        $reason = JTEXT::_('COM_MATUKIO_MAX_PARTICIPANT_NUMBER_REACHED');
//    } else if ($row->maxpupil - $gebucht - $nrbooked < 0 && $row->stopbooking == 0) {
//        $allesok = 2;
//        $ueber1 = JTEXT::_('COM_MATUKIO_ADDED_WAITLIST');
//        $reason = JTEXT::_('COM_MATUKIO_YOU_ARE_BOOKED_ON_THE_WAITING_LIST');
//    }
//    if ($art == 4) {
//        $allesok = 1;
//        $ueber1 = JTEXT::_('COM_MATUKIO_BOOKING_WAS_SUCCESSFULL');
//    }
//
//    // Alles in Ordnung
//    if ($allesok > 0) {
//
//        // Buchung eintragen
//        $neu = new mossembookings($database);
//        if (!$neu->bind($_POST)) {
//            JError::raiseError(500, $database->stderr());
//            exit();
//        }
//        $neu->semid = $cid;
//        $neu->userid = $usrid;
//        $neu->bookingdate = MatukioHelperUtilsDate::getCurrentDate();
//        $neu->name = sem_f018($neu->name);
//        $neu->email = sem_f018($neu->email);
//        $neu->zusatz1 = sem_f018($neu->zusatz1);
//        $neu->zusatz2 = sem_f018($neu->zusatz2);
//        $neu->zusatz3 = sem_f018($neu->zusatz3);
//        $neu->zusatz4 = sem_f018($neu->zusatz4);
//        $neu->zusatz5 = sem_f018($neu->zusatz5);
//        $neu->zusatz6 = sem_f018($neu->zusatz6);
//        $neu->zusatz7 = sem_f018($neu->zusatz7);
//        $neu->zusatz8 = sem_f018($neu->zusatz8);
//        $neu->zusatz9 = sem_f018($neu->zusatz9);
//        $neu->zusatz10 = sem_f018($neu->zusatz10);
//        $neu->zusatz11 = sem_f018($neu->zusatz11);
//        $neu->zusatz12 = sem_f018($neu->zusatz12);
//        $neu->zusatz13 = sem_f018($neu->zusatz13);
//        $neu->zusatz14 = sem_f018($neu->zusatz14);
//        $neu->zusatz15 = sem_f018($neu->zusatz15);
//        $neu->zusatz16 = sem_f018($neu->zusatz16);
//        $neu->zusatz17 = sem_f018($neu->zusatz17);
//        $neu->zusatz18 = sem_f018($neu->zusatz18);
//        $neu->zusatz19 = sem_f018($neu->zusatz19);
//        $neu->zusatz20 = sem_f018($neu->zusatz20);
//        if (!$neu->check()) {
//            JError::raiseError(500, $database->stderr());
//            exit();
//        }
//        if (!$neu->store()) {
//            JError::raiseError(500, $database->stderr());
//            exit();
//        }
//        $neu->checkin();
//    }
//    if ($art == 4) {
//        sem_f050($cid, $neu->id, 8);
//        sem_g010(2);
//    } else {
//        sem_f050($cid, $neu->id, 1);
//        $ueberschrift = array($ueber1, $reason);
//
//        // Ausgabe des Kurses
//        sem_f019(JTEXT::_('COM_MATUKIO_EVENTS'), "javascript:semauf('','','');");
//        sem_f019($row->title, "");
//        if ($usrid == 0) {
//            $usrid = $neu->id * -1;
//        }
//        HTML_FrontMatukio::sem_g002($art, $row, $usrid, $search, $catid, $limit, $limitstart, $dateid, $ueberschrift);
//    }
}

// +++++++++++++++++++++++++++++++++++++
// +++ Buchung loeschen              +++
// +++++++++++++++++++++++++++++++++++++

function sem_g005()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    if (count($rows) > 0) {
//        sem_f050($rows[0]->semid, $cid, 2);
//        $database->setQuery("DELETE FROM #__matukio_bookings WHERE id='$cid'");
//        if (!$database->query()) {
//            JError::raiseError(500, $database->getError());
//            exit();
//        }
//    }
//    sem_g001(1);
}

// ++++++++++++++++++++++++++++++++++
// +++ Kurse editieren            +++
// ++++++++++++++++++++++++++++++++++

function sem_g006()
{
//{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//
//    $dateid = JRequest::getInt('dateid', 1);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getVar('search', '');
//    $limit = JRequest::getInt('limit', 5);
//    $limitstart = JRequest::getInt('limitstart', 0);
//    $vorlage = JRequest::getInt('vorlage', 0);
//    $cid = JRequest::getInt('cid', 0);
//
//    $args = func_get_args();
//    if (count($args) == 1) {
//        $vorlage = $args[0];
//        $cid = $vorlage;
//    }
//    if (count($args) > 1) {
//        $cid = $args[0];
//        $fehler = $args[1];
//        $fehler = array_unique($fehler);
//        if ($fehler[0] == "") {
//            $fehler = array_slice($fehler, 1);
//        }
//        $fehler = implode("<br />", $fehler);
//        JError::raise(E_WARNING, 1, $fehler);
//    }
//    if (count($args) > 2) {
//        $row = $args[2];
//    } else {
//        $row = new mosSeminar($database);
//        $row->load($cid);
//    }
//
//    // Ist es eine Vorlage
//    if ($vorlage > 0) {
//        $row->id = "";
//        $row->pattern = "";
//    }
//    if ($cid < 1) {
//        $row->publisher = $my->id;
//        $row->semnum = createNewEventNumber(date('Y'));
//    }
//    $row->vorlage = $vorlage;
//
//    // Zeit festlegen
//    if ($row->begin == "0000-00-00 00:00:00") {
//        $row->begin = date("Y-m-d 14:00:00");
//        $row->end = date("Y-m-d 17:00:00");
//        $row->booked = date("Y-m-d 12:00:00");
//    }
//    $zeit = explode(" ", $row->begin);
//    $row->begin_date = $zeit[0];
//    $zeit = explode(":", $zeit[1]);
//    $row->begin_hour = $zeit[0];
//    $row->begin_minute = $zeit[1];
//    $zeit = explode(" ", $row->end);
//    $row->end_date = $zeit[0];
//    $zeit = explode(":", $zeit[1]);
//    $row->end_hour = $zeit[0];
//    $row->end_minute = $zeit[1];
//    $zeit = explode(" ", $row->booked);
//    $row->booked_date = $zeit[0];
//    $zeit = explode(":", $zeit[1]);
//    $row->booked_hour = $zeit[0];
//    $row->booked_minute = $zeit[1];
//
//    sem_f019(JTEXT::_('COM_MATUKIO_MY_OFFERS'), "javascript:semauf(2,'','');");
//    if ($cid) {
//        sem_f019($row->title, "");
//    } else {
//        sem_f019(JTEXT::_('COM_MATUKIO_NEW_EVENT'), "");
//    }
//    HTML_FrontMatukio::sem_g006($row, $search, $catid, $limit, $limitstart, $dateid);
}

// +++++++++++++++++++++++++++++++++++++
// +++ Neuen Kurs speichern          +++
// +++++++++++++++++++++++++++++++++++++

function sem_g007()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//    $cid = JRequest::getInt('cid', 0);
//    $caid = JRequest::getInt('caid', 0);
//    $cancel = JRequest::getInt('cancel', 0);
//    $inform = JRequest::getInt('inform', 0);
//    $infotext = sem_f018(JRequest::getVar('infotext', ''));
//    $deldatei1 = JRequest::getInt('deldatei1', 0);
//    $deldatei2 = JRequest::getInt('deldatei2', 0);
//    $deldatei3 = JRequest::getInt('deldatei3', 0);
//    $deldatei4 = JRequest::getInt('deldatei4', 0);
//    $deldatei5 = JRequest::getInt('deldatei5', 0);
//    $vorlage = JRequest::getInt('vorlage', 0);
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//
//    // Zeit formatieren
//    $_begin_date = JRequest::getVar('_begin_date', '0000-00-00');
//    $_begin_hour = JRequest::getVar('_begin_hour', '00');
//    $_begin_minute = JRequest::getVar('_begin_minute', '00');
//    $_end_date = JRequest::getVar('_end_date', '0000-00-00');
//    $_end_hour = JRequest::getVar('_end_hour', '00');
//    $_end_minute = JRequest::getVar('_end_minute', '00');
//    $_booked_date = JRequest::getVar('_booked_date', '0000-00-00');
//    $_booked_hour = JRequest::getVar('_booked_hour', '00');
//    $_booked_minute = JRequest::getVar('_booked_minute', '00');
//
//    if ($cid > 0) {
//        $kurs = new mosSeminar($database);
//        $kurs->load($cid);
//    }
//    if ($vorlage > 0) {
//        $kurs = new mosSeminar($database);
//        $kurs->load($vorlage);
//    }
//    $post = JRequest::get('post');
//    $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
//    $row = new mosSeminar($database);
//    $row->load($cid);
//    if (!$row->bind($post)) {
//        return JError::raiseWarning(500, $row->getError());
//    }
//    if ($cancel != $row->cancelled AND $row->pattern == "") {
//        $tempmail = 9 + $cancel;
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id'");
//        $rows = $database->loadObjectList();
//        for ($i = 0, $n = count($rows); $i < $n; $i++) {
//            sem_f050($row->id, $rows[$i]->id, $tempmail);
//        }
//    }
//    $row->cancelled = $cancel;
//    $row->catid = $caid;
//
//    // Zuweisung der Startzeit
//    if (intval($_begin_date)) {
//        $dt = "$_begin_date $_begin_hour:$_begin_minute:00";
//    } else {
//        $dt = date("Y-m-d 14:00:00");
//    }
//    $row->begin = strftime("%Y-%m-%d %H:%M:%S", strtotime($dt));
//
//    // Zuweisung der Endzeit
//    if (intval($_end_date)) {
//        $dt = "$_end_date $_end_hour:$_end_minute:00";
//    } else {
//        $dt = date("Y-m-d 17:00:00");
//    }
//    $row->end = strftime("%Y-%m-%d %H:%M:%S", strtotime($dt));
//
//    // Zuweisung der Buchungszeit
//    if (intval($_booked_date)) {
//        $dt = "$_booked_date $_booked_hour:$_booked_minute:00";
//    } else {
//        $dt = date("Y-m-d 12:00:00");
//    }
//    $row->booked = strftime("%Y-%m-%d %H:%M:%S", strtotime($dt));
//
//    // Zuweisung der aktuellen Zeit
//    if ($cid == 0) {
//        $row->publishdate = $neudatum;
//    } else {
//        $row->publishdate = $kurs->publishdate;
//    }
//    $row->updated = $neudatum;
//
//
//    // neue Daten eintragen
//    $row->description = str_replace('<br>', '<br />', $row->description);
//    $row->description = str_replace('\"', '"', $row->description);
//    $row->description = str_replace("\'", "'", $row->description);
//    $row->semnum = sem_f018($row->semnum);
//    $row->title = sem_f018($row->title);
//    $row->target = sem_f018($row->target);
//    $row->shortdesc = sem_f018($row->shortdesc);
//    $row->place = sem_f018($row->place);
//    $row->fees = str_replace(",", ".", sem_f018($row->fees));
//    $row->maxpupil = sem_f018($row->maxpupil);
//    $row->gmaploc = sem_f018($row->gmaploc);
//    $row->nrbooked = sem_f018($row->nrbooked);
//    $row->zusatz1 = sem_f018($row->zusatz1);
//    $row->zusatz2 = sem_f018($row->zusatz2);
//    $row->zusatz3 = sem_f018($row->zusatz3);
//    $row->zusatz4 = sem_f018($row->zusatz4);
//    $row->zusatz5 = sem_f018($row->zusatz5);
//    $row->zusatz6 = sem_f018($row->zusatz6);
//    $row->zusatz7 = sem_f018($row->zusatz7);
//    $row->zusatz8 = sem_f018($row->zusatz8);
//    $row->zusatz9 = sem_f018($row->zusatz9);
//    $row->zusatz10 = sem_f018($row->zusatz10);
//    $row->zusatz11 = sem_f018($row->zusatz11);
//    $row->zusatz12 = sem_f018($row->zusatz12);
//    $row->zusatz13 = sem_f018($row->zusatz13);
//    $row->zusatz14 = sem_f018($row->zusatz14);
//    $row->zusatz15 = sem_f018($row->zusatz15);
//    $row->zusatz16 = sem_f018($row->zusatz16);
//    $row->zusatz17 = sem_f018($row->zusatz17);
//    $row->zusatz18 = sem_f018($row->zusatz18);
//    $row->zusatz19 = sem_f018($row->zusatz19);
//    $row->zusatz20 = sem_f018($row->zusatz20);
//    $row->zusatz1hint = sem_f018($row->zusatz1hint);
//    $row->zusatz2hint = sem_f018($row->zusatz2hint);
//    $row->zusatz3hint = sem_f018($row->zusatz3hint);
//    $row->zusatz4hint = sem_f018($row->zusatz4hint);
//    $row->zusatz5hint = sem_f018($row->zusatz5hint);
//    $row->zusatz6hint = sem_f018($row->zusatz6hint);
//    $row->zusatz7hint = sem_f018($row->zusatz7hint);
//    $row->zusatz8hint = sem_f018($row->zusatz8hint);
//    $row->zusatz9hint = sem_f018($row->zusatz9hint);
//    $row->zusatz10hint = sem_f018($row->zusatz10hint);
//    $row->zusatz11hint = sem_f018($row->zusatz11hint);
//    $row->zusatz12hint = sem_f018($row->zusatz12hint);
//    $row->zusatz13hint = sem_f018($row->zusatz13hint);
//    $row->zusatz14hint = sem_f018($row->zusatz14hint);
//    $row->zusatz15hint = sem_f018($row->zusatz15hint);
//    $row->zusatz16hint = sem_f018($row->zusatz16hint);
//    $row->zusatz17hint = sem_f018($row->zusatz17hint);
//    $row->zusatz18hint = sem_f018($row->zusatz18hint);
//    $row->zusatz19hint = sem_f018($row->zusatz19hint);
//    $row->zusatz20hint = sem_f018($row->zusatz20hint);
//    $row->file1desc = sem_f018($row->file1desc);
//    $row->file2desc = sem_f018($row->file2desc);
//    $row->file3desc = sem_f018($row->file3desc);
//    $row->file4desc = sem_f018($row->file4desc);
//    $row->file5desc = sem_f018($row->file5desc);
//    if ($cid > 0 OR $vorlage > 0) {
//        if ($deldatei1 != 1) {
//            $row->file1 = $kurs->file1;
//            $row->file1code = $kurs->file1code;
//        }
//        if ($deldatei2 != 1) {
//            $row->file2 = $kurs->file2;
//            $row->file2code = $kurs->file2code;
//        }
//        if ($deldatei3 != 1) {
//            $row->file3 = $kurs->file3;
//            $row->file3code = $kurs->file3code;
//        }
//        if ($deldatei4 != 1) {
//            $row->file4 = $kurs->file4;
//            $row->file4code = $kurs->file4code;
//        }
//        if ($deldatei5 != 1) {
//            $row->file5 = $kurs->file5;
//            $row->file5code = $kurs->file5code;
//        }
//    }
//    if ($cid > 0) {
//        $row->hits = $kurs->hits;
//    }
//    $fileext = explode(' ', strtolower(MatukioHelperSettings::getSettings('file_endings', 'txt zip pdf')));
//    $filesize = MatukioHelperSettings::getSettings('file_maxsize', 500) * 1024;
//    $fehler = array('', '', '', '', '', '', '', '', '', '');
//    if (is_file($_FILES['datei1']['tmp_name']) AND $_FILES['datei1']['size'] > 0) {
//        if ($_FILES['datei1']['size'] > $filesize) {
//            $fehler[0] = str_replace("SEM_FILE", $_FILES['datei1']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
//        }
//        $datei1ext = array_pop(explode(".", strtolower($_FILES['datei1']['name'])));
//        if (!in_array($datei1ext, $fileext)) {
//            $fehler[1] = str_replace("SEM_FILE", $_FILES['datei1']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
//        }
//        if ($fehler[0] == "" AND $fehler[1] == "") {
//            $row->file1 = $_FILES['datei1']['name'];
//            $row->file1code = base64_encode(file_get_contents($_FILES['datei1']['tmp_name']));
//        }
//    }
//    if (is_file($_FILES['datei2']['tmp_name']) AND $_FILES['datei2']['size'] > 0) {
//        if ($_FILES['datei2']['size'] > $filesize) {
//            $fehler[2] = str_replace("SEM_FILE", $_FILES['datei2']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
//        }
//        $datei2ext = array_pop(explode(".", strtolower($_FILES['datei2']['name'])));
//        if (!in_array($datei2ext, $fileext)) {
//            $fehler[3] = str_replace("SEM_FILE", $_FILES['datei2']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
//        }
//        if ($fehler[2] == "" AND $fehler[3] == "") {
//            $row->file2 = $_FILES['datei2']['name'];
//            $row->file2code = base64_encode(file_get_contents($_FILES['datei2']['tmp_name']));
//        }
//    }
//    if (is_file($_FILES['datei3']['tmp_name']) AND $_FILES['datei3']['size'] > 0) {
//        if ($_FILES['datei3']['size'] > $filesize) {
//            $fehler[4] = str_replace("SEM_FILE", $_FILES['datei3']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
//        }
//        $datei3ext = array_pop(explode(".", strtolower($_FILES['datei3']['name'])));
//        if (!in_array($datei3ext, $fileext)) {
//            $fehler[5] = str_replace("SEM_FILE", $_FILES['datei3']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
//        }
//        if ($fehler[4] == "" AND $fehler[5] == "") {
//            $row->file3 = $_FILES['datei3']['name'];
//            $row->file3code = base64_encode(file_get_contents($_FILES['datei3']['tmp_name']));
//        }
//    }
//    if (is_file($_FILES['datei4']['tmp_name']) AND $_FILES['datei4']['size'] > 0) {
//        if ($_FILES['datei4']['size'] > $filesize) {
//            $fehler[6] = str_replace("SEM_FILE", $_FILES['datei4']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
//        }
//        $datei4ext = array_pop(explode(".", strtolower($_FILES['datei4']['name'])));
//        if (!in_array($datei4ext, $fileext)) {
//            $fehler[7] = str_replace("SEM_FILE", $_FILES['datei4']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
//        }
//        if ($fehler[6] == "" AND $fehler[7] == "") {
//            $row->file4 = $_FILES['datei4']['name'];
//            $row->file4code = base64_encode(file_get_contents($_FILES['datei4']['tmp_name']));
//        }
//    }
//    if (is_file($_FILES['datei5']['tmp_name']) AND $_FILES['datei5']['size'] > 0) {
//        if ($_FILES['datei5']['size'] > $filesize) {
//            $fehler[8] = str_replace("SEM_FILE", $_FILES['datei5']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
//        }
//        $datei5ext = array_pop(explode(".", strtolower($_FILES['datei5']['name'])));
//        if (!in_array($datei5ext, $fileext)) {
//            $fehler[9] = str_replace("SEM_FILE", $_FILES['datei5']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
//        }
//        if ($fehler[8] == "" AND $fehler[9] == "") {
//            $row->file5 = $_FILES['datei5']['name'];
//            $row->file5code = base64_encode(file_get_contents($_FILES['datei5']['tmp_name']));
//        }
//    }
//
//    // Eingaben ueberpruefen
//    $speichern = TRUE;
//    if (!sem_f067($row->pattern, 'leer')) {
//        if (!sem_f067($row->semnum, 'leer')) {
//            $speichern = FALSE;
//            $htxt = JTEXT::_('COM_MATUKIO_NO_EVENT_FILES');
//            if ($cid < 1) {
//                $htxt .= " " . JTEXT::_('COM_MATUKIO_EVENT_NOT_STORED');
//            }
//            $fehler[] = $htxt;
//        } else {
//            $database->setQuery("SELECT id FROM #__matukio WHERE semnum='$row->semnum' AND id!='$row->id'");
//            $rows = $database->loadObjectList();
//            if (count($rows) > 0) {
//                $speichern = FALSE;
//                $htxt = JTEXT::_('COM_MATUKIO_NOT_UNIQUE_NUMBERS');
//                if ($cid < 1) {
//                    $htxt .= " " . JTEXT::_('COM_MATUKIO_EVENT_NOT_STORED');
//                }
//                $fehler[] = $htxt;
//            }
//        }
//    }
//    // speichern
//    if ($speichern == TRUE) {
//        if (!$row->check()) {
//            JError::raiseError(500, $database->stderr());
//            return false;
//        }
//        if (!$row->store()) {
//            JError::raiseError(500, $database->stderr());
//            return false;
//        }
//    }
//
//    // Ausgabe der Kurse
//    $fehlerzahl = array_unique($fehler);
//    if (sem_f067($row->pattern, 'leer')) {
//        sem_g006($row->id);
//    } elseif (count($fehlerzahl) > 1 AND $speichern == TRUE) {
//        sem_g006($row->id, $fehler);
//    } elseif (count($fehlerzahl) > 1 AND $speichern == FALSE) {
//        sem_g006($row->id, $fehler, $row);
//    } else {
//        sem_g001(2);
//    }
}

// +++++++++++++++++++++++++++++++++++++
// +++ Kurs unpublishen              +++
// +++++++++++++++++++++++++++++++++++++

function sem_g008()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//    $cid = JRequest::getInt('cid', 0);
//    $vorlage = JRequest::getInt('vorlage', 0);
//    $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    $aktsem = &$rows[0];
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    if ($neudatum < $aktsem->begin AND $vorlage == 0) {
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid'");
//        $rows = $database->loadObjectList();
//        for ($i = 0, $n = count($rows); $i < $n; $i++) {
//            sem_f050($cid, $rows[$i]->id, 4);
//        }
//    }
//    $database->setQuery("UPDATE #__matukio SET published=0 WHERE id='$cid'");
//    if (!$database->query()) {
//        JError::raiseError(500, $row->getError());
//        exit();
//    }
//    if ($vorlage > 0) {
//        sem_g006(0);
//    } else {
//        sem_g001(2);
//    }
}

// ++++++++++++++++++++++++++++++++++
// +++ matuki kopieren           +++
// ++++++++++++++++++++++++++++++++++

function sem_g009()
{
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    if ($database->getErrorNum()) {
//        JError::raiseError(500, $row->getError());
//        return false;
//    }
//    $item = $rows[0];
//    $row = new mosSeminar($database);
//    if (!$row->bind($item)) {
//        JError::raiseError(500, $row->getError());
//        exit();
//    }
//    $row->id = NULL;
//    $row->hits = 0;
//    $row->grade = 0;
//    $row->certificated = 0;
//    $row->sid = $item->id;
//    $row->publishdate = MatukioHelperUtilsDate::getCurrentDate();
//    $row->semnum = createNewEventNumber(date('Y'));
//    if (!$row->check()) {
//        JError::raiseError(500, $row->getError());
//        return false;
//    }
//    if (!$row->store()) {
//        JError::raiseError(500, $row->getError());
//        return false;
//    }
//    sem_g001(2);
}

// +++++++++++++++++++++++++++++++++++++
// +++ Buchungen ansehen             +++
// +++++++++++++++++++++++++++++++++++++

function sem_g010($arte)
{
//    $database = JFactory::getDBO();
//    $dateid = JRequest::getInt('dateid', 1);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getVar('search', '');
//    $limit = JRequest::getInt('limit', 5);
//    $limitstart = JRequest::getInt('limitstart', 0);
//    $cid = JRequest::getInt('cid', 0);
//    $art = JRequest::getInt('uid', 0);
//    $args = func_get_args();
//    if (count($args) > 1) {
//        $cid = $args[1];
//    }
//    if ($arte == 2) {
//        $art = 2;
//    }
//    $kurs = new mosSeminar($database);
//    $kurs->load($cid);
//
//    if ($art == 0) {
//        $anztyp = array(JTEXT::_('COM_MATUKIO_EVENTS'), 0);
//    } elseif ($art == 1) {
//        $anztyp = array(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), 1);
//    } elseif ($art == 2) {
//        $anztyp = array(JTEXT::_('COM_MATUKIO_MY_OFFERS'), 2);
//    }
//
//    $database->setQuery("SELECT a.*, cc.*, a.id AS sid, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$kurs->id' ORDER BY a.id");
//    $rows = $database->loadObjectList();
//    if ($database->getErrorNum()) {
//        echo $database->stderr();
//        return false;
//    }
//    sem_f019($anztyp[0], "javascript:semauf(" . $anztyp[1] . ",'','');");
//    sem_f019($kurs->title, "");
//    HTML_FrontMatukio::sem_g010($art, $rows, $search, $limit, $limitstart, $kurs, $catid, $dateid);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Buchung durch Veranstalter loeschen +++
// +++++++++++++++++++++++++++++++++++++++++++

function sem_g011()
{
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    if ($rows[0]->userid > 0) {
//        sem_f050($rows[0]->semid, $rows[0]->id, 3);
//    }
//    $database->setQuery("DELETE FROM #__matukio_bookings WHERE id='$cid'");
//    if (!$database->query()) {
//        JError::raiseError(500, $database->getError());
//        exit();
//    }
//    sem_g010(2, $rows[0]->semid);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Bezahlung markieren                 +++
// +++++++++++++++++++++++++++++++++++++++++++

function sem_g012()
{
    $database = JFactory::getDBO();
    $cid = JFactory::getApplication()->input->getInt('cid', 0);
    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$cid'");
    $rows = $database->loadObjectList();
    if ($rows[0]->paid == 0) {
        $paid = 1;
    } else {
        $paid = 0;
    }
    $database->setQuery("UPDATE #__matukio_bookings SET paid='$paid' WHERE id='$cid'");
    if (!$database->execute()) {
        JError::raiseError(500, $database->getError());
        exit();
    }

    sem_g010(2, $rows[0]->semid);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Teilnehmer zertifizieren            +++
// +++++++++++++++++++++++++++++++++++++++++++

function sem_g013()
{
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    if ($rows[0]->certificated == 0) {
//        $cert = 1;
//        $certmail = 6;
//    } else {
//        $cert = 0;
//        $certmail = 7;
//    }
//    $database->setQuery("UPDATE #__matukio_bookings SET certificated='$cert' WHERE id='$cid'");
//    if (!$database->query()) {
//        JError::raiseError(500, $row->getError());
//        exit();
//    }
//    sem_f050($rows[0]->semid, $cid, $certmail);
//    sem_g010(2, $rows[0]->semid);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Bewertungsfenster ausgeben          +++
// +++++++++++++++++++++++++++++++++++++++++++

function sem_g014()
{
//    $my = JFactory::getuser();
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    $row = &$rows[0];
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid' AND userid='$my->id'");
//    $buchung = $database->loadObjectList();
//    $buchung = $buchung[0];
//    HTML_FrontMatukio::sem_g014($row, $buchung);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Bewertung abspeichern               +++
// +++++++++++++++++++++++++++++++++++++++++++

function sem_g015()
{
//    $mainframe = JFactory::getApplication();
//    jimport('joomla.mail.helper');
//    $my = JFactory::getuser();
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $grade = JRequest::getInt('grade', 0);
//    $text = JRequest::getVar('text', '');
//    $text = str_replace(array("\"", "\'"), "", $text);
//    $text = JMailHelper::cleanBody($text);
//    $database->setQuery("UPDATE #__matukio_bookings SET grade='$grade', comment='$text' WHERE semid='$cid' AND userid='$my->id'");
//    if (!$database->query()) {
//        JError::raiseError(500, $row->getError());
//        exit();
//    }
//    $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid'");
//    $rows = $database->loadObjectList();
//    $zaehler = 0;
//    $wertung = 0;
//    foreach ($rows AS $row) {
//        if ($row->grade > 0) {
//            $wertung = $wertung + $row->grade;
//            $zaehler = $zaehler + 1;
//        }
//    }
//    if ($zaehler > 0) {
//        $geswert = round($wertung / $zaehler);
//    } else {
//        $geswert = 0;
//    }
//    $database->setQuery("UPDATE #__matukio SET grade='$geswert' WHERE id='$cid'");
//    if (!$database->query()) {
//        JError::raiseError(500, $row->getError());
//        exit();
//    }
//    if (MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0) {
//        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid' AND userid='$my->id'");
//        $rows = $database->loadObjectList();
//        $buchung = &$rows[0];
//        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//        $rows = $database->loadObjectList();
//        $row = &$rows[0];
//        $publisher = JFactory::getuser($row->publisher);
//        $body = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
//        $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_RECEIVED_RATING') . "</div>";
//        $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_RATING') . ":</div>";
//        $htxt = str_replace('SEM_POINTS', $grade, JTEXT::_('COM_MATUKIO_SEM_POINTS_6'));
//        $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $htxt . "</div>";
//        $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_COMMENT') . ":</div>";
//        $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . htmlspecialchars($text) . "</div>";
//        $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_AVARAGE_SCORE') . ":</div>";
//        $htxt = str_replace('SEM_POINTS', $geswert, JTEXT::_('COM_MATUKIO_SEM_POINTS_6'));
//        $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $htxt . "</div>";
//        $body .= "<p>" . sem_f049($row, $buchung, $my);
//        $sender = $mainframe->getCfg('fromname');
//        $from = $mainframe->getCfg('mailfrom');
//        $replyname = $my->name;
//        $replyto = $my->email;
//        $email = $publisher->email;
//        $subject = JTEXT::_('COM_MATUKIO_EVENT');
//        if ($row->semnum != "") {
//            $subject .= " " . $row->semnum;
//        }
//        $subject .= ": " . $row->title;
//        $subject = JMailHelper::cleanSubject($subject);
//        JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
//    }
//    HTML_FrontMatukio::sem_g021($grade, $cid);
}

// +++++++++++++++++++++++++++++++++++++++++++
// +++ Nachricht an Veranstalter schreiben +++
// +++++++++++++++++++++++++++++++++++++++++++

//function sem_g016($art)
//{
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//    $rows = $database->loadObjectList();
//    $row = &$rows[0];
//    HTML_FrontMatukio::sem_g016($art, $row);
//}

// ++++++++++++++++++++++++++++++++++++++++++++
// +++ Nachricht an Veranstalter abschicken +++
// ++++++++++++++++++++++++++++++++++++++++++++

function sem_g017()
{
//    $mainframe = JFactory::getApplication();
//    jimport('joomla.mail.helper');
//    $my = JFactory::getuser();
//    $database = JFactory::getDBO();
//    $cid = JRequest::getInt('cid', 0);
//    $uid = JRequest::getInt('uid', 0);
//    $text = JMailHelper::cleanBody(nl2br(Request::getVar('text', '')));
//    if ($text != "") {
//        $reason = JTEXT::_('COM_MATUKIO_MESSAGE_SEND');
//        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
//        $rows = $database->loadObjectList();
//        $kurs = &$rows[0];
//        if ($row->semnum != "") {
//            $subject .= " " . $kurs->semnum;
//        }
//        $subject .= ": " . $kurs->title;
//        $subject = JMailHelper::cleanSubject($subject);
//        $sender = $mainframe->getCfg('fromname');
//        $from = $mainframe->getCfg('mailfrom');
//        if ($my->id == 0) {
//            $replyname = $mainframe->getCfg('fromname');
//            $replyto = $mainframe->getCfg('mailfrom');
//        } else {
//            $replyname = $my->name;
//            $replyto = $my->email;
//        }
//        $body = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
//        if ($uid == 1 AND $my->id != 0) {
//            $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_QUESTION_ABOUT_EVENT') . "</div><p>";
//        }
//        $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $text . "</div><p>";
//        $temp = array();
//        if ($uid == 1) {
//            $body .= sem_f049($kurs, $temp, $my);
//            $publisher = JFactory::getuser($kurs->publisher);
//            $email = $publisher->email;
//            JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
//        } else {
//            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$kurs->id'");
//            $rows = $database->loadObjectList();
//            foreach ($rows as $row) {
//                if ($row->userid == 0) {
//                    $user->email = $row->email;
//                    $user->name = $row->name;
//                } else {
//                    $user = JFactory::getuser($row->userid);
//                }
//                $text = $body . sem_f049($kurs, $row, $user);
//                JUtility::sendMail($from, $sender, $user->email, $subject, $text, 1, null, null, null, $replyto, $replyname);
//            }
//        }
//    } else {
//        $reason = JTEXT::_('COM_MATUKIO_MESSAGE_NOT_SEND');
//    }
//    HTML_FrontMatukio::sem_g022($reason);
}

// ++++++++++++++++++++++++++++++++++++++++
// +++ Ausdruck der matukiuebersichten +++
// ++++++++++++++++++++++++++++++++++++++++

function sem_g018()
{
//    $database = JFactory::getDBO();
//    $my = JFactory::getuser();
//    $dateid = JRequest::getInt('dateid', 1);
//    $catid = JRequest::getInt('catid', 0);
//    $search = JRequest::getVar('search', '');
//    $limit = JRequest::getInt('limit', 5);
//    $limitstart = JRequest::getInt('limitstart', 0);
//    $cid = JRequest::getInt('cid', 0);
//    $uid = JRequest::getInt('uid', 0);
//    $OIO = JRequest::getVar('OIO', '');
//    if ($OIO != "65O9805443904" AND $OIO != "6530387504345" AND $OIO != "653O875032490") {
//        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
//        exit;
//    }
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    if ($limitstart < 0) {
//        $limitstart = 0;
//    }
//    $ttlimit = "";
//    if ($limit > 0) {
//        $ttlimit = "\nLIMIT $limitstart, $limit";
//    }
//
//    $where = array();
//    $where[] = "a.pattern = ''";
//    $where[] = "a.published = '1'";
//    switch ($OIO) {
//        case "65O9805443904":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavshowmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//        case "6530387504345":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavbookingmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//        case "653O875032490":
//            $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavoffermodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
//            break;
//    }
//    if (in_array('SEM_TYPES', $navioben)) {
//        switch ($dateid) {
//            case "1":
//                $where[] = "a.end > '$neudatum'";
//                break;
//            case "2":
//                $where[] = "a.end <= '$neudatum'";
//                break;
//        }
//    }
//    switch ($OIO) {
//        case "65O9805443904":
//            if (!in_array('SEM_TYPES', $navioben)) {
//                $where[] = "a.end > '$neudatum'";
//            }
//            if ((isset($_GET["catid"]) OR in_array('SEM_CATEGORIES', $navioben)) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            $headertext = JTEXT::_('COM_MATUKIO_EVENTS');
//            if ($cid) {
//                $where[] = "a.id= '$cid'";
//                $headertext = JTEXT::_('COM_MATUKIO_EVENT');
//            }
//            $database->setQuery("SELECT a.*, cc.title AS category FROM #__matukio AS a"
//                    . "\nLEFT JOIN #__categories AS cc"
//                    . "\nON cc.id = a.catid"
//                    . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//                    . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
//            );
//            $rows = $database->loadObjectList();
//
//// Abzug der Kurse, die wegen Ausbuchung nicht angezeigt werden sollen
//            if (!$cid) {
//                $abid = array();
//                foreach ($rows as $row) {
//                    if ($row->stopbooking == 2) {
//                        $gebucht = sem_f020($row);
//                        if ($row->maxpupil - $gebucht->booked < 1) {
//                            $abid[] = $row->id;
//                        }
//                        ;
//                    }
//                }
//                if (count($abid) > 0) {
//                    $abid = implode(',', $abid);
//                    $where[] = "a.id NOT IN ($abid)";
//                }
//            }
//
//            $database->setQuery("SELECT a.*, cc.title AS category FROM #__matukio AS a"
//                    . "\nLEFT JOIN #__categories AS cc"
//                    . "\nON cc.id = a.catid"
//                    . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//                    . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
//                    . "\nORDER BY a.begin"
//                    . $ttlimit
//            );
//            $rows = $database->loadObjectList();
//            $status = array();
//            $paid = array();
//            $abid = array();
//            for ($i = 0, $n = count($rows); $i < $n; $i++) {
//                $row = &$rows[$i];
//                $gebucht = sem_f020($row);
//                $gebucht = $gebucht->booked;
//                if (MatukioHelperUtilsDate::getCurrentDate() > $row->booked OR ($row->maxpupil - $gebucht < 1 AND $row->stopbooking == 1) OR ($my->id == $row->publisher AND MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0)) {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
//                } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
//                } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 2) {
//                    $abid[] = $row->id;
//                } else {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
//                }
//                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' AND userid='$my->id'");
//                $temp = $database->loadObjectList();
//                if (count($temp) > 0) {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_ALREADY_BOOKED');
//                    if ($temp[0]->paid == 1) {
//                        $rows[$i]->fees = $rows[$i]->fees . " - " . JTEXT::_('COM_MATUKIO_PAID');
//                    }
//                }
//                $rows[$i]->codepic = "";
//            }
//            break;
//
//        case "6530387504345":
//            MatukioHelperUtilsBasic::checkUserLevel(1);
//            $headertext = JTEXT::_('COM_MATUKIO_MY_BOOKINGS') . " - " . $my->name;
//            if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            $where[] = "cc.userid = '" . $my->id . "'";
//            if ($cid) {
//                $where[] = "cc.semid = '" . $cid . "'";
//                $headertext = JTEXT::_('COM_MATUKIO_BOOKING_CONFIRMATION') . " - " . $my->name;
//            }
//            $database->setQuery("SELECT a.*, cat.title AS category, cc.bookingdate AS bookingdate, cc.id AS bookid FROM #__matukio AS a LEFT JOIN #__matukio_bookings AS cc ON cc.semid = a.id LEFT JOIN #__categories AS cat ON cat.id = a.catid"
//                    . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//                    . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
//                    . "\nORDER BY a.begin"
//                    . $ttlimit
//            );
//            $rows = $database->loadObjectList();
//            $status = array();
//            for ($i = 0, $n = count($rows); $i < $n; $i++) {
//                $row = &$rows[$i];
//                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id' ORDER BY id");
//                $temps = $database->loadObjectList();
//                $status[$i] = JTEXT::_('COM_MATUKIO_PARTICIPANT_ASSURED');
//                $rows[$i]->codepic = $row->bookid;
//                if (count($temps) > $row->maxpupil) {
//                    if ($row->stopbooking == 0) {
//                        for ($l = 0, $m = count($temps); $l < $m; $l++) {
//                            $temp = &$temps[$l];
//                            if ($temp->userid == $my->id) {
//                                break;
//                            }
//                        }
//                        if ($l + 1 > $row->maxpupil) {
//                            $status[$i] = JTEXT::_('COM_MATUKIO_WAITLIST');
//                        }
//                    } else {
//                        $status[$i] = JTEXT::_('COM_MATUKIO_NO_SPACE_AVAILABLE');
//                    }
//                }
//                if ($temps[0]->paid == 1) {
//                    $rows[$i]->fees = $rows[$i]->fees . " - " . JTEXT::_('COM_MATUKIO_PAID');
//                }
//            }
//            break;
//
//        case "653O875032490":
//            MatukioHelperUtilsBasic::checkUserLevel(2);
//            if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
//                $where[] = "a.catid ='$catid'";
//            }
//            $where[] = "a.publisher = '" . $my->id . "'";
//            $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
//                    . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//                    . "\nAND (a.semnum LIKE'%$search%' OR a.teacher LIKE '%$search%' OR a.title LIKE '%$search%' OR a.shortdesc LIKE '%$search%' OR a.description LIKE '%$search%')"
//                    . "\nORDER BY a.begin"
//                    . $ttlimit
//            );
//            $rows = $database->loadObjectList();
//            $status = array();
//            $headertext = JTEXT::_('COM_MATUKIO_MY_OFFERS') . " - " . $my->name;
//            for ($i = 0, $n = count($rows); $i < $n; $i++) {
//                $row = &$rows[$i];
//                $gebucht = sem_f020($row);
//                $gebucht = $gebucht->booked;
//                if ((MatukioHelperUtilsDate::getCurrentDate() > $row->booked) OR ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 1)) {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_UNBOOKABLE');
//                } else if ($row->maxpupil - $gebucht < 1 && $row->stopbooking == 0) {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_BOOKING_ON_WAITLIST');
//                } else {
//                    $status[$i] = JTEXT::_('COM_MATUKIO_NOT_EXCEEDED');
//                }
//                $rows[$i]->codepic = "";
//            }
//            break;
//    }
//    sem_f056($rows, $status, $headertext);
}

// ++++++++++++++++++++++++++
// +++ Zertifikat drucken +++
// ++++++++++++++++++++++++++

function sem_g019()
{
    $cid = JFactory::getApplication()->input->getInt('cid', 5);
    $OIO = JFactory::getApplication()->input->get('OIO', '');
    if ($OIO != "764576O987985") {
        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
        exit;
    }
    sem_f051($cid);
}

// +++++++++++++++++++++++++++++
// +++ AGB anzeigen          +++
// +++++++++++++++++++++++++++++

function sem_g020()
{
//    HTML_FrontMatukio::sem_g020();
}

// +++++++++++++++++++++++++++++
// +++ RSS-Feed erzeugen     +++
// +++++++++++++++++++++++++++++

function sem_g023()
{
//    if (MatukioHelperSettings::getSettings('rss_feed', 1) == 0) {
//        JError::raiseError(403, JText::_("ALERTNOTAUTH"));
//        exit;
//    }
//    $database = JFactory::getDBO();
//    $neudatum = MatukioHelperUtilsDate::getCurrentDate();
//    $where = array();
//    $database->setQuery("SELECT id, access FROM #__categories WHERE extension='" . JRequest::getCmd('option') . "'");
//    $cats = $database->loadObjectList();
//    $allowedcat = array();
//    foreach ($cats AS $cat) {
//        if ($cat->access < 1) {
//            $allowedcat[] = $cat->id;
//        }
//    }
//    if (count($allowedcat) > 0) {
//        $allowedcat = implode(',', $allowedcat);
//        $where[] = "a.catid IN ($allowedcat)";
//    }
//    $where[] = "a.published = '1'";
//    $where[] = "a.end > '$neudatum'";
//    $where[] = "a.booked > '$neudatum'";
//    $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
//            . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
//            . "\nORDER BY a.publishdate DESC"
//    );
//    $rows = $database->loadObjectList();
//    HTML_FrontMatukio::sem_g023($rows);
}

// +++++++++++++++++++++++++++++++++++++++++++++++
// +++ Benutzer ausloggen                      +++
// +++++++++++++++++++++++++++++++++++++++++++++++

function sem_g024()
{
//    $mainframe = JFactory::getApplication();
//    $userid = null;
//    $mainframe->logout($userid);
//    sem_g001(0);
}


?>
