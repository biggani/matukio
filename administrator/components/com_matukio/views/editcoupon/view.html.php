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

class MatukioViewEditCoupon extends JView {

    function display($tpl = null) {

        $model = $this->getModel();

        $coupon = $model->getCoupon();

        if (!$coupon) {
            $coupon = JTable::getInstance('coupons', 'Table');
        }

        $procent = JHTML::_('select.booleanlist', 'procent', 'class="inputbox"', $coupon->procent);

        $this->assignRef('coupon', $coupon);
        $this->assignRef('select_procent', $procent);


        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_MATUKIO_EDIT_COUPON'), 'user');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.coupons', true);
    }

}
