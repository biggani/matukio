<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 beta $
 **/
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class MatukioViewSettings extends JViewLegacy {

	public function display($tpl = null) {

        $items = $this->get('Data');

        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            if ($item->catdisp == "basic") {
                $items_basic[$item->id] = $item;
            }
            if ($item->catdisp == "layout") {
                $items_layout[$item->id] = $item;
            }

            if ($item->catdisp == "advanced") {
                $items_advanced[$item->id] = $item;
            }

            if ($item->catdisp == "security") {
                $items_security[$item->id] = $item;
            }

            if ($item->catdisp == "payment") {
                $items_payment[$item->id] = $item;
            }

            if ($item->catdisp == "modernlayout") {
                $items_modernlayout[$item->id] = $item;
            }
        }

		$this->items = $items;
		$this->items_basic = $items_basic;
		$this->items_layout = $items_layout;
        $this->items_modernlayout = $items_modernlayout;
        $this->items_advanced = $items_advanced;
		$this->items_security = $items_security;
		$this->items_payment = $items_payment;

        $this->addToolbar();

        parent::display($tpl);
	}

    public function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MATUKIO_SETTINGS'), 'config');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel');
        JToolBarHelper::preferences("com_matukio");
    }

}