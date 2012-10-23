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


class MatukioControllerContactOrganizer extends JController
{
    public function display()
    {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'ContactOrganizer');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('ContactOrganizer', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function sendEmail(){


        $mainframe = JFactory::getApplication();
        $msg = JText::_("COM_MATUKIO_MAIL_TO_ORGANIZER_SEND_SUCCESSFULL");

        jimport('joomla.mail.helper');
        $my = &JFactory::getuser();
        $database = &JFactory::getDBO();
        $cid = JRequest::getInt('event_id', 0);
        $uid = JRequest::getInt('art', 0);
        $text = JMailHelper::cleanBody(nl2br(JRequest::getVar('text', '')));

        if ($text != "") {
            $reason = JTEXT::_('COM_MATUKIO_MESSAGE_SEND');
            $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");

            $kurs = $database->loadObject();

            $subject = "";

            if ($kurs->semnum != "") {
                $subject .= " " . $kurs->semnum;
            }
            $subject .= ": " . $kurs->title;
            $subject = JMailHelper::cleanSubject($subject);
            $sender = $mainframe->getCfg('fromname');
            $from = $mainframe->getCfg('mailfrom');
            if ($my->id == 0) {
                $replyname = $mainframe->getCfg('fromname');
                $replyto = $mainframe->getCfg('mailfrom');
            } else {
                $replyname = $my->name;
                $replyto = $my->email;
            }
            $body = "\n<head>\n<style type=\"text/css\">\n<!--\nbody {\nfont-family: Verdana, Tahoma, Arial;\nfont-size:12pt;\n}\n-->\n</style></head><body>";
            if ($uid == 1 AND $my->id != 0) {
                $body .= "<p><div style=\"font-size: 10pt\">" . JTEXT::_('COM_MATUKIO_QUESTION_ABOUT_EVENT') . "</div><p>";
            }
            $body .= "<div style=\"border: 1px solid #A0A0A0; width: 100%; padding: 5px;\">" . $text . "</div><p>";
            $temp = array();
            // Mail to Organizer
            if ($uid == 1) {
                $body .= MatukioHelperUtilsEvents::getEmailBody($kurs, $temp, $my);
                $publisher = &JFactory::getuser($kurs->publisher);
                $email = $publisher->email;
                JUtility::sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
            } else {
                if (!JFactory::getUser()->authorise('core.create', 'com_matukio.frontend.')) {
                    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                }

                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $kurs->id . "'");
                $rows = $database->loadObjectList();
                foreach ($rows as $row) {
                    if ($row->userid == 0) {
                        $user->email = $row->email;
                        $user->name = $row->name;
                    } else {
                        $user = &JFactory::getuser($row->userid);
                    }
                    $text = $body . MatukioHelperUtilsEvents::getEmailBody($kurs, $row, $user);
                    JUtility::sendMail($from, $sender, $user->email, $subject, $text, 1, null, null, null, $replyto, $replyname);
                }
            }
        } else {
            $msg = JTEXT::_('COM_MATUKIO_MESSAGE_NOT_SEND');
        }

        $link = MatukioHelperUtilsBasic::getSitePath() . "index.php?option=com_matukio&tmpl=component&view=contactorganizer&cid=" . $cid;

//        echo $link;
//        die("asdf");


        $this->setRedirect($link, $msg);
    }

}