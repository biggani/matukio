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

class MatukioControllerPPayment extends JControllerLegacy
{
    /**
     * @param bool $cachable
     * @param bool $urlparams
     * @return JController|JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = false)
    {
        MatukioHelperUtilsBasic::loginUser();
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'PPayment');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('PPayment', 'MatukioModel');
        $view->setModel($model, true);
        $view->display();
    }

    /**
     * Update Booking status and redirect to event art 1
     */
    public function status(){
        $uuid = JFactory::getApplication()->input->get('uuid', '');
        $pg_plugin = JFactory::getApplication()->input->get('pg_plugin', '');

        $dispatcher = JDispatcher::getInstance();

        // Import the right plugin here!
        JPluginHelper::importPlugin('payment', $pg_plugin);

        $data = $dispatcher->trigger('onTP_Processpayment', array(JRequest::get("post")));

        $model = $this->getModel('PPayment', 'MatukioModel');

        $booking = $model->getBooking($uuid);

        if(empty($booking)) {
            JError::raise(E_ERROR, "500", JText::_("COM_MATUKIO_BOOKING_NOT_FOUND"));
        }

        $event = $model->getEvent($booking->semid);

        $payment_status = $data[0]['status'];

        // Update Payment status
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->update("#__matukio_bookings")->where("uuid = " . $db->quote($uuid))->set("payment_status = " . $db->quote($payment_status));
        $db->setQuery($query);
        $db->execute();

        // $link = $data[0]["return"];  TODO check if this is required? And where this url is used =)

        // Link to event art = 1
        $eventid_l = $event->id . ':' . JFilterOutput::stringURLSafe($event->title);
        $catid_l = $event->catid . ':' . JFilterOutput::stringURLSafe(MatukioHelperCategories::getCategoryAlias($event->catid));

        $link = JRoute::_(MatukioHelperRoute::getEventRoute($eventid_l, $catid_l, 1), false);

        $msg = JText::_("COM_MATUKIO_THANK_YOU");

        $this->setRedirect($link, $msg);
    }

    /**
     * Set booking to canceled?
     * TODO update booking status to delete or canceld?
     */
    public function cancelPayment() {
        $uuid = JFactory::getApplication()->input->get('uuid', '');
        $pg_plugin = JFactory::getApplication()->input->get('pg_plugin', '');

        $model = $this->getModel('PPayment', 'MatukioModel');

        $booking = $model->getBooking($uuid);

        if(empty($booking)) {
            JError::raise(E_ERROR, "500", JText::_("COM_MATUKIO_BOOKING_NOT_FOUND"));
        }

        $event = $model->getEvent($booking->semid);

        // Update status
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->update("#__matukio_bookings")->where("uuid = " . $db->quote($uuid))->set("payment_status = " . $db->quote("P"));  // Set status to pending
        $db->setQuery($query);
        $db->execute();

        // Link to event art = 1
        $eventid_l = $event->id . ':' . JFilterOutput::stringURLSafe($event->title);
        $catid_l = $event->catid . ':' . JFilterOutput::stringURLSafe(MatukioHelperCategories::getCategoryAlias($event->catid));

        $link = JRoute::_(MatukioHelperRoute::getEventRoute($eventid_l, $catid_l, 1), false);

        $msg = JText::_("COM_MATUKIO_THANK_YOU");

        $this->setRedirect($link, $msg);
    }

}