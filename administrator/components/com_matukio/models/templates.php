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

defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class MatukioModelTemplates extends JModelLegacy {


    public function __construct() {
        parent::__construct();
    }

    public function getTemplates() {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select("*")->from("#__matukio_templates")->where("published = 1");

        $db->setQuery($query);

        return $db->loadObjectList();
    }

}