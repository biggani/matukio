<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class MatukioControllerOrganizers extends JControllerLegacy {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
        $this->registerTask('unpublish', 'publish');
        // Register Extra tasks
        $this->registerTask('addOrganizer', 'editOrganizer');
        $this->registerTask('apply', 'save');
    }

    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'Organizers');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Organizers', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function remove() {
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');
        $db = JFactory::getDBO();
        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = "DELETE FROM #__matukio_organizers where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->execute()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
        }
        $msg = JText::_("COM_MATUKIO_ORGANIZERS_SUCCESSFULLY_DELETED");

        $this->setRedirect('index.php?option=com_matukio&view=organizers', $msg);
    }


    public function publish() {
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');

        if ($this->task == 'publish') {
            $publish = 1;
        } else {
            $publish = 0;
        }

        $msg = "";
        $tilesTable = JTable::getInstance('organizers', 'Table');
        $tilesTable->publish($cid, $publish);

        $link = 'index.php?option=com_matukio&view=organizers';

        $this->setRedirect($link, $msg);
    }

    function editOrganizer() {
        $document = JFactory::getDocument();
        $viewName = 'editorganizer'; // hardcoede
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('editorganizer');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    function save() {
        $row = JTable::getInstance('organizers', 'Table');
        $postgal = JRequest::get('post');

        $id = JFactory::getApplication()->input->getInt('id', 0);


        var_dump($postgal);

        if (!$row->bind($postgal)) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        if (!isset($row->published)) {
            $row->published = 1;
        }

        if (!$row->store()) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        switch ($this->task) {
            case 'apply':
                $msg = JText::_('COM_MATUKIO_ORAGANIZER_APPLY');
                $link = 'index.php?option=com_matukio&view=editOrganizer&id=' . $row->id;
                break;

            case 'save':
            default:
                $msg = JText::_('COM_MATUKIO_ORGANIZER_SAVE');
                $link = 'index.php?option=com_matukio&view=organizers';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    function cancel() {
        $link = 'index.php?option=com_matukio&view=organizers';
        $this->setRedirect($link);
    }

}