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

$database = JFactory::getDBO();

$database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $this->uid .  "'");
$booking = $database->loadObject();

$database->setQuery("SELECT * FROM #__matukio WHERE id='" . $booking->semid .  "'");
$rows = $database->loadObjectList();
$row = &$rows[0];

if ($booking->userid == 0) {
    $user = JFactory::getUser(0);
    $user->name = $booking->name;
    $user->email = $booking->email;
} else {
    $user = JFactory::getuser($booking->userid);
}

$html = "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";

if (MatukioHelperSettings::getSettings('certificate_htmlcode', '') != "") {
    $html .= MatukioHelperSettings::getSettings('certificate_htmlcode', '');
} else {
    $html .= JTEXT::_('COM_MATUKIO_CREATE_CERTIFICATE_CODE_IN_BACKEND_SETTINGS');
}
$html .= "</body></html>";
echo MatukioHelperUtilsEvents::replaceSEMConstants($html, $row, $user);