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
$kurs = $database->loadObject();

$tmpl_code = MatukioHelperTemplates::getTemplate("export_certificate")->value;

if(!empty($kurs->certicate_code)) {
    $tmpl_code = $kurs->certificate_code; // Custom code for certificates
}
// Parse language strings
$tmpl_code = MatukioHelperTemplates::replaceLanguageStrings($tmpl_code);

echo "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";

$replaces = MatukioHelperTemplates::getReplaces($kurs, $booking);

foreach($replaces as $key => $replace) {
    $tmpl_code = str_replace($key, $replace, $tmpl_code);
}

echo $tmpl_code;

echo "</body></html>";
