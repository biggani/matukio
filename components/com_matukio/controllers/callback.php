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

class MatukioControllerCallback extends JController
{
    public function display()
    {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'Callback');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Callback', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function cancel(){
        $uuid = JRequest::getVar('booking_id', '');

        if(empty($uuid)){
            return JError::raiseError('404', "COM_MATUKIO_NO_ID");
        }

        $model = $this->getModel('Callback', 'MatukioModel');
        $booking = $model->getBooking($uuid);
        $uid = $booking->id;
        $link = JRoute::_('index.php?option=com_matukio&view=bookevent&task=cancelBooking&uid=' . $uid . "&return=1");

        $this->setRedirect($link);
    }
}