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
jimport('joomla.application.component.modeladmin');

class MatukioModelContactPart extends JModelLegacy {

    public function __construct() {
        parent::__construct();
    }

    public function getParticipants() {
        $event_id = JFactory::getApplication()->input->getInt('event_id', 0);

        if (empty($this->_data)) {
            $query = $this->_buildQuery($event_id);
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObjectList();
        }
        return $this->_data;
    }

    private function _buildQuery($event_id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")->from("#__matukio_bookings")->where("semid = " . $event_id, "status = " . MatukioHelperUtilsBooking::$BOOKED);
        return $query;
    }

}