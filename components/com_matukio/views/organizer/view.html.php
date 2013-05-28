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

class MatukioViewOrganizer extends JViewLegacy {

    public function display($tpl = NULL) {

        $orga_id = JFactory::getApplication()->input->get('id', 0);

        $model = $this->getModel();

        $params = JComponentHelper::getParams( 'com_matukio' );

        if(empty($orga_id)) {
            $orga_id = $params->get('id', 0);
        }

        // Raise error
        if(empty($orga_id)) {
            JError::raise(E_ERROR, 403, JText::_("COM_MATUKIO_NO_ID"));
        }

        $organizer = $model->getOrganizer($orga_id);

        if(!empty($organizer))
            $organizer_user = JFactory::getUser($organizer->userId);  // Get the Joomla user obj
        else
            $organizer_user = null;

        $ue_title = $params->get('title', '');


        if(empty($ue_title)){
            $ue_title = $organizer->name; //Set the title to the name
        }

        $this->organizer = $organizer;
        $this->organizer_user = $organizer_user;
        $this->title = $ue_title;

        parent::display($tpl);
    }
}