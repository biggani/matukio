<?php
/**
 * ControlCenter
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

// Include tiles config
//require_once('');
defined('_JEXEC') or die();

class ControlCenterConfig {

    var $version                = "2.1.10";
    var $copyright              = "Copyright (C) 2012 Yves Hoppe - compojoom.com";
    var $license                = "GPL v2 or later";
    var $translation            = "English: compojoom.com <br />German: compojoom.com";
    var $description            = "COM_MATUKIO_XML_DESCRIPTION";
    var $thankyou               = "<li><a href='http://seminar.vollmar.ws'>Dirk Vollmar</a> - for writing the extension Seminar for Joomla 1.5,
                                   on which Version 1.0 of this extension was originally based</li>";

    var $_extensionTitle        = "com_matukio";
    var $extensionPosition     = "matukio"; // e.G. ccc_extensionPostion_left

    var $_logopath              = '/media/com_matukio/backend/images/logo.png';

    public static function &getInstance()
    {
        static $instance = null;

        if(!is_object($instance)) {
            $instance = new ControlCenterConfig();
        }

        return $instance;
    }
}