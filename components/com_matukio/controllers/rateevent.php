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


class MatukioControllerRateEvent extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'RateEvent');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('RateEvent', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function rate(){
        // TODO uncomment
        //MatukioHelperUtilsBasic::checkUserLevel(2);

        $msg = "COM_MATUKIO_RATING_SUCCESSFULL";
        $mainframe = JFactory::getApplication();
        jimport('joomla.mail.helper');
        $my = JFactory::getuser();
        $database = JFactory::getDBO();
        $cid = JFactory::getApplication()->input->getInt('cid', 0);
        $grade = JFactory::getApplication()->input->getInt('grade', 0);
        $text = JFactory::getApplication()->input->get('text', '');
        $text = str_replace(array("\"", "\'"), "", $text);
        $text = JMailHelper::cleanBody($text);
        $database->setQuery("UPDATE #__matukio_bookings SET grade='" .$grade . "', comment='" .$text . "' WHERE semid='"
                            . $cid . "' AND userid='" . $my->id . "'");

        if (!$database->execute()) {
            JError::raiseError(500, $database->getError());
            exit();
        }
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $cid . "'");
        $rows = $database->loadObjectList();
        $zaehler = 0;
        $wertung = 0;
        foreach ($rows AS $row) {
            if ($row->grade > 0) {
                $wertung = $wertung + $row->grade;
                $zaehler = $zaehler + 1;
            }
        }
        if ($zaehler > 0) {
            $geswert = round($wertung / $zaehler);
        } else {
            $geswert = 0;
        }
        $database->setQuery("UPDATE #__matukio SET grade='$geswert' WHERE id='$cid'");

        if (!$database->execute()) {
            JError::raiseError(500, $database->getError());
            $msg = "COM_MATUKIO_RATING_FAILED " . $database->getError();
        }
        if (MatukioHelperSettings::getSettings('sendmail_owner', 1) > 0) {
            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid' AND userid='$my->id'");
            $rows = $database->loadObjectList();
            $buchung = &$rows[0];
            $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
            $rows = $database->loadObjectList();
            $row = &$rows[0];
            $publisher = JFactory::getuser($row->publisher);
            $body = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
            $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_RECEIVED_RATING') . "</div>";
            $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_RATING') . ":</div>";
            $htxt = str_replace('SEM_POINTS', $grade, JTEXT::_('COM_MATUKIO_SEM_POINTS_6'));
            $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $htxt . "</div>";
            $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_COMMENT') . ":</div>";
            $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . htmlspecialchars($text) . "</div>";
            $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_AVARAGE_SCORE') . ":</div>";
            $htxt = str_replace('SEM_POINTS', $geswert, JTEXT::_('COM_MATUKIO_SEM_POINTS_6'));
            $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $htxt . "</div>";
            $body .= "<p>" . MatukioHelperUtilsEvents::getEmailBody($row, $buchung, $my);
            $sender = $mainframe->getCfg('fromname');
            $from = $mainframe->getCfg('mailfrom');
            $replyname = $my->name;
            $replyto = $my->email;
            $email = $publisher->email;
            $subject = JTEXT::_('COM_MATUKIO_EVENT');
            if ($row->semnum != "") {
                $subject .= " " . $row->semnum;
            }
            $subject .= ": " . $row->title;
            $subject = JMailHelper::cleanSubject($subject);
            $mailer = JFactory::getMailer();

            $mailer->sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
        }

        $link = "index.php?option=com_matukio&tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&view=rateevent&cid=" . $cid;

        $this->setRedirect($link, $msg);
    }

}