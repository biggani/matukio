<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.0.0 Stable $
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class MatukioViewEditBookingfield extends JViewLegacy {

    function display($tpl = null) {

        $model = $this->getModel();

        $bookingfield = $model->getBookingfield();

        if (!$bookingfield) {
            $bookingfield = JTable::getInstance('bookingfields', 'Table');
        }

        // Type
        $field_types = array(
            JHTML::_('select.option', 'text', JText::_('COM_MATUKIO_TYPE_TEXT') ),
            JHTML::_('select.option', 'textarea', JText::_('COM_MATUKIO_TYPE_TEXTAREA') ),
            JHTML::_('select.option', 'select', JText::_('COM_MATUKIO_TYPE_SELECT') ),
            JHTML::_('select.option', 'radio', JText::_('COM_MATUKIO_TYPE_RADIO') ),
            JHTML::_('select.option', 'checkbox', JText::_('COM_MATUKIO_TYPE_CHECKBOX') ),
            JHTML::_('select.option', 'spacer', JText::_('COM_MATUKIO_TYPE_SPACER') ),
        );

        $select_fieldtype = JHTML::_('select.genericlist', $field_types, 'type', null, 'value', 'text', $bookingfield->type);

        // Page
        $pages = array(
            JHTML::_('select.option', '1', JText::_('COM_MATUKIO_PAGE_ONE') ),
            JHTML::_('select.option', '2', JText::_('COM_MATUKIO_PAGE_TWO') ),
            JHTML::_('select.option', '3', JText::_('COM_MATUKIO_PAGE_THREE') )
        );

        $select_pages = JHTML::_('select.genericlist', $pages, 'page', null, 'value', 'text', $bookingfield->page);

        $required = JHTML::_('select.booleanlist', 'required', 'class="inputbox"', $bookingfield->required);

        $this->bookingfield = $bookingfield;
        $this->select_type = $select_fieldtype;
        $this->select_page = $select_pages;
        $this->select_required = $required;

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_MATUKIO_EDIT_BOOKINGFIELD'), 'module');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.bookingfields', true);
    }

}
