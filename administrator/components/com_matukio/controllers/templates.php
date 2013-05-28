<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class MatukioControllerTemplates extends JControllerLegacy {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
        $this->registerTask('apply', 'save');
    }

    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'templates');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Templates', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    function save() {
        $subjectArray = JRequest::getVar('subject', array(0), 'post', 'array');
        $valueArray = JRequest::getVar('value', array(0), 'post', 'array');
        $value_textArray = JRequest::getVar('value_text', array(0), 'post', 'array');

        $row = JTable::getInstance('templates', 'Table');

        foreach ($subjectArray as $key => $subject) {
            $data['id'] = $key;
            $data['subject'] = $subject;
            $data['value'] = $valueArray[$key];
            $data['value_text'] = $value_textArray[$key];

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        switch ($this->task) {
            case 'apply':
                $msg = JText::_('COM_MATUKIO_TEMPLATES_FIELD_APPLY');
                $link = 'index.php?option=com_matukio&view=templates';
                break;

            case 'save':
            default:
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_SAVE');
                $link = 'index.php?option=com_matukio&view=templates';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    function cancel() {
        $link = 'index.php?option=com_matukio';
        $this->setRedirect($link);
    }

}