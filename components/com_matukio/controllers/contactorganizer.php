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


class MatukioControllerContactOrganizer extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'ContactOrganizer');
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
        $msg_type = "message";

        jimport('joomla.mail.helper');
        $my = JFactory::getuser();
        $database = JFactory::getDBO();
        $cid = JFactory::getApplication()->input->getInt('event_id', 0);
        $uid = JFactory::getApplication()->input->getInt('art', 0);
        $text = JMailHelper::cleanBody(nl2br(JFactory::getApplication()->input->get('text', '', 'string')));

        $name = JFactory::getApplication()->input->get('name', '', 'string');
        $email = JFactory::getApplication()->input->get('email', '', 'string');

        if ($text != "" && $name != "" && $email != "") {
            $reason = JTEXT::_('COM_MATUKIO_MESSAGE_SEND');
            $database->setQuery("SELECT * FROM #__matukio WHERE id= " . $cid);

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
                $replyname = $name;
                $replyto = $email;

                // Setting it hardcoded for the body function.. dirk you really give me headaches
                $my->name = $name;
                $my->email = $email;

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
                $publisher = JFactory::getuser($kurs->publisher);
                $email = $publisher->email;
                $mailer = JFactory::getMailer();

                $mailer->sendMail($from, $sender, $email, $subject, $body, 1, null, null, null, $replyto, $replyname);
            } else {

                if (!JFactory::getUser()->authorise('core.create', 'com_matukio.frontend.')) {
                    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                }

                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='" . $kurs->id . "'");
                $rows = $database->loadObjectList();
                foreach ($rows as $row) {
                    if ($row->userid == 0) {
                        $user = JFactory::getUser(0);
                        $user->email = $row->email;
                        $user->name = $row->name;
                    } else {
                        $user = JFactory::getuser($row->userid);
                    }
                    $text = $body . MatukioHelperUtilsEvents::getEmailBody($kurs, $row, $user);
                    $mailer = JFactory::getMailer();

                    $mailer->sendMail($from, $sender, $user->email, $subject, $text, 1, null, null, null, $replyto, $replyname);
                }
            }
        } else {
            $msg = JTEXT::_('COM_MATUKIO_MESSAGE_NOT_SEND');
            $msg_type = "error";
        }

        $link = MatukioHelperUtilsBasic::getSitePath() . "index.php?option=com_matukio&tmpl=component&view=contactorganizer&cid=" . $cid;

//        echo $link;
//        die("asdf");


        $this->setRedirect($link, $msg, $msg_type);
    }

}