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


defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class MatukioModelOrganizers extends JModelList {

    protected function populateState($ordering = null, $direction = null) {

        $search = $this->getUserStateFromRequest('com_matukio.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        parent::populateState('n.name', 'asc');
    }

    protected function getListQuery() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // jos_matukio_organizers
        $query->select('*')->from('#__matukio_organizers AS n');

        $orderCol = $this->state->get('list.ordering');
        $orderDir = $this->state->get('list.direction');

        $published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        $search = $this->getState('filter.search');

        if(!empty($search)) {
            $search = $db->quote('%'.$db->escape($search, true) . '%');
            $query->where('n.name LIKE ' . $search);
        }

        if($published != "" && $published != "*")
            $query->where('published = ' . $db->quote($published));

        if(!empty($orderCol))
            $query->order($orderCol. ' ' . $orderDir);

        return $query;
    }
}