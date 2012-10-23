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

jimport('joomla.application.component.controller');


class MatukioControllerEventlist extends JController
{
    public function display()
    {
        MatukioHelperUtilsBasic::loginUser();

        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'eventlist');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Eventlist', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }
}