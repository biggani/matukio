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

require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/helper/defines.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/controller.php' );


JLoader::register('MatukioHelperSettings', JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');
JLoader::register('MatukioHelperUtilsBasic', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_basic.php');
JLoader::register('MatukioHelperUtilsBoooking', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_booking.php');
JLoader::register('MatukioHelperUtilsDate', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_date.php');
JLoader::register('MatukioHelperUtilsEvents', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_events.php');
JLoader::register('MatukioHelperRoute', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_route.php');
JLoader::register('MatukioHelperCategories', JPATH_COMPONENT_ADMINISTRATOR . '/helper/util_categories.php');

// Live updater
require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/liveupdate/liveupdate.php');

// Conrol Center
require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/controlcenter/controlcenter.php');


if(JRequest::getCmd('view','') == 'liveupdate') {
    JToolBarHelper::preferences( 'com_matukio' );
    LiveUpdate::handleRequest();
    return;
}

if(JRequest::getCmd('view','') == 'controlcenter') {
    JToolBarHelper::preferences( 'com_matukio' );
    CompojoomControlCenter::handleRequest();
    return;
}

if(JRequest::getCmd('view','') == 'information') {
    JToolBarHelper::preferences( 'com_matukio' );
    CompojoomControlCenter::handleRequest('information');
    return;
}

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

// thank you for this black magic Nickolas :)
// Magic: merge the eventlist translation with the current translation
$jlang = JFactory::getLanguage();
$jlang->load('com_matukio', JPATH_SITE, 'en-GB', true);
$jlang->load('com_matukio', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('com_matukio', JPATH_SITE, null, true);
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_matukio', JPATH_ADMINISTRATOR, null, true);

// Get the view and controller from the request, or set to eventlist if they weren't set
JRequest::setVar('controller', JRequest::getCmd('view','events')); // Black magic: Get controller based on the selected view

// Require specific controller if requested
if ($controller = JRequest::getCmd('controller')) {
    $path = JPATH_COMPONENT_ADMINISTRATOR .  'controllers' .  $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if ($controller == '') {
    require_once(JPATH_COMPONENT_ADMINISTRATOR .  '/controllers/galleries.php');
    $controller = 'galleries';
}

// Create the controller
$classname = 'TilesController' . $controller;
$controller = new $classname( );
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();