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

class MatukioControllerCalendar extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        MatukioHelperUtilsBasic::loginUser();

        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'Calendar');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Calendar', 'MatukioModel');
        $view->setModel($model, true);

        //$tmpl = MatukioHelperSettings::getSettings("event_template", "default");
        //$view->setLayout($tmpl);
        $view->display();
    }

}