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

defined( '_JEXEC' ) or die ( 'Restricted access' );

jimport('joomla.application.component.view');

class MatukioViewEvent extends JView {

    public function display($tmpl = null) {

        $model = $this->getModel();

        $art = JRequest::getint('art', 0);

        $database = JFactory::getDBO();
        $dateid = JRequest::getInt('dateid', 1);
        $cid = JRequest::getInt('id', 0);      // Event id
        $uid = JRequest::getInt('uid', 0);     // Booking id!!   Dirk.. WTF?!?!?!?!?!

        $booking = "";

        if($art == 1){
            $booking = MatukioHelperUtilsBooking::getBooking($uid);
            //var_dump($booking);
        }

        //echo $uid;

        //die("asdf");

        $catid = JRequest::getInt('catid', 0);  // category id
        $search = JRequest::getVar('search', '');
        $limit = JRequest::getInt('limit', 5);
        $limitstart = JRequest::getInt('limitstart', 0);      // pagination should be updated to JOomla Framework

        $params = &JComponentHelper::getParams( 'com_matukio' );
        $menuitemid = JRequest::getInt( 'Itemid' );

        if ($menuitemid)
        {
            $menu = JSite::getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        $menu_cid = $params->get('eventid', 0);

        if(empty($cid)){
            if(empty($menu_cid)) {
                JError::raiseError('404', JTEXT::_("COM_MATUKIO_NO_ID"));
                return;
            } else {
                $cid = $menu_cid;
            }
        }

        $row = $model->getItem($cid);

        if ($art == 3) {
            if ($uid > 0) {
                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $uid . "'");
                $temp = $database->loadObjectList();
                $userid = $temp[0]->userid;
                if ($userid == 0) {
                    $uid = $uid * -1;
                } else {
                    $uid = $userid;
                }
            }
        } else {
            if ($uid > 0) {
                $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='$uid'");
                $temp = $database->loadObjectList();
                if($temp[0]->userid != 0 || $art != 1) {
                    $uid = $temp[0]->userid;
                } else {
                    $uid = $uid * -1;
                }
            }
        }
        if ($art == 0) {
            // Hits erhoehen
            $database->setQuery("UPDATE #__matukio SET hits=hits+1 WHERE id='$cid'");
            if (!$database->query()) {
                JError::raiseError(500, $row->getError());
                exit();
            }

            // Ausgabe des Kurses
            MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
        } elseif ($art == 1 OR $art == 2) {
            MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), JRoute::_("index.php?option=com_matukio&art=1"));
        } else {
            MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_MY_OFFERS'), JRoute::_("index.php?option=com_matukio&art=2"));
        }
        MatukioHelperUtilsBasic::expandPathway($row->title, "");

        $ueberschrift = array(JTEXT::_('COM_MATUKIO_DESCRIPTION'), $row->shortdesc);

        //  HTML_FrontMatukio::sem_g002($art, $row, $uid, $search, $catid, $limit, $limitstart, $dateid, $ueberschrift);

        if(empty($row)){
            JError::raiseError('404', JTEXT::_("COM_MATUKIO_NO_ID"));
            return;
        }

        $user = JFactory::getUser();

        $this->assignRef('id', $cid);
        $this->assignRef('art', $art);
        $this->assignRef('event', $row);
        $this->assignRef('uid', $uid);
        $this->assignRef('search', $search);
        $this->assignRef('catid', $catid);
        $this->assignRef('limit', $limit);
        $this->assignRef('limitstart', $limitstart);
        $this->assignRef('dateid', $dateid);
        $this->assignRef('ueberschrift', $ueberschrift);
        $this->assignRef('booking', $booking);
        $this->assignRef('user', $user);

        parent::display($tmpl);
    }
}