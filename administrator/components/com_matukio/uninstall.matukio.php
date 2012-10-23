<?php

/**
 * Matukio - Installer
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 * Based on Seminar for Joomla!
 * by Dirk Vollmar
 **/

defined( '_JEXEC' ) or die ( 'Restricted access' );


function com_uninstall()
{
    $lang = JFactory::getLanguage();
    $sprache = strtolower(substr($lang->getName(), 0, 2));
    switch ($sprache) {
        case "de":
            $html = "<b>Matukio wurde erfolgreich deinstalliert.</b>";
            break;
        default:
            $html = "<b>Matukio has been uninstalled.</b>";
            break;
    }
    echo $html;
}

?>