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

jimport('joomla.application.component.view');

class MatukioViewEventlist extends JViewLegacy {

    /**
     * @param null $tpl
     * @return mixed|object
     */
    public function display($tpl = NULL) {

        //    function sem_g001($art, $rows, $pageNav, $search, $limit, $limitstart, $total, $datelist, $dateid, $clist, $catid)

        $params = JComponentHelper::getParams( 'com_matukio' );
        $menuitemid = JFactory::getApplication()->input->get( 'Itemid' );
        if ($menuitemid)
        {
            $site = new JSite();
            $menu = $site->getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        $art = JFactory::getApplication()->input->getInt('art', 0); // Hardcoed in Dirk's matukio-mvc.php task

        $database = JFactory::getDBO();
        $dateid = JFactory::getApplication()->input->getInt('dateid', 1);
        $catid = JFactory::getApplication()->input->getInt('catid', 0);
        $uuid = JFactory::getApplication()->input->get('uuid', '', 'string');

//        echo "dateid: " . $dateid;
//        echo "<br />catid: " . $catid;

        if(empty($catid)){
            $catid = $params->get('startcat', 0);
        }

        //echo $catid;

        $search = JFactory::getApplication()->input->get('search', '', 'string');
        $search = str_replace("'", "", $search);
        $search = str_replace("\"", "", $search);

        //echo "Search: " . $search;

        $limit = JFactory::getApplication()->input->getInt('limit', MatukioHelperSettings::getSettings('event_showanzahl', 10));
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $my = JFactory::getuser();
        $neudatum = MatukioHelperUtilsDate::getCurrentDate();
        $where = array();

//        echo "<br />limit: " . $limit;


        // Check if user is logged in and allowed to see it's bookings

        if($art == 1){
            if($my->id == 0 && empty($uuid)){
                JError::raiseError("404", JTEXT::_('COM_MATUKIO_NOT_LOGGED_IN'));
            }
        }

        // Check if user is logged in and allowed to edit his events

        if($art == 2) {
            if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        // Nur veroeffentlichte Kurse anzeigen
        $where[] = "a.published = '1'";
        $where[] = "a.pattern = ''";

        // nur Kurse anzeigen, deren Kategorie fuer den Benutzer erlaubt ist
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $accesslvl = 1;

        if ($reglevel > 2) {
            $accesslvl = 3;
        } elseif ($reglevel > 1) {
            $accesslvl = 2;
        }

        $database->setQuery("SELECT id, access FROM #__categories WHERE extension = '" . JFactory::getApplication()->input->get('option') . "' AND published = 1");
        $cats = $database->loadObjectList();

        //var_dump($cats);

        //die("asdf");
        $allowedcat = array();

        foreach ((array)$cats AS $cat) {
            if ($cat->access <= $accesslvl) {
                $allowedcat[] = $cat->id;
            }
        }

        if (count($allowedcat) > 0) {
            $allowedcat = implode(',', $allowedcat);
            $where[] = "a.catid IN ($allowedcat)";
        }
        switch ($art) {
            default:
            case "0":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavshowmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET'));
                break;
            case "1":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavbookingmodules', 'SEM_NUMBER SEM_SEARCH SEM_TYPES SEM_RESET'));
                break;
            case "2":
                $navioben = explode(" ", MatukioHelperSettings::getSettings('frontend_topnavoffermodules', 'SEM_NUMBER SEM_SEARCH SEM_TYPES SEM_RESET'));
                break;
        }
        switch (MatukioHelperSettings::getSettings('event_stopshowing', 2)) {
            case "0":
                $showend = "a.begin";
                break;
            case "1":
                $showend = "a.end";
                break;
            case "2":
            default:
                $showend = "a.booked";
                break;
        }

        //var_dump($navioben);

        if (in_array('SEM_TYPES', $navioben)) {
            switch ($dateid) {
                case "1":
                    $where[] = "$showend > '$neudatum'";
                    break;
                case "2":
                    $where[] = "$showend <= '$neudatum'";
                    break;
            }
        }
        switch ($art) {
            case "0":

// Gesamte Kurse anzeigen
                if (!in_array('SEM_TYPES', $navioben)) {
                    $where[] = "$showend > '$neudatum'";
                }
                if ((isset($_GET["catid"]) OR in_array('SEM_CATEGORIES', $navioben)) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }
                $leftjoin = "";
                $bookdate = "";
                $anztyp = array(JTEXT::_('COM_MATUKIO_EVENTS'), 0);
                break;
            case "1":

// Gebuchte Kurse anzeigen
                if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }
                $where[] = "cc.userid = '" . $my->id . "'";
                $leftjoin = "\n LEFT JOIN #__matukio_bookings AS cc ON cc.semid = a.id";
                $bookdate = ", cc.bookingdate AS bookingdate, cc.id AS sid";
                $anztyp = array(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), 1);
                break;

            case "2":

// Eingestellte Kurse anzeigen
                if (in_array('SEM_CATEGORIES', $navioben) AND $catid > 0) {
                    $where[] = "a.catid ='$catid'";
                }

                //echo MatukioHelperUtilsBasic::getUserLevel();

                // Todo find a better solution
//                if (MatukioHelperUtilsBasic::getUserLevel() < 6) {
                    $where[] = "a.publisher = '" . $my->id . "'";
//                }
                $leftjoin = "";
                $bookdate = "";
                $anztyp = array(JTEXT::_('COM_MATUKIO_MY_OFFERS'), 2);
                break;
        }
        $suche = "\nAND (a.semnum LIKE '%" . $search . "%' OR a.gmaploc LIKE '%" . $search . "%' OR a.target LIKE '%" . $search . "%' OR a.place LIKE '%" . $search . "%' OR a.teacher LIKE '%" . $search . "%' OR a.title LIKE '%" . $search . "%' OR a.shortdesc LIKE '%" . $search . "%' OR a.description LIKE '%" . $search . "%')";

        $database->setQuery("SELECT a.* FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                . $leftjoin
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . $suche
        );
        $rows = $database->loadObjectList();

        // Abzug der Kurse, die wegen Ausbuchung nicht angezeigt werden sollen
        $abzug = 0;
        $abid = array();
        if ($art == 0) {
            foreach ((array)$rows as $row) {
                if ($row->stopbooking == 2) {
                    $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($row);
                    if ($row->maxpupil - $gebucht->booked < 1) {
                        $abzug++;
                        $abid[] = $row->id;
                    }
                }
            }
        }
        if (count($abid) > 0) {
            $abid = implode(',', $abid);
            $where[] = "a.id NOT IN ($abid)";
        }
        $total = count($rows) - $abzug;

        if (!is_numeric($limitstart)) {
            $limitstart = explode("=", $limitstart);
            $limitstart = end($limitstart);
            if (!is_numeric($limitstart)) {
                $limitstart = 0;
            }
        }
        if ($total <= $limitstart) {
            $limitstart = $limitstart - $limit;
        }
        if ($limitstart < 0) {
            $limitstart = 0;
        }
        $ttlimit = "";
        if ($limit > 0) {
            $ttlimit = "\nLIMIT $limitstart, $limit";
        }
        $pageNav = MatukioHelperUtilsEvents::cleanSiteNavigation($total, $limit, $limitstart);

        $database->setQuery("SELECT a.*, cat.title AS category FROM #__matukio AS a LEFT JOIN #__categories AS cat ON cat.id = a.catid"
                . $leftjoin
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . $suche
                . "\nORDER BY a.begin"
                . $ttlimit
        );
        $rows = $database->loadObjectList();

        // Kursauswahl erstellen
        $allekurse = array();
        $allekurse[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_ALL_EVENTS'));
        $allekurse[] = JHTML::_('select.option', '1', JTEXT::_('COM_MATUKIO_CURRENT_EVENTS'));
        $allekurse[] = JHTML::_('select.option', '2', JTEXT::_('COM_MATUKIO_OLD_EVENTS'));
        $datelist = JHTML::_('select.genericlist', $allekurse, "dateid", "class=\"sem_inputbox\" size=\"1\"
                onchange=\"changeStatus();\"", "value", "text", $dateid);

        // Kategorieliste erstellen
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        $accesslvl = 1;

        if ($reglevel >= 6) {
            $accesslvl = 3;
        } elseif ($reglevel >= 2) {
            $accesslvl = 2;
        }
        $categories[] = JHTML::_('select.option', '0', JTEXT::_('COM_MATUKIO_ALL_CATS'));

        $database->setQuery("SELECT id AS value, title AS text FROM #__categories WHERE extension='" . JFactory::getApplication()->input->get('option') . "' AND published = 1");
        $categs = array_merge($categories, (array)$database->loadObjectList());
        $clist = JHTML::_('select.genericlist', $categs, "catid", "class=\"sem_inputbox\" size=\"1\"
                onchange=\"changeCategoryEventlist();\"", "value", "text", $catid);
        $listen = array($datelist, $dateid, $clist, $catid);

        // Navigationspfad erweitern
        MatukioHelperUtilsBasic::expandPathway($anztyp[0], JRoute::_("index.php?option=com_matukio&view=eventlist"));

        $this->art = $art;
        $this->rows = $rows;
        $this->pageNav = $pageNav;
        $this->search = $search;
        $this->limit = $limit;
        $this->limitstart = $limitstart;
        $this->total = $total;
        $this->datelist = $datelist;
        $this->dateid = $dateid;
        $this->clist = $clist;
        $this->catid = $catid;

        parent::display($tpl);
    }
}