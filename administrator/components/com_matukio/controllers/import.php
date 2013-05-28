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

class MatukioControllerImport extends JControllerLegacy {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
    }

    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'Import');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Import', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    /**
     * $cattable->id = null;
    $cattable->asset_id = 0;
    $cattable->parent_id = 0;
    $cattable->lft = 0;
    $cattable->rgt = 0;
    $cattable->level = 1;
    $cattable->path = "test";
    $cattable->extension = "com_matukio";
    $cattable->title = "";
    $cattable->alias = "";
    $cattable->note = "";
    $cattable->description = "";
    $cattable->published = "";
    $cattable->checked_out = 0;
    $cattable->checked_out_time = "0000-00-00 00:00:00";
    $cattable->access = "";
    $cattable->params = "";

    $cattable->metadesc = "";
    $cattable->metakey = "";
    $cattable->metadata = "";
    $cattable->created_user_id = "";
    $cattable->created_time = "";
    $cattable->modified_user_id = "";
    $cattable->hits = "";
    $cattable->language = "";
     */

    public function importseminar() {
        $input = JFactory::getApplication()->input;
        $db = JFactory::getDbo();

        $seminar_table = $input->get('seminar_table', '');
        $seminar_category_table = $input->get('seminar_category_table', '');
        $seminar_booking_table = $input->get('seminar_booking_table', '');
        $seminar_number_table = $input->get('seminar_number_table', '');

        // Load old categories
        $query = $db->getQuery(true);
        $query->select("*")->from($seminar_category_table)->where("section = " . $db->quote("com_seminar"));
        $db->setQuery($query);

        $cats = $db->loadObjectList();

        $insert_id = null;
        $relationsDb = array();
        $user = JFactory::getUser();
        $i = 0;
        $table = JTable::getInstance('Category', 'JTable');
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('content');

        foreach($cats as $cat) {
            // Import category into Joomla 2.5 #__categories table

            $old_id = $cat->id;

            $cat->name = html_entity_decode($cat->title);
            $cat->path = $cat->name;
            $cat->alias = $cat->alias;
            $cat->parent = 1;
            $cat->author = $user->id;

            $new_id = $this->insertCategory($cat);

            if($new_id == -1) break;

            $dispatcher->trigger('onContentAfterSave', array('com_content.category.'.$insert_id, &$table, true));

            $relationsDb[] = $db->quote($new_id) . ',' . $i . ',' . $old_id;

            // Get the events for the category
            $query = $db->getQuery(true);
            $query->select("*")->from($seminar_table)->where("catid = " . $old_id);
            $db->setQuery($query);

            $events = $db->loadObjectList();

            foreach($events as $event){
                $mattab = JTable::getInstance('Matukio', 'Table');

                $old_event_id = $event->id;

                // reseting event id
                $event->id = null;

                if (!$mattab->bind($event)) {
                    JError::raiseError(500, $mattab->getError());
                }

                $event->created_by = $user->id;
                $event->catid = $new_id;

                if (!$mattab->check()) {
                    JError::raiseError(500, $db->stderr());
                }
                if (!$mattab->store()) {
                    JError::raiseError(500, $db->stderr());
                }

                $new_event_id = $mattab->id;

                // Get the event bookings for this event
                $query = $db->getQuery(true);
                $query->select("*")->from($seminar_booking_table)->where("semid = " . $old_event_id);
                $db->setQuery($query);

                $bookings = $db->loadObjectList();

                foreach($bookings as $booking) {

                    $booking->id = null; // Reset
                    $booking->semid = $new_event_id;
                    $booking->uuid = MatukioHelperPayment::getUuid(true);

                    $booking->payment_brutto = $mattab->fees * $booking->nrbooked; // Calculating payment

                    $booktable = JTable::getInstance('Bookings', 'Table');

                    if (!$booktable->bind($booking)) {
                        JError::raiseError(500, $booktable->getError());
                    }

                    if (!$booktable->check()) {
                        JError::raiseError(500, $db->stderr());
                    }

                    if (!$booktable->store()) {
                        JError::raiseError(500, $db->stderr());
                    }
                }

            }


            $i++;
        }

        // Import Numbers

        $query = $db->getQuery(true);
        $query->select("*")->from($seminar_number_table);
        $db->setQuery($query);

        $numbers = $db->loadObjectList();

        foreach($numbers as $number){
            $numtable = JTable::getInstance("Number", "Table");

            if (!$numtable->bind($number)) {
                JError::raiseError(500, $numtable->getError());
            }

            if (!$numtable->check()) {
                JError::raiseError(500, $db->stderr());
            }

            if (!$numtable->store()) {
                JError::raiseError(500, $db->stderr());
            }
        }

        $msg = JText::_("COM_MATUKIO_IMPORT_SUCCESSFULLY");
        $link = 'index.php?option=com_matukio&view=import';
        $this->setRedirect($link, $msg);

    }

    /**
     * @param $category
     * @return int
     */
    public function insertCategory($category) {
        $ret = -1;
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->insert('#__categories');
        $query->set('`title`=' . $db->quote($category->name));
        $query->set('`alias`=' . $db->quote($category->alias));
        $query->set('`parent_id`=' . $db->quote($category->parent));
        $query->set('`extension`= "com_matukio"');
        $query->set('`published`= 1');
        $query->set('`created_user_id`=' . $db->quote($category->author));
        $query->set('`access`=1');
        $query->set('`language`="*"');
        $db->setQuery($query);

        if($db->execute()) {
            $ret = $db->insertId();
        } else {
            $ret = -1;
            echo ('Category with an title ' . $db->quote($category->name) . 'could not be added into your database!\n' . $db->getErrorMsg());
        }

        return $ret;
    }

    function cancel() {
        $link = 'index.php?option=com_matukio';
        $this->setRedirect($link);
    }

}