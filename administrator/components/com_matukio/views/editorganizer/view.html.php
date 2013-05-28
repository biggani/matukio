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

class MatukioViewEditOrganizer extends JViewLegacy {

    function display($tpl = null) {

        $model = $this->getModel();

        $organizer = $model->getOrganizer();

        if (!$organizer) {
            $organizer = JTable::getInstance('Organizers', 'Table');
        }

        $this->organizer = $organizer;

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_MATUKIO_EDIT_ORGANIZER'), 'user');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.matukio', true);
    }

}
