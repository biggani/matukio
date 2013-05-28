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

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

if(!defined('CJOOMLA_VERSION')) {
    if(substr(JVERSION, 0, 1) == 3) {
        define('CJOOMLA_VERSION', 3);
    } else {
        define('CJOOMLA_VERSION', 2);
    }
}

require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/helper/defines.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/controller.php' );

$input=JFactory::getApplication()->input;

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


if($input->get('view','') == 'liveupdate') {
    JToolBarHelper::preferences( 'com_matukio' );
    LiveUpdate::handleRequest();
    return;
}

if($input->get('view','') == 'controlcenter') {
    JToolBarHelper::preferences( 'com_matukio' );
    CompojoomControlCenter::handleRequest();
    return;
}

if($input->get('view','') == 'information') {
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
$input->set('controller', $input->get('view','events')); // Black magic: Get controller based on the selected view

// Require specific controller if requested
if ($controller = $input->get('controller')) {
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
$classname = 'MatukioController' . $controller;
$controller = new $classname( );
$controller->execute($input->get('task'));
$controller->redirect();