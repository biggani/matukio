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


class MatukioControllerCreateEvent extends JControllerLegacy
{
    /**
     * @param bool $cachable
     * @param bool $urlparams
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get('view', 'createevent');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Createevent', 'MatukioModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    /**
     * @return object
     */
    public function unpublishEvent() {

        $msg = "COM_MATUKIO_EVENT_UNPUBLISH_SUCCESS";

        $database = JFactory::getDBO();
        $my = JFactory::getuser();
        $cid = JFactory::getApplication()->input->getInt('cid', 0);
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.', $cid)) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }
        $vorlage = JFactory::getApplication()->input->getInt('vorlage', 0);
        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
        $rows = $database->loadObjectList();
        $aktsem = &$rows[0];
        $neudatum = MatukioHelperUtilsDate::getCurrentDate();
        if ($neudatum < $aktsem->begin AND $vorlage == 0) {
            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$cid'");
            $rows = $database->loadObjectList();
            for ($i = 0, $n = count($rows); $i < $n; $i++) {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($cid, $rows[$i]->id, 4);
            }
        }
        $database->setQuery("UPDATE #__matukio SET published=0 WHERE id='" . $cid . "'");

        if (!$database->execute()) {
            JError::raiseError(500, $database->getError());
            $msg = "COM_MATUKIO_EVENT_UNPUBLISH_FAILURE_" . $database->getError();
            exit();
        }

        $link = JRoute::_("index.php?com_matukio&art=2");

        $this->setRedirect($link, $msg);
    }

    /**
     * @return object
     */
    public function duplicateEvent() {
        $msg = "COM_MATUKIO_EVENT_DUPLICATE_SUCCESS";

        $database = JFactory::getDBO();
        $cid = JFactory::getApplication()->input->getInt('cid', 0);

        // Check authorise
        if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.', $cid)) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }
        $database->setQuery("SELECT * FROM #__matukio WHERE id='$cid'");
        $item = $database->loadObject();

        if ($database->getErrorNum()) {
            JError::raiseError(500, $database->getError());
            $msg = "COM_MATUKIO_EVENT_DUPLICATE_FAILURE_" . $database->getError();
        }

        $item->id = null;

        $row = JTable::getInstance('matukio', 'Table');

        if (!$row->bind($item)) {
            JError::raiseError(500, $row->getError());
            $msg = "COM_MATUKIO_EVENT_DUPLICATE_FAILURE_" . $row->getError();
        }
        $row->id = NULL;
        $row->hits = 0;
        $row->grade = 0;
        $row->certificated = 0;
        $row->sid = $item->id;
        $row->publishdate = MatukioHelperUtilsDate::getCurrentDate();
        $row->semnum = MatukioHelperUtilsEvents::createNewEventNumber(date('Y'));

        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
            $msg = "COM_MATUKIO_EVENT_DUPLICATE_FAILURE_" . $row->getError();
        }
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
            $msg = "COM_MATUKIO_EVENT_DUPLICATE_FAILURE_" . $row->getError();
        }

        $link = JRoute::_("index.php?com_matukio&art=2");

        $this->setRedirect($link, $msg);
    }

    /**
     * @return bool|object
     */
    public function saveEvent() {

        $database = JFactory::getDBO();
        $my = JFactory::getuser();
        $cid = JFactory::getApplication()->input->getInt('cid', 0);


        $msg = JTEXT::_("COM_MATUKIO_EVENT_SAVED");
        if(empty($cid)){
            if (!JFactory::getUser()->authorise('core.create', 'com_matukio.frontend.')) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        } else {
            if (!JFactory::getUser()->authorise('core.edit', 'com_matukio.frontend.', $cid)) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        $caid = JFactory::getApplication()->input->getInt('caid', 0);
        $cancel = JFactory::getApplication()->input->getInt('cancel', 0);
        $inform = JFactory::getApplication()->input->getInt('inform', 0);
        $infotext = MatukioHelperUtilsBasic::cleanHTMLfromText(JFactory::getApplication()->input->get('infotext', '', 'string'));
        $deldatei1 = JFactory::getApplication()->input->getInt('deldatei1', 0);
        $deldatei2 = JFactory::getApplication()->input->getInt('deldatei2', 0);
        $deldatei3 = JFactory::getApplication()->input->getInt('deldatei3', 0);
        $deldatei4 = JFactory::getApplication()->input->getInt('deldatei4', 0);
        $deldatei5 = JFactory::getApplication()->input->getInt('deldatei5', 0);
        $vorlage = JFactory::getApplication()->input->getInt('vorlage', 0);
        $neudatum = MatukioHelperUtilsDate::getCurrentDate();

        // Zeit formatieren

        $_begin_date = JFactory::getApplication()->input->get('_begin_date', '0000-00-00 00:00:00', 'string');
        $_end_date = JFactory::getApplication()->input->get('_end_date', '0000-00-00 00:00:00', 'string');
        $_booked_date = JFactory::getApplication()->input->get('_booked_date', '0000-00-00 00:00:00', 'string');

        // $row = JTable::getInstance('matukio', 'Table');
        // $row->load($id);

        if ($cid > 0) {
            $kurs = JTable::getInstance('matukio', 'Table');
            $kurs->load($cid);
        }
        if ($vorlage > 0) {
            $kurs = JTable::getInstance('matukio', 'Table');
            $kurs->load($vorlage);
        }
        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);

        $row = JTable::getInstance('matukio', 'Table');
        $row->load($cid);

        if (!$row->bind($post)) {
            return JError::raiseWarning(500, $row->getError());
        }
        if ($cancel != $row->cancelled AND $row->pattern == "") {
            $tempmail = 9 + $cancel;
            $database->setQuery("SELECT * FROM #__matukio_bookings WHERE semid='$row->id'");
            $rows = $database->loadObjectList();
            for ($i = 0, $n = count($rows); $i < $n; $i++) {
                MatukioHelperUtilsEvents::sendBookingConfirmationMail($row->id, $rows[$i]->id, $tempmail);
            }
        }
        $row->cancelled = $cancel;
        $row->catid = $caid;

//        $row->begin = $_begin_date;
//        $row->end = $_end_date;
//        $row->booked = $_booked_date;

        // Zuweisung der Startzeit
        $row->begin = JFactory::getDate($_begin_date, MatukioHelperUtilsBasic::getTimeZone())->format('Y-m-d H:i:s', false, false);

        // Zuweisung der Endzeit
        $row->end = JFactory::getDate($_end_date, MatukioHelperUtilsBasic::getTimeZone())->format('Y-m-d H:i:s', false, false);

        // Zuweisung der Buchungszeit
        $row->booked = JFactory::getDate($_booked_date, MatukioHelperUtilsBasic::getTimeZone())->format('Y-m-d H:i:s', false, false);

        // Zuweisung der aktuellen Zeit
        if ($cid == 0) {
            $row->publishdate = $neudatum;
        } else {
            $row->publishdate = $kurs->publishdate;
        }
        $row->updated = $neudatum;

        // neue Daten eintragen
        $row->description = str_replace('<br>', '<br />', $row->description);
        $row->description = str_replace('\"', '"', $row->description);
        $row->description = str_replace("\'", "'", $row->description);
        $row->semnum = MatukioHelperUtilsBasic::cleanHTMLfromText($row->semnum);
        $row->title = MatukioHelperUtilsBasic::cleanHTMLfromText($row->title);
        $row->target = MatukioHelperUtilsBasic::cleanHTMLfromText($row->target);
        $row->shortdesc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->shortdesc);
        $row->place = MatukioHelperUtilsBasic::cleanHTMLfromText($row->place);
        $row->fees = str_replace(",", ".", MatukioHelperUtilsBasic::cleanHTMLfromText($row->fees));
        $row->maxpupil = MatukioHelperUtilsBasic::cleanHTMLfromText($row->maxpupil);
        $row->gmaploc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->gmaploc);
        $row->nrbooked = MatukioHelperUtilsBasic::cleanHTMLfromText($row->nrbooked);
        $row->zusatz1 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz1);
        $row->zusatz2 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz2);
        $row->zusatz3 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz3);
        $row->zusatz4 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz4);
        $row->zusatz5 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz5);
        $row->zusatz6 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz6);
        $row->zusatz7 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz7);
        $row->zusatz8 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz8);
        $row->zusatz9 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz9);
        $row->zusatz10 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz10);
        $row->zusatz11 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz11);
        $row->zusatz12 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz12);
        $row->zusatz13 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz13);
        $row->zusatz14 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz14);
        $row->zusatz15 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz15);
        $row->zusatz16 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz16);
        $row->zusatz17 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz17);
        $row->zusatz18 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz18);
        $row->zusatz19 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz19);
        $row->zusatz20 = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz20);
        $row->zusatz1hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz1hint);
        $row->zusatz2hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz2hint);
        $row->zusatz3hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz3hint);
        $row->zusatz4hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz4hint);
        $row->zusatz5hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz5hint);
        $row->zusatz6hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz6hint);
        $row->zusatz7hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz7hint);
        $row->zusatz8hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz8hint);
        $row->zusatz9hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz9hint);
        $row->zusatz10hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz10hint);
        $row->zusatz11hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz11hint);
        $row->zusatz12hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz12hint);
        $row->zusatz13hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz13hint);
        $row->zusatz14hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz14hint);
        $row->zusatz15hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz15hint);
        $row->zusatz16hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz16hint);
        $row->zusatz17hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz17hint);
        $row->zusatz18hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz18hint);
        $row->zusatz19hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz19hint);
        $row->zusatz20hint = MatukioHelperUtilsBasic::cleanHTMLfromText($row->zusatz20hint);
        $row->file1desc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->file1desc);
        $row->file2desc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->file2desc);
        $row->file3desc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->file3desc);
        $row->file4desc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->file4desc);
        $row->file5desc = MatukioHelperUtilsBasic::cleanHTMLfromText($row->file5desc);
        if ($cid > 0 OR $vorlage > 0) {
            if ($deldatei1 != 1) {
                $row->file1 = $kurs->file1;
                $row->file1code = $kurs->file1code;
            }
            if ($deldatei2 != 1) {
                $row->file2 = $kurs->file2;
                $row->file2code = $kurs->file2code;
            }
            if ($deldatei3 != 1) {
                $row->file3 = $kurs->file3;
                $row->file3code = $kurs->file3code;
            }
            if ($deldatei4 != 1) {
                $row->file4 = $kurs->file4;
                $row->file4code = $kurs->file4code;
            }
            if ($deldatei5 != 1) {
                $row->file5 = $kurs->file5;
                $row->file5code = $kurs->file5code;
            }
        }
        if ($cid > 0) {
            $row->hits = $kurs->hits;
        }

        $fileext = explode(' ', strtolower(MatukioHelperSettings::getSettings('file_endings', 'txt zip pdf')));
        $filesize = MatukioHelperSettings::getSettings('file_maxsize', 500) * 1024;
        $fehler = array('', '', '', '', '', '', '', '', '', '');

        if (is_file($_FILES['datei1']['tmp_name']) AND $_FILES['datei1']['size'] > 0) {
            if ($_FILES['datei1']['size'] > $filesize) {
                $fehler[0] = str_replace("SEM_FILE", $_FILES['datei1']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
            }
            $datei1ext = array_pop(explode(".", strtolower($_FILES['datei1']['name'])));
            if (!in_array($datei1ext, $fileext)) {
                $fehler[1] = str_replace("SEM_FILE", $_FILES['datei1']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
            }
            if ($fehler[0] == "" AND $fehler[1] == "") {
                $row->file1 = $_FILES['datei1']['name'];
                $row->file1code = base64_encode(file_get_contents($_FILES['datei1']['tmp_name']));
            }
        }

        if (is_file($_FILES['datei2']['tmp_name']) AND $_FILES['datei2']['size'] > 0) {
            if ($_FILES['datei2']['size'] > $filesize) {
                $fehler[2] = str_replace("SEM_FILE", $_FILES['datei2']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
            }
            $datei2ext = array_pop(explode(".", strtolower($_FILES['datei2']['name'])));
            if (!in_array($datei2ext, $fileext)) {
                $fehler[3] = str_replace("SEM_FILE", $_FILES['datei2']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
            }
            if ($fehler[2] == "" AND $fehler[3] == "") {
                $row->file2 = $_FILES['datei2']['name'];
                $row->file2code = base64_encode(file_get_contents($_FILES['datei2']['tmp_name']));
            }
        }

        if (is_file($_FILES['datei3']['tmp_name']) AND $_FILES['datei3']['size'] > 0) {
            if ($_FILES['datei3']['size'] > $filesize) {
                $fehler[4] = str_replace("SEM_FILE", $_FILES['datei3']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
            }
            $datei3ext = array_pop(explode(".", strtolower($_FILES['datei3']['name'])));
            if (!in_array($datei3ext, $fileext)) {
                $fehler[5] = str_replace("SEM_FILE", $_FILES['datei3']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
            }
            if ($fehler[4] == "" AND $fehler[5] == "") {
                $row->file3 = $_FILES['datei3']['name'];
                $row->file3code = base64_encode(file_get_contents($_FILES['datei3']['tmp_name']));
            }
        }

        if (is_file($_FILES['datei4']['tmp_name']) AND $_FILES['datei4']['size'] > 0) {
            if ($_FILES['datei4']['size'] > $filesize) {
                $fehler[6] = str_replace("SEM_FILE", $_FILES['datei4']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
            }
            $datei4ext = array_pop(explode(".", strtolower($_FILES['datei4']['name'])));
            if (!in_array($datei4ext, $fileext)) {
                $fehler[7] = str_replace("SEM_FILE", $_FILES['datei4']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
            }
            if ($fehler[6] == "" AND $fehler[7] == "") {
                $row->file4 = $_FILES['datei4']['name'];
                $row->file4code = base64_encode(file_get_contents($_FILES['datei4']['tmp_name']));
            }
        }

        if (is_file($_FILES['datei5']['tmp_name']) AND $_FILES['datei5']['size'] > 0) {
            if ($_FILES['datei5']['size'] > $filesize) {
                $fehler[8] = str_replace("SEM_FILE", $_FILES['datei5']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_MAX_SIZE'));
            }
            $datei5ext = array_pop(explode(".", strtolower($_FILES['datei5']['name'])));
            if (!in_array($datei5ext, $fileext)) {
                $fehler[9] = str_replace("SEM_FILE", $_FILES['datei5']['name'], JTEXT::_('COM_MATUKIO_UPLOAD_FAILED_FILE_TYPE'));
            }
            if ($fehler[8] == "" AND $fehler[9] == "") {
                $row->file5 = $_FILES['datei5']['name'];
                $row->file5code = base64_encode(file_get_contents($_FILES['datei5']['tmp_name']));
            }
        }

        // Eingaben ueberpruefen
        $speichern = TRUE;
        if (!MatukioHelperUtilsEvents::checkRequiredFieldValues($row->pattern, 'leer')) {
            if (!MatukioHelperUtilsEvents::checkRequiredFieldValues($row->semnum, 'leer')) {
                $speichern = FALSE;
                $htxt = JTEXT::_('COM_MATUKIO_NO_EVENT_FILES');
                if ($cid < 1) {
                    $htxt .= " " . JTEXT::_('COM_MATUKIO_EVENT_NOT_STORED');
                }
                $fehler[] = $htxt;
            } else {
                $database->setQuery("SELECT id FROM #__matukio WHERE semnum='$row->semnum' AND id!='$row->id'");
                $rows = $database->loadObjectList();
                if (count($rows) > 0) {
                    $speichern = FALSE;
                    $htxt = JTEXT::_('COM_MATUKIO_NOT_UNIQUE_NUMBERS');
                    if ($cid < 1) {
                        $htxt .= " " . JTEXT::_('COM_MATUKIO_EVENT_NOT_STORED');
                    }
                    $fehler[] = $htxt;
                }
            }
        }
        // speichern
        if ($speichern == TRUE) {
            if (!$row->check()) {
                JError::raiseError(500, $database->stderr());
                return false;
            }
            if (!$row->store()) {
                JError::raiseError(500, $database->stderr());
                return false;
            }
        }

        $link = JRoute::_("index.php?option=com_matukio&art=2");
        $link2 = JRoute::_("index.php?option=com_matukio&view=createevent&cid=" .$row->id);
        if(!empty($fehler)){
            $tempmsg = implode(",", $fehler);
            $tempmsg = str_replace(",", "", $tempmsg); // hack for dirks empty array
            if(!empty($tempmsg)){
                $msg = implode(",", $fehler);
            }
        }

//        var_dump($msg);
//
//        die();

        // Ausgabe der Kurse
        $fehlerzahl = array_unique($fehler);
        if (MatukioHelperUtilsEvents::checkRequiredFieldValues($row->pattern, 'leer')) {
            $this->setRedirect($link2, $msg);
        } elseif (count($fehlerzahl) > 1 AND $speichern == TRUE) {
            $link = JRoute::_("index.php?option=com_matukio&art=2", $msg);
        } else {
            $link = JRoute::_("index.php?option=com_matukio&art=2", $msg);
        }

        $this->setRedirect($link, $msg);
    }
}