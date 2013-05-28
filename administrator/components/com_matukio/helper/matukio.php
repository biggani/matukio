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

defined('_JEXEC') or die;

/**
 * Banners component helper.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_matukio
 * @since        1.6
 */
class MatukioHelper
{

    /**
     * Configure the Linkbar.
     *
     * @param    string    The name of the active view.
     *
     * @return    void
     * @since    1.6
     */
    public static function addSubmenu($vName)
    {
        JSubMenuHelper::addEntry(
            JText::_('COM_BANNERS_SUBMENU_BANNERS'),
            'index.php?option=com_matukio&view=banners',
            $vName == 'banners'
        );

        JSubMenuHelper::addEntry(
            JText::_('COM_BANNERS_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&extension=com_matukio',
            $vName == 'categories'
        );
        if ($vName == 'categories') {
            JToolBarHelper::title(
                JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_matukio')),
                'banners-categories');
        }

        JSubMenuHelper::addEntry(
            JText::_('COM_BANNERS_SUBMENU_CLIENTS'),
            'index.php?option=com_matukio&view=clients',
            $vName == 'clients'
        );

        JSubMenuHelper::addEntry(
            JText::_('COM_BANNERS_SUBMENU_TRACKS'),
            'index.php?option=com_matukio&view=tracks',
            $vName == 'tracks'
        );
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @param    int        The category ID.
     *
     * @return    JObject
     * @since    1.6
     */
    public static function getActions($categoryId = 0)
    {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($categoryId)) {
            $assetName = 'com_matukio';
        } else {
            $assetName = 'com_matukio.category.' . (int)$categoryId;
        }

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
}