<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class MatukioControllerContactPart extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param bool $cachable
     * @param bool $urlparams
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'ContactPart');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('ContactPart', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }


    public function send() {

    }

    function cancel() {
        $link = 'index.php?option=com_matukio&task=29&uid=' . JFactory::getApplication()->input->getInt('event_id', 0);
        $this->setRedirect($link);
    }

}