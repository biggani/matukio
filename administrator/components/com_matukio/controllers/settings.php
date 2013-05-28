<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );


class MatukioControllerSettings extends MatukioController
{
    function __construct() {
        parent::__construct();
        $this->registerTask( 'apply'  , 'save' );
    }

    function save() {
        //$post	  = JFactory::getApplication()->input->post;
        $matukioSet = JRequest::getVar('matukioset', array(0), 'post', 'array'); // Todo update to JINPUT without filter!!

        require_once(JPATH_COMPONENT. '/models/settings.php');
        $model=new MatukioModelSettings;

        switch ( JFactory::getApplication()->input->get('task') ) {
            case 'apply':
                if ($model->store($matukioSet)) {
                    $msg = JText::_( 'COM_MATUKIO_CHANGES_TO_SETTINGS_SAVED' );
                } else {
                    $msg = JText::_( 'COM_MATUKIO_ERROR_SAVING_SETTINGS' );
                }
                $this->setRedirect( 'index.php?option=com_matukio&view=settings', $msg );
                break;

            case 'save':
            default:
                if ($model->store($matukioSet)) {
                    $msg = JText::_( 'COM_MATUKIO_SETTINGS_SAVED' );
                } else {
                    $msg = JText::_( 'COM_MATUKIO_ERROR_SAVING_SETTINGS' );
                }
                $this->setRedirect( 'index.php?option=com_matukio');
                break;
        }

        $model->checkin();
    }

    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JFactory::getApplication()->input->get( 'view', 'settings' );

        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        require_once(JPATH_COMPONENT . '/models/settings.php');
        $model=new MatukioModelSettings;

        $view->setModel($model, true);

        $view->setLayout('default');
        $view->display();
    }


    function cancel() {
        $this->setRedirect( 'index.php?option=com_matukio' );
    }

}
?>