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

class MatukioControllerParticipants extends JController
{
    public function display()
    {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'participants');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('participants', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }


    /**
     * Toogle ..
     */
    public function toogleStatusPayed()
    {

        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = &JFactory::getDBO();
        $uid = JRequest::getInt('uid', 0);
        $cid = JRequest::getInt('cid', 0);

        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $uid . "'");
        $row = $database->loadObject();

        if ($row->paid == 0) {
            $paid = 1;
        } else {
            $paid = 0;
        }

        $database->setQuery("UPDATE #__matukio_bookings SET paid='" . $paid . "' WHERE id='" . $uid . "'");
        if (!$database->query()) {
            JError::raiseError(500, $row->getError());
            exit();
        }

        $msg = JTEXT::_("COM_MATUKIO_PAYMENT_STATUS_CHANGED");
        $link = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $cid . "&art=2");

        $this->setRedirect($link, $msg);
        //sem_g010(2, $rows[0]->semid);
    }

    /**
     * Cert user
     */
    public function certificateUser()
    {
        $msg = JTEXT::_("COM_MATUKIO_SEND_USER_CERTIFICATE");

        $database = &JFactory::getDBO();
        $cid = JRequest::getInt('cid', 0);
        $uid = JRequest::getInt('uid', 0);
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $uid . "'");

        $rows = $database->loadObjectList();
        if ($rows[0]->certificated == 0) {
            $cert = 1;
            $certmail = 6;
        } else {
            $cert = 0;
            $certmail = 7;
        }
        $database->setQuery("UPDATE #__matukio_bookings SET certificated='$cert' WHERE id='" . $uid . "'");
        if (!$database->query()) {
            JError::raiseError(500, $database->getError());
            $msg = JTEXT::_("COM_MATUKIO_SAVE_USER_CERTIFICATE_FAILED");
        }
        MatukioHelperUtilsEvents::sendBookingConfirmationMail($rows[0]->semid, $uid, $certmail);

        $link = JRoute::_('index.php?option=com_matukio&view=participants&art=2&uid=' . $uid . "&cid=" . $cid);

        $this->setRedirect($link, $msg);

    }


    public function cancelBookingOrganizer()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $database = &JFactory::getDBO();
        $uid = JRequest::getInt('uid', 0);
        $eventid = JRequest::getInt('cid', 0);

        $link = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $eventid . "&art=2");

        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $uid . "'");
        $rows = $database->loadObjectList();
        if ($rows[0]->userid > 0) {
            MatukioHelperUtilsEvents::sendBookingConfirmationMail($rows[0]->semid, $rows[0]->id, 3);
        }

        $database->setQuery("DELETE FROM #__matukio_bookings WHERE id='" . $uid . "'");
        if (!$database->query()) {
            JError::raiseError(500, $database->getError());
        }

        $msg = JText::_("COM_MATUKIO_CANCEL_BOOKING_SUCCESSFULL");

        $this->setRedirect($link, $msg);

        //sem_g010(2, $rows[0]->semid);
    }


    public function changeBookingOrganizer()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        MatukioHelperUtilsBasic::checkUserLevel(2);

        $art = JRequest::getInt('art', 3);
        $cid = JRequest::getInt('cid', 0);

        $database = &JFactory::getDBO();
        $neu = JTable::getInstance("bookings", "Table");

        //var_dump(JRequest::get( 'post' ));

        if (!$neu->bind(JRequest::get('post'))) {
            return JError::raiseError(500, $database->stderr());
        }

        $uid = JRequest::getInt('uid', 0);

        if ($uid < 0) { // wtf.. DIRK!?!?! Any Sense?!
            $uid *= -1;
        }

        $neu->id = $uid;
        $neu->name = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->name);
        $neu->email = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->email);
        $neu->zusatz1 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz1);
        $neu->zusatz2 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz2);
        $neu->zusatz3 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz3);
        $neu->zusatz4 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz4);
        $neu->zusatz5 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz5);
        $neu->zusatz6 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz6);
        $neu->zusatz7 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz7);
        $neu->zusatz8 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz8);
        $neu->zusatz9 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz9);
        $neu->zusatz10 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz10);
        $neu->zusatz11 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz11);
        $neu->zusatz12 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz12);
        $neu->zusatz13 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz13);
        $neu->zusatz14 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz14);
        $neu->zusatz15 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz15);
        $neu->zusatz16 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz16);
        $neu->zusatz17 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz17);
        $neu->zusatz18 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz18);
        $neu->zusatz19 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz19);
        $neu->zusatz20 = MatukioHelperUtilsBasic::cleanHTMLfromText($neu->zusatz20);

        if (!$neu->check()) {
            return JError::raiseError(500, $database->stderr());
        }
        if (!$neu->store()) {
            return JError::raiseError(500, $database->stderr());
        }

        //die("wtf dirk!12");


        $link = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $cid . "&art=2");

        //$neu->checkin();

        $msg = JTEXT::_("COM_MATUKIO_BOOKING_CHANGED_SUCCESSFULL");

        $this->setRedirect($link, $msg);
    }

}