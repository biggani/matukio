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

class MatukioViewAGB extends JViewLegacy {

    public function display($tpl = NULL) {

        $this->agb = nl2br(MatukioHelperSettings::getSettings('agb_text', ''));

        parent::display($tpl);
    }
}