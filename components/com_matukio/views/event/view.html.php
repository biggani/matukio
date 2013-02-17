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

class MatukioViewEvent extends JViewLegacy {

    public function display($tpl = NULL) {

        $model = $this->getModel();

        $art = JFactory::getApplication()->input->getInt('art', 0);

        $database = JFactory::getDBO();
        $dateid = JFactory::getApplication()->input->getInt('dateid', 1);
        $cid = JFactory::getApplication()->input->getInt('id', 0);      // Event id
        $uid = JFactory::getApplication()->input->getInt('uid', 0);     // Booking id!!   Dirk.. WTF?!?!?!?!?!

        $booking = "";

        $user = JFactory::getUser();

        if($art == 1){
            $booking = MatukioHelperUtilsBooking::getBooking($uid);
            //var_dump($booking);
        }

        //echo $uid;

        //die("asdf");

        $catid = JFactory::getApplication()->input->getInt('catid', 0);  // category id
        $search = JFactory::getApplication()->input->get('search', '', 'string');
        $limit = JFactory::getApplication()->input->getInt('limit', 5);
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);      // pagination should be updated to JOomla Framework

        $params = JComponentHelper::getParams( 'com_matukio' );
        $menuitemid = JFactory::getApplication()->input->get( 'Itemid' );

        if ($menuitemid)
        {
            $site = new JSite();
            $menu = $site->getMenu();
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
            if (!$database->execute()) {
                JError::raiseError(500, $row->getError());
                exit();
            }

            // Ausgabe des Kurses
            MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_EVENTS'), JRoute::_("index.php?option=com_matukio"));
        } elseif ($art == 1 OR $art == 2)  {
            if ($user->id > 0) {
                MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_MY_BOOKINGS'), JRoute::_("index.php?option=com_matukio&art=1"));
            }
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


        $this->id = $cid;
        $this->art = $art;
        $this->event = $row;
        $this->uid = $uid;
        $this->search = $search;
        $this->catid = $catid;
        $this->limit = $limit;
        $this->limitstart = $limitstart;
        $this->dateid = $dateid;
        $this->ueberschrift = $ueberschrift;
        $this->booking = $booking;
        $this->user = $user;

        parent::display($tpl);
    }
}