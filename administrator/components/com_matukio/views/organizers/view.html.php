<?php
/**
 * CAdvancedSlideshow - Helper
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class MatukioViewOrganizers extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null) {
        $this->state = $this->get('State');;
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MATUKIO_ORGANIZERS'), 'user');
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList(JText::_('COM_MATUKIO_DO_YOU_REALLY_WANT_TO_DELETE_THIS_ORGANIZER'));
        JToolBarHelper::addNew('editOrganizer');;
    }
}