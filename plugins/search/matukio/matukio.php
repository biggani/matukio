<?php

/**
 * Matukio - Search plugin
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
jimport('joomla.plugin.plugin');

jimport('joomla.database.table');

require_once(JPATH_ADMINISTRATOR . '/components/com_matukio/helper/settings.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_matukio/helper/util_basic.php');

$app = JFactory::getApplication('site');

class plgSearchMatukio extends JPlugin
{

// Bereiche durchsuchen
    function onContentSearchAreas()
    {
        static $areas = array('matukio' => 'Matukio');
        return $areas;
    }

// Seminarfelder durchsuchen
    function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        if (is_array($areas)) {
            if (!array_intersect($areas, array_keys(plgSearchSeminarAreas()))) {
                return array();
            }
        }

// Kein Suchtext vorhanden
        $text = trim($text);
        if ($text == "") {
            return array();
        }

// Vorbereitungen
        $database = JFactory::getDBO();
        $my = JFactory::getuser();
        $app = JFactory::getApplication();
        $offset = $app->getCfg('offset');
        if (MatukioHelperSettings::getSettings('date_format_summertime', 1) > 0) {
            $jahr = date("Y");
            $sombeginn = mktime(2, 0, 0, 3, 31 - date('w', mktime(2, 0, 0, 3, 31, $jahr)), $jahr);
            $somende = mktime(2, 0, 0, 10, 31 - date('w', mktime(2, 0, 0, 10, 31, $jahr)), $jahr);
            $aktuell = time();
            if ($aktuell > $sombeginn AND $aktuell < $somende) {
                $offset++;
            }
        }
        $date = JFactory::getDate();
        $date->setOffset($offset);
        $neudatum = $date->Format;
        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_matukio&view=eventlist'");
        $tempitemid = $database->loadResult();

        $slimit = $this->params->get('search_limit', 50);
        $sname = $this->params->get('search_name', 'Matukio');

        // Check category ACL rights
        $groups	= implode(',', $my->getAuthorisedViewLevels());
        $query = $database->getQuery(true);
        $query->select("id, access")->from("#__categories")->where(array ("extension = " . $database->quote(JFactory::getApplication()->input->get('option')),
            "published = 1", "access in (" . $groups . ")"));

        $database->setQuery($query);
        $cats = $database->loadObjectList();

        $allowedcat = array();

        foreach ((array)$cats AS $cat) {
            $allowedcat[] = $cat->id;
        }

        $where[] = "a.catid IN (" . implode(',', $allowedcat) . ")";
        $where[] = "a.published = '1'";
        $where[] = "a.pattern = ''";

        switch (MatukioHelperSettings::getSettings('event_stopshowing', 2)) {
            case "0":
                $showend = "a.begin";
                break;
            case "1":
                $showend = "a.booked";
                break;
            case "3":
                $showend = "";
                break;

            default:
                $showend = "a.end";
                break;
        }
        $where[] = "$showend > '$neudatum'";

// Sortierung festlegen
        $order = '';
        switch ($ordering) {
            case 'newest':
                $order = 'ORDER BY a.id DESC';
                break;
            case 'oldest':
                $order = 'ORDER BY a.id';
                break;
            case 'popular':
                $order = 'ORDER BY a.hits';
                break;
            case 'alpha':
                $order = 'ORDER BY title';
                break;
            case 'category':
                $order = 'ORDER BY category';
                break;
        }

        switch ($phrase) {
            case 'exact':
                $text = preg_replace('/\s/', ' ', trim($text));
                $suche = "\nAND (a.semnum LIKE '%" . $text . "%' OR a.gmaploc LIKE '%" . $text . "%' OR a.target LIKE '%" . $text
                    . "%' OR a.place LIKE '%" . $text . "%' OR a.teacher LIKE '%" . $text . "%' OR a.title LIKE '%" . $text
                    . "%' OR a.shortdesc LIKE '%" . $text . "%' OR a.description LIKE '%" . $text . "%')";
                break;
            case 'all':
            case 'any':
            default:
                $text = preg_replace('/\s\s+/', ' ', trim($text));
                $words = explode(' ', $text);
                $suche = array();
                foreach ($words as $word) {
                    $word = $database->Quote('%' . $database->getEscaped($word, true) . '%', false);
                    $suche2 = array();
                    $suche2[] = "a.semnum LIKE $word";
                    $suche2[] = "a.gmaploc LIKE $word";
                    $suche2[] = "a.target LIKE $word";
                    $suche2[] = "a.place LIKE $word";
                    $suche2[] = "a.teacher LIKE $word";
                    $suche2[] = "a.title LIKE $word";
                    $suche2[] = "a.shortdesc LIKE $word";
                    $suche2[] = "a.description LIKE $word";
                    $suche3[] = implode(' OR ', $suche2);
                }
                $suche = "\nAND (" . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $suche3) . ")";
                break;
        }

// Rueckgabe des Suchergebnisses
        // TODO find a solution for multiple menu entries and old ones.. SEF..
        $database->setQuery("SELECT a.title AS title,"
                . " a.begin AS begin,"
                . " a.publishdate AS created,"
                . " a.shortdesc AS text,"
                . " CONCAT( 'index.php?option=com_matukio&Itemid=" . $tempitemid . "&view=event&id=',a.id) AS href,"
                . " '2' AS browsernav,"
                . " '" . $sname . "' AS section,"
                . " b.title AS category"
                . " FROM #__matukio AS a"
                . " LEFT JOIN #__matukio AS b ON b.id = a.catid"
                . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
                . $suche
                . $order
                . " LIMIT 0, " . $slimit
        );
        $rows = $database->loadObjectList();
        for ($i = 0; $i < count($rows); $i++) {
            $date = JFactory::getDate($rows[$i]->begin);
            $rows[$i]->section = $rows[$i]->section . " - " . $date->Format;
            $rows[$i]->Itemid = $tempitemid;
        }
        return $rows;
    }
}