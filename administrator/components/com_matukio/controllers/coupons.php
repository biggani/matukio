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

class MatukioControllerCoupons extends JController {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
        $this->registerTask('unpublish', 'publish');
        // Register Extra tasks
        $this->registerTask('addCoupon', 'editCoupon');
        $this->registerTask('apply', 'save');
    }

    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'coupons');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Coupons', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    public function remove() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $db = JFactory::getDBO();
        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = "DELETE FROM #__matukio_booking_coupons where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->query()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
        }
        $this->setRedirect('index.php?option=com_matukio&view=coupons');
    }


    public function publish() {
        $cid = JRequest::getVar('cid', array(), '', 'array');

        if ($this->task == 'publish') {
            $publish = 1;
        } else {
            $publish = 0;
        }

        $msg = "";
        $tilesTable = JTable::getInstance('coupons', 'Table');
        $tilesTable->publish($cid, $publish);

        $link = 'index.php?option=com_matukio&view=coupons';

        $this->setRedirect($link, $msg);
    }

    function editCoupon() {
        $document = JFactory::getDocument();
        $viewName = 'editcoupon'; // hardcoede
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);

        $model = $this->getModel('editcoupon');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    function save() {
        $row = JTable::getInstance('coupons', 'Table');
        $postgal = JRequest::get('post');

        $id = JRequest::GetVar('id', 0);


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
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_APPLY');
                $link = 'index.php?option=com_matukio&view=coupons&task=editCoupon&id=' . $row->id;
                break;

            case 'save':
            default:
                $msg = JText::_('COM_MATUKIO_BOOKING_FIELD_SAVE');
                $link = 'index.php?option=com_matukio&view=coupons';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    function cancel() {
        $link = 'index.php?option=com_matukio&view=coupons';
        $this->setRedirect($link);
    }

}