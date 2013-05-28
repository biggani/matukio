<?php
/**
 * Copyright (C) 2010-2012 Yves Hoppe - All rights reserved
 * User: yh
 * E-Mail: info@yves-hoppe.de
 */

// No direct access to this file
defined('_JEXEC') or die;

abstract class MatukioHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu)
    {
        $language = JFactory::getLanguage();
        $language->load('com_matukio.sys', JPATH_ADMINISTRATOR, null, true);

        $view = JFactory::getApplication()->input->get('task');

        $active2 = ($view == 'controlcenter');
        JSubMenuHelper::addEntry(JText::_('COM_MATUKIO_CONTROLCENTER'), 'index.php?option=com_matukio&view=controlcenter', $active2);

        $subMenus = array(
            '2' => 'COM_MATUKIO_EVENTS',
            'categories' => 'COM_MATUKIO_CATEGORIES',
            //'1' => 'COM_MATUKIO_TEMPLATES',
            'coupons' => 'COM_MATUKIO_COUPONS',
            'settings' => 'COM_MATUKIO_CONFIGURATION',
            'bookingfields' => 'COM_MATUKIO_BOOKINGFIELDS',
            'templates' => 'COM_MATUKIO_TEMPLATES',
            'organizers' => 'COM_MATUKIO_ORGANIZERS',
            '4' => 'COM_MATUKIO_STATISTICS',
            'import' => 'COM_MATUKIO_IMPORT',
            'information' => 'COM_MATUKIO_INFORMATIONS'
        );

        foreach ($subMenus as $key => $name) {
            $active = ($view == $key);
            if ($key == 'categories') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_categories&extension=com_matukio', $active);
            } else if($key == 'bookingfields') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=bookingfields', $active);
            } else if($key == 'coupons') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=coupons', $active);
            } else if($key == 'information') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=information', $active);
            } else if($key == 'templates') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=templates', $active);
            } else if($key == 'organizers') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=organizers', $active);
            } else if($key == 'settings') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=settings', $active);
            } else if($key == 'import') {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&view=import', $active);
            } else {
                JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_matukio&task=' . $key, $active);
            }
        }
        $active = ($view == 'liveupdate');
        JSubMenuHelper::addEntry(JText::_('COM_MATUKIO_LIVEUPDATE'), 'index.php?option=com_matukio&view=liveupdate', $active);

        $active3 = ($view == 'help');
        JSubMenuHelper::addEntry(JText::_('COM_MATUKIO_HELP'), 'index.php?option=com_matukio&view=help', $active3);

        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-matukio ' .
            '{background-image: url(../media/com_matukio/backend/images/icon-48.png);}');

        if ($submenu == 'categories') {
            $document->setTitle(JText::_('COM_MATUKIO_CATEGORIES'));
        }

    }

}