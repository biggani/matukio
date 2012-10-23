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

class MatukioViewPrintEventlist extends JView {

    public function display() {

        $cid = JRequest::getInt('cid', 0);
        $todo = JRequest::getVar('todo', '');

        if(empty($cid)){
            JError::_raiseError("COM_MATUKIO_NO_ID");
            return;
        }

        switch ($todo) {
            default:
            case "csvlist":
                // TODO implement userchecking
                $art = JRequest::getInt('art', 0);

                $this->assignRef('art', $art);
                $this->assignRef('cid', $cid);

                $this->setLayout("csv");
                break;
        }

        parent::display();
    }
}