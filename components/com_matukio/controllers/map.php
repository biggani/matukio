<?php
/**
 * @author Daniel Dimitrov
 * @date: 29.03.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class MatukioControllerMap extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        $model = $this->getModel('Event', 'MatukioModel');
        $eventId = JFactory::getApplication()->input->getInt('event_id');
        $event = $model->getItem($eventId);
        $view = $this->getView('Map', 'html', 'MatukioView');

        $view->event = $event;
        $view->display();
    }
}