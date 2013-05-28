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

class MatukioViewContactOrganizer extends JViewLegacy {

    public function display($tpl = NULL) {

        $art = JFactory::getApplication()->input->getInt('art', 1);  // should be 1, else it's messages to participants
        $cid = JFactory::getApplication()->input->getInt('cid', 0);

        if(empty($cid) && $this->art != "organizer"){
            JError::raiseError('404', "COM_MATUKIO_NO_ID");
            return;
        }

        if($this->art != "organizer") {
            $database = JFactory::getDBO();
            $database->setQuery("SELECT * FROM #__matukio WHERE id= " . $cid);
            $row = $database->loadObject();

            $this->event = $row;
        } else {
            $this->organizer = MatukioHelperOrganizer::getOrganizerId($cid);
        }

        $this->art = $art;

//        echo $art;

        parent::display($tpl);
    }
}