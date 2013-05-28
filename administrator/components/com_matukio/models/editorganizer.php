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

defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class MatukioModelEditOrganizer extends JModelLegacy {

    public function __construct() {
        parent::__construct();
        $this->setId = JFactory::getApplication()->input->getInt('id', 0);
    }

    public function getOrganizer() {
        $id =  JFactory::getApplication()->input->getInt('id', 0);

        if (empty($this->_data)) {
            $query = $this->_buildQuery($id);
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
        }
        return $this->_data;
    }

    private function _buildQuery($id) {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select("*")->from($db->quoteName("#__matukio_organizers"))->where("id = " . $id);

        return $query;
    }

}