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

jimport('joomla.application.component.controller');

class MatukioControllerMatukio extends JControllerLegacy
{
    // Just for old links
    // could be removed sometime
    public function display($cachable = false, $urlparams = false)
    {
        $task = JFactory::getApplication()->input->get('task', '');
        if(empty($task)){
            MatukioHelperUtilsBasic::loginUser();
            $link = JRoute::_("index.php?option=com_matukio");
            $this->setRedirect($link);
        }
    }

    // For compatibiliy - reimplented
    public function logoutUser()
    {
        $mainframe = JFactory::getApplication();
        $my = JFactory::getuser();
        $mainframe->logout($my->id);
        $link = JRoute::_("index.php?option=com_matukio");
        $msg = JText::_("COM_MATUKIO_LOGOUT_SUCCESS");
        $this->setRedirect($link, $msg);
        //sem_g001(0);
    }

    public function downloadFile() {
        $database = JFactory::getDBO();
        $my = JFactory::getuser();

        $daten = trim(JFactory::getApplication()->input->get('a6d5dgdee4cu7eho8e7fc6ed4e76z', ''));
        $cid = substr($daten, 40);
        $dat = substr($daten, 0, 40);

        $kurs = JTable::getInstance("Matukio", "Table");
        $kurs->load($cid);
        $datfeld = MatukioHelperUtilsEvents::getEventFileArray($kurs);

        for ($i = 0; $i < count($datfeld[0]); $i++) {
            if (sha1(md5($datfeld[0][$i])) == $dat AND ($datfeld[2][$i] == 0
                                OR ($my->id > 0 AND $datfeld[2][$i] > 0))) {
                $datname = $datfeld[0][$i];
                $datcode = "file" . ($i + 1) . "code";
                $daten = base64_decode($kurs->$datcode);
                $datext = array_pop(explode(".", strtolower($datname)));
                header("Content-Type: application/$datext");
                header("Content-Length: " . strlen($daten));
                header("Content-Disposition: attachment; filename=\"$datname\"");
                header('Pragma: no-cache');
                echo $daten;
                exit;
            }
        }
    }

}