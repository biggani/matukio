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

class MatukioModelEditBooking extends JModel {

    public function __construct() {
        parent::__construct();
        $uuid = JRequest::getVar('booking_id', '');
        $this->setUuid = $uuid;
    }

    public function getBooking() {
        //                 $dlink = "index.php?option=com_matukio&view=editbooking&booking_id=" . $row->uuid;

        $uuid = JRequest::getVar('booking_id', 0);

        if (empty($this->_data)) {
            $query = $this->_buildQuery($uuid);
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
        }
        return $this->_data;
    }

    private function _buildQuery($uuid) {
        $query = "SELECT * FROM #__matukio_bookings WHERE uuid = '" . $uuid . "'";

        //echo $query;
        return $query;
    }

}