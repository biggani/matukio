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

$config =& JFactory::getConfig();
header("Content-Type: application/rss+xml; charset=UTF-8");
$mainconfig =& JFactory::getConfig();
$sprache =& JFactory::getLanguage();

$html = "<rss version=\"2.0\">";
$html .= "\n<channel>";
$html .= "\n<title>" . $mainconfig->getValue('config.sitename') . " - " . JTEXT::_('COM_MATUKIO_EVENTS') . "</title>";
$html .= "\n<link>" . JURI::ROOT() . "index.php?tmpl=component&amp;option=" . JRequest::getCmd('option')
    . "&amp;task=31</link>";
$html .= "\n<description>" . $mainconfig->getValue('config.sitename') . " - Events" . "</description>";
$html .= "\n<language>" . $sprache->getTag() . "</language>";
$html .= "\n<copyright>" . $mainconfig->getValue('config.fromname') . "</copyright>";
$html .= "\n<ttl>60</ttl>";
$html .= "\n<pubDate>" . date("r") . "</pubDate>";

foreach ($this->rows AS $row) {
    $user = &JFactory::getuser($row->publisher);
    $cancelled = "";
    if ($row->cancelled == 1) {
        $cancelled = " - " . JTEXT::_('COM_MATUKIO_CANCELLED');
    }
    $html .= "\n<item>";
    $html .= "\n<title>" . $row->title . $cancelled . "</title>";
    $html .= "\n<description>" . JTEXT::_('COM_MATUKIO_BEGIN') . ": " . JHTML::_('date', $row->begin,
        MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')) . " - " . $row->shortdesc . "</description>";
    $html .= "\n<link>" . JURI::ROOT() . "index.php?option=" . JRequest::getCmd('option') . "&amp;task=3&amp;cid=" . $row->id . "</link>";
    if (MatukioHelperSettings::getSettings('frontend_showownerdetails', 1) > 0) {
        $html .= "\n<author>" . $user->name . ", " . $user->email . "</author>";
    }
    $html .= "\n<guid>" . MatukioHelperUtilsBooking::getBookingId($row->id) . "</guid>";
    $html .= "\n<category>" . $row->category . "</category>";
    $html .= "\n<pubDate>" . date("r", strtotime($row->publishdate)) . "</pubDate>";
    $html .= "\n</item>";
}
$html .= "\n</channel>";
$html .= "\n</rss>";
echo $html;