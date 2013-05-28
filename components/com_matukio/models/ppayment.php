<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 $
 **/


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class MatukioModelPPayment extends JModelLegacy {

    /**
     * Get Booking on uuid
     * @param $id
     * @return mixed
     */
    public function getBooking($uuid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio_bookings')->where('uuid='.$db->quote($uuid));
        $db->setQuery($query,0,1);
        return $db->loadObject();
    }

    /**
     * Get Event on id
     * @param $id
     * @return mixed
     */
    public function getEvent($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio')->where('id='.$db->quote($id));
        $db->setQuery($query,0,1);
        return $db->loadObject();
    }
}
