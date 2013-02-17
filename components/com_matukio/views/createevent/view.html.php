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

class MatukioViewCreateEvent extends JViewLegacy {

    public function display($tpl = NULL) {
//        $model = $this->getModel();
        $my = JFactory::getuser();

        $dateid = JFactory::getApplication()->input->getInt('dateid', 1);
        $catid = JFactory::getApplication()->input->getInt('catid', 0);

        $params = JComponentHelper::getParams( 'com_matukio' );

        $menuitemid = JFactory::getApplication()->input->get( 'Itemid' );
        if ($menuitemid)
        {
            $site = new JSite();
            $menu = $site->getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        if(empty($catid)){
            $catid = $params->get('catid', 0);
        }

        $search = JFactory::getApplication()->input->get('search', '', 'string');
        $limit = JFactory::getApplication()->input->getInt('limit', 5);
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $vorlage = JFactory::getApplication()->input->getInt('vorlage', 0);
        $cid = JFactory::getApplication()->input->getInt('cid', 0);


        if(empty($cid)){
            // Access check for creating new event
            if (!JFactory::getUser()->authorise('core.create', 'com_matukio.frontend.')) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        } else {
            // Access check for editing this event
            if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.', $cid)) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        $row = null;
        // Load event
        if(!empty($cid)){
            $row = JTable::getInstance('matukio', 'Table');
            $row->load($cid);
        } else {
            // New Event
            $row = JTable::getInstance('matukio', 'Table');
        }

        // Ist es eine Vorlage
        if ($vorlage > 0) {
            $row->id = "";
            $row->pattern = "";
        }
        if ($cid < 1) {
            $row->publisher = $my->id;
            $row->semnum = MatukioHelperUtilsEvents::createNewEventNumber(date('Y'));
        }
        $row->vorlage = $vorlage;

        // New Event
        if(empty($row->begin) || $row->begin == "0000-00-00 00:00:00") {
            $row->begin = date("Y-m-d 14:00:00");
            $row->end = date("Y-m-d 17:00:00");
            $row->booked = date("Y-m-d 12:00:00");
        }
        $zeit = explode(" ", $row->begin);
        $row->begin_date = $zeit[0];
        $zeit = explode(":", $zeit[1]);
        $row->begin_hour = $zeit[0];
        $row->begin_minute = $zeit[1];
        $zeit = explode(" ", $row->end);
        $row->end_date = $zeit[0];
        $zeit = explode(":", $zeit[1]);
        $row->end_hour = $zeit[0];
        $row->end_minute = $zeit[1];
        $zeit = explode(" ", $row->booked);
        $row->booked_date = $zeit[0];
        $zeit = explode(":", $zeit[1]);
        $row->booked_hour = $zeit[0];
        $row->booked_minute = $zeit[1];

        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_MY_OFFERS'), "index.php?option=com_matukio&view=myevents");

        if ($cid) {
            MatukioHelperUtilsBasic::expandPathway($row->title, "");
        } else {
            MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_NEW_EVENT'), "");
        }

        //HTML_FrontMatukio::sem_g006($row, $search, $catid, $limit, $limitstart, $dateid);

        $this->event = $row;
        $this->search = $search;
        $this->catid = $catid;
        $this->limit = $limit;
        $this->limitstart = $limitstart;
        $this->dateid = $dateid;

        parent::display($tpl);
    }
}