<?php
/**
* Matukio
* @package Joomla!
* @Copyright (C) 2012 - Yves Hoppe - compojoom.com
* @All rights reserved
* @Joomla! is Free Software
* @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 2.0.0 $
**/
defined( '_JEXEC' ) or die ( 'Restricted access' );

jimport('joomla.application.component.view');

class MatukioViewCalendar extends JViewLegacy {

    public function display($tpl = NULL) {

        $catid = JFactory::getApplication()->input->getInt('catid', 0);
        $user = JFactory::getUser();

        $params = JComponentHelper::getParams( 'com_matukio' );

        $menuitemid = JFactory::getApplication()->input->get( 'Itemid' );
        if ($menuitemid)
        {
            $site = new JSite();
            $menu = $site->getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        // Todo integrate category support - in requests tmpl
        if(empty($catid)){
            $catid = $params->get('catid', 0);
        }

        $ue_title = $params->get('title', 'COM_MATUKIO_CALENDAR_TITLE');

        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_UPCOMING_EVENTS'), "");

        $this->catid = $catid;
        //$this->events = $events;
        $this->user = $user;
        $this->params = $params;
        $this->title = $ue_title;


        parent::display($tpl);
    }
}