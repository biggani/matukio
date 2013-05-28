<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 $
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('stylesheet', 'media/com_matukio/css/matukio.css');
$database = JFactory::getDBO();

$neudatum = MatukioHelperUtilsDate::getCurrentDate();
$cid = JFactory::getApplication()->input->getInt('cid', 0);

$kurs = JTable::getInstance('Matukio', 'Table');
$kurs->load($cid);

$database->setQuery("SELECT a.*, cc.*, a.id AS sid, a.name AS aname, a.email AS aemail FROM #__matukio_bookings AS a " .
    "LEFT JOIN #__users AS cc ON cc.id = a.userid WHERE a.semid = '" . $kurs->id . "' ORDER BY a.id");

$bookings = $database->loadObjectList();

if ($this->art > 2) {
    echo MatukioHelperUtilsBasic::getHTMLHeader();
    $this->art -= 2;
}

$tmpl = MatukioHelperTemplates::getTemplate("export_signaturelist");

$tmpl = MatukioHelperTemplates::getParsedExportTemplateHeadding($tmpl, $kurs);


echo "\n<body onload=\" parent.sbox-window.focus(); parent.sbox-window.print(); \">";
if(!empty($tmpl->subject)) {
    echo "\n<br /><center><span class=\"mat_title\">" . JTEXT::_($tmpl->subject) . "</span></center><br />";
}

/* Header before out of value_text */

echo $tmpl->value_text;

/* Single lines from value */

$signature_line = str_replace("</p>", "", str_replace("<p>", "", $tmpl->value));

$signatures = "<table class=\"mat_table\" style=\"width: 100%;\">";

// Header table

$signatures .= "<tr>" . MatukioHelperTemplates::getExportSignatureHeader($signature_line) . "</tr>";

// Move to function
$i = 1;
foreach ($bookings as $b) {
    $replaces = MatukioHelperTemplates::getReplaces($kurs, $b, $i);

    $line = $signature_line;

    $signatures .= "<tr>\n";

    foreach($replaces as $key => $replace) {
        $line = str_replace($key, "<td>" . $replace . "</td>\n", $line);
    }

    $signatures .= $line;

    $signatures .= "</tr>\n";
    $i++;
}

echo $signatures;

echo "</table>";

echo "<br />" . MatukioHelperUtilsBasic::getCopyright();
echo "</body></html>";