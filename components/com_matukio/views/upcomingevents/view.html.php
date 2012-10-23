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

class MatukioViewUpcomingEvents extends JView {

    public function display() {

        $catid = JRequest::getInt('catid', 0);
        $user = JFactory::getUser();
        $ue_title="COM_MATUKIO_UPCOMING_EVENTS";

        $params = &JComponentHelper::getParams( 'com_matukio' );

        $menuitemid = JRequest::getInt( 'Itemid' );
        if ($menuitemid)
        {
            $menu = JSite::getMenu();
            $menuparams = $menu->getParams( $menuitemid );
            $params->merge( $menuparams );
        }

        if(empty($catid)){
            $catid = $params->get('catid', 0);
        }

        $ue_title = $params->get('title', 'COM_MATUKIO_UPCOMING_EVENTS');
        $number = $params->get('number', 10);
        $orderby = $params->get('orderby', 'begin ASC');

        $model = $this->getModel();
        $events = $model->getEvents($catid, $number, $orderby);

        MatukioHelperUtilsBasic::expandPathway(JTEXT::_('COM_MATUKIO_UPCOMING_EVENTS'), "");

        $this->assignRef('catid', $catid);
        $this->assignRef('events', $events);
        $this->assignRef('user', $user);
        $this->assignRef('title', $ue_title);


        parent::display();
    }
}