<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class MatukioViewContactpart extends JViewLegacy {

    function display($tpl = null) {
        $appl = JFactory::getApplication();
        $uri = JFactory::getURI();
        $model = $this->getModel();

        $this->participants = $model->getParticipants();
        $this->event_id = JFactory::getApplication()->input->getInt('event_id', 0);

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_MATUKIO_CONTACT_PARTICIPANTS'), 'send');
        JToolbarHelper::custom("send", 'send.png', 'send.png', JText::_("COM_MATUKIO_SEND"), false);
        JToolbarHelper::cancel();
    }
}