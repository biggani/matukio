<?php
/**
 * Matukio - Helper
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die();

class MatukioHelperUtilsBasic
{
    private static $instance;

    public static function getJoomlaVersion()
    {
        $version = new JVersion;
        $joomla = $version->getShortVersion();

        return (substr($joomla, 0, 3));
    }

    // TODO: Change to ACL..
    public static function getUserType($user)
    {
        $userid = $user->get('id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('g.title AS group_name')
            ->from('#__usergroups AS g')
            ->leftJoin('#__user_usergroup_map AS map ON map.group_id = g.id')
            ->where('map.user_id = ' . (int)$userid);
        $db->setQuery($query);
        $ugp = $db->loadObject();
        return $ugp->group_name;
    }

    // TODO: Change to ACL..
    public static function getUserTypeID($user)
    {
        if ($user->get('id') == '')
            return -1;

        $userid = $user->get('id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('g.id AS id')
            ->from('#__usergroups AS g')
            ->leftJoin('#__user_usergroup_map AS map ON map.group_id = g.id')
            ->where('map.user_id = ' . (int)$userid);
        $db->setQuery($query);
        $ugp = $db->loadObject();
        return $ugp->id;
    }

    /**
     * Returns the userTime zone if the user has set one, or the global config one
     * @return mixed
     */
    public static function getTimeZone() {
        $userTz = JFactory::getUser()->getParam('timezone');
        $timeZone = JFactory::getConfig()->getValue('offset');
        if($userTz) {
            $timeZone = $userTz;
        }
        return new DateTimeZone($timeZone);
    }

    public static function getExtensionVersion()
    {
        return MATUKIO_VERSION;
    }

    /**
     * sem_f004()
     * @return mixed
     */

    public static function getSitePath()
    {
        return JURI::ROOT();
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Komponentenverzeichnis ausgeben ++  sem_f005()
    // ++++++++++++++++++++++++++++++++++++++

    public static function getComponentPath()
    {
        return MatukioHelperUtilsBasic::getSitePath() . "components/" . JRequest::getCmd('option') . "/";
    }


    // ++++++++++++++++++++++++++++++++++++++
    // +++ Bildverzeichnis 1 ausgeben     +++        sem_f006
    // ++++++++++++++++++++++++++++++++++++++

    public static function getComponentImagePath()
    {
        return MatukioHelperUtilsBasic::getSitePath() . "media/com_matukio/images/";
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Bildverzeichnis 2 ausgeben     +++        sem_f007
    // ++++++++++++++++++++++++++++++++++++++

    public static function getEventImagePath($art)
    {
        $htxt = "";
        if (MatukioHelperSettings::getSettings('image_path', "") != "" AND $art > 0) {
            $htxt = trim(MatukioHelperSettings::getSettings('image_path', ""), "/") . "/";
        }
        return MatukioHelperUtilsBasic::getSitePath() . "images/" . $htxt;
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Benutzerlevel festlegen        +++
    // ++++++++++++++++++++++++++++++++++++++

    /**
     * @deprecated TODO Move to ACL
     * @return int|string
     */
    public static function getUserLevel()
    {
        // Public
        $reglevel = 0;
        $my = &JFactory::getuser();

        // Zugriffslevel festlegen
        $utype = strtolower($my->usertype);

        // > Joomla 1.5
        if (MatukioHelperUtilsBasic::getJoomlaVersion() != '1.5') {
            $utype = MatukioHelperUtilsBasic::getUserTypeID($my);
        }

        if (MatukioHelperUtilsBasic::getJoomlaVersion() == '1.5') {
            switch ($utype) {
                case "registered":
                    $reglevel = 2;
                    break;
                case "author":
                    $reglevel = 3;
                    break;
                case "editor":
                    $reglevel = 4;
                    break;
                case "publisher":
                    $reglevel = 5;
                    break;
                case "manager":
                    $reglevel = 6;
                    break;
                case "administrator":
                    $reglevel = 7;
                    break;
                case "super administrator":
                    $reglevel = 8;
                    break;
                default:
                    $reglevel = 0;
                    if (MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1) {
                        $reglevel = 1;
                    }
                    break;
            }
        } else {
            $reglevel = $utype;

            if ($utype == -1) {
                $reglevel = 0;
                if (MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1) {
                    $reglevel = 1;
                }
            }
        }
        return $reglevel;
    }


    // ++++++++++++++++++++++++++++++++++++++
    // +++ Auf Benutzerlevel testen       +++ sem_f043($temp)
    // ++++++++++++++++++++++++++++++++++++++

    public static function checkUserLevel($temp)
    {
        $reglevel = MatukioHelperUtilsBasic::getUserLevel();
        if ($reglevel < $temp) {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
            exit;
        }

        if($temp == 0) {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
            exit;
        }
    }


    // ++++++++++++++++++++++++++++++++++
    // +++ Pathway erweitern                sem_f019
    // ++++++++++++++++++++++++++++++++++

    public static function expandPathway($text, $link)
    {
        $mainframe = JFactory::getApplication();
        $pathway = $mainframe->getPathWay();
        $pathway->addItem($text, $link);
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Formularstart ausgeben          sem_f026
    // ++++++++++++++++++++++++++++++++++++++

    public static function printFormstart($art)
    {
        $htxt = "FrontForm";
        if ($art == 2 OR $art == 4) {
            $htxt = "adminForm";
        }
        $type = "";
        if ($art > 2) {
            $type = " enctype=\"multipart/form-data\"";
        }
        echo "<form action=\"\" method=\"post\" name=\"" . $htxt . "\" id=\"" . $htxt . "\"" . $type . ">";
    }

    /**
     * sem_f036 Zufälliges Zeichen
     *
     * @static
     * @return string
     */

    public static function getRandomChar()
    {
        $zufall = "";
        for ($i = 0; $i <= 200; $i++) {
            $gkl = rand(1, 3);
            if ($gkl == 1) {
                $zufall .= chr(rand(97, 121));
            } else if ($gkl == 0) {
                $zufall .= chr(rand(65, 90));
            } else {
                $zufall .= rand(0, 9);
            }
        }
        return $zufall;
    }

    public static function loginUser()
    {
        $mainframe =& JFactory::getApplication();
        $username = JRequest::getVar('semusername', JTEXT::_('USERNAME'));
        $password = JRequest::getVar('sempassword', JTEXT::_('PASSWORD'));
        if ($username != JTEXT::_('USERNAME')) {
            $data['username'] = $username;
            $data['password'] = $password;
            $option['remember'] = true;
            $option['silent'] = true;
            $mainframe->login($data, $option);
        }
    }

    // ++++++++++++++++++++++++++++++++++++++
// +++ Benutzerliste ausgeben         +++          sem_f011
// ++++++++++++++++++++++++++++++++++++++

    public static function getBookedUserList($row)
    {
        $database = &JFactory::getDBO();
        //  $database->setQuery( "SELECT a.*, cc.*, a.id AS sid FROM #__matukio_bookings AS a LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '$row->id' ORDER BY a.id");
        $database->setQuery("SELECT userid AS id FROM #__matukio_bookings WHERE semid = '$row->id'");
        $users = $database->loadObjectList();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return false;
        }
        if ((count($users) >= $row->maxpupil) AND ($row->stopbooking > 0)) {
            $blist = "";
        } else {
            $userout = array();
            if (MatukioHelperSettings::getSettings('booking_ownevents', 1) == 0) {
                $userout[] = $row->publisher;
            }
            foreach ($users as $user) {
                $userout[] = $user->id;
            }
            $where = "";
            if (count($userout) > 0) {
                $userout = implode(',', $userout);
                $where = "\nWHERE id NOT IN ($userout)";
            }
            $database->setQuery("SELECT id AS value, name AS text FROM #__users"
                    . $where
                    . "\nORDER BY name"
            );
            $benutzer = $database->loadObjectList();
            if (count($benutzer)) {
                $benutzer = array_merge($benutzer);
                $blist = JHTML::_('select.genericlist', $benutzer, 'uid', 'class="sem_inputbox" size="1"', 'value', 'text', '');
            } else {
                $blist = "";
            }
        }
        return $blist;
    }



    // ++++++++++++++++++++++++++++++++++++++
// +++ Tooltip erzeugen               +++      sem_f055
// ++++++++++++++++++++++++++++++++++++++

    public static function createToolTip($text)
    {
        $html = "";
        if ($text != "") {
            $text = explode("|", $text);
            if (count($text) > 1) {
                $hinttext = $text[0] . "::" . $text[1];
            } else {
                $hinttext = JTEXT::_('COM_MATUKIO_FIELD_TIP') . "::" . $text[0];
            }
            $html = " <span class=\"editlinktip hasTip\" title=\"" . $hinttext . "\" style=\"text-decoration: none;cursor: help;\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "0012.png\" border=\"0\" style=\"vertical-align: absmiddle;\"/></span>";
        }
        return $html;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe parsen                 +++      sem_f065
// ++++++++++++++++++++++++++++++++++++++

    public static function parseOutput($text, $status)
    {
        preg_match_all("`\[" . $status . "\](.*)\[/" . $status . "\]`U", $text, $ausgabe);
        for ($i = 0; $i < count($ausgabe[0]); $i++) {
            $text = str_replace($ausgabe[0][$i], $ausgabe[1][$i], $text);
        }
        preg_match_all("`\[sem_[^\]]+\](.*)\[/sem_[^\]]+\]`U", $text, $ausgabe);
        for ($i = 0; $i < count($ausgabe[0]); $i++) {
            $text = str_replace($ausgabe[0][$i], "", $text);
        }
        return $text;
    }


    // ++++++++++++++++++++++++++++++++++++++
// +++ Fensterstatus loeschen                    sem_f025
// ++++++++++++++++++++++++++++++++++++++

    public static function getMouseOverWindowStatus($status)
    {
        return "onmouseover=\"window.status='" . $status . "';return true;\" onmouseout=\"window.status='';return true;\"";
    }


    // ++++++++++++++++++++++++++++++++++
    // +++ Text von HTML befreien            sem_f018
    // ++++++++++++++++++++++++++++++++++

    public static function cleanHTMLfromText($text)
    {
        $text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
        $text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text);
        $text = preg_replace('/<!--.+?-->/', '', $text);
        $text = preg_replace('/{.+?}/', '', $text);
        $text = preg_replace('/&nbsp;/', ' ', $text);
        $text = preg_replace('/&amp;/', ' ', $text);
        $text = str_replace("\'", "'", $text);
        $text = str_replace('\"', '"', $text);
        $text = strip_tags($text);
        return $text;
    }


    /**
     * HTML Heaer        sem_f031
     * @return string
     */
    public static function getHTMLHeader()
    {
        $lang = JFactory::getLanguage();
        $html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
        $html .= "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"" . $lang->getName() . "\" lang=\"" . $lang->getName() . "\" >";
        $html .= "\n<head>";
        $html .= "\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />";
        // $html .= sem_f030();           We don't insert css this way!1
        $html .= "\n</head>";
        return $html;
    }

    // ++++++++++++++++++++++++++++++++++
    // +++ Copyright ausgeben         +++
    // ++++++++++++++++++++++++++++++++++

    public static function getCopyright()
    {
        if (MatukioHelperSettings::getSettings('frontend_showfooter', 1) == 1) {
            $html = "<div id=\"copyright_box\">Powered by
           <a href=\"http://compojoom.com\" target=\"_new\">Matukio - Joomla Event Manager</a></div>";
        } else {
            $html = "<div id=\"copyright_box\" style=\"display: none\">Powered by
           <a href=\"http://compojoom.com\" target=\"_new\">Matukio - Joomla Event Manager</a></div>";
        }
        return $html;
    }
}
