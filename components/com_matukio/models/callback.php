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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class MatukioModelCallback extends JModel {

    public function getEvent($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio')->where('id='.$db->quote($id));
        $db->setQuery($query,0,1);
        return $db->loadObject();
    }

    public function getBooking($uuid){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__matukio_bookings')->where('uuid='.$db->quote($uuid));
        $db->setQuery($query,0,1);

        //$booking = $db->loadObject();
//        echo $query;
//        var_dump($booking);
//        die("asdf");
        return $db->loadObject();;
    }
}