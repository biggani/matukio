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

JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'matukio.css', 'media/com_matukio/css/');

$htxt = str_replace("SEM_TITLE", $this->event->title, JTEXT::_('COM_MATUKIO_PLEASE_RATE_THIS_EVENT'));

$html = "\n<body onload=\"parent.sbox-window.focus();\">";
$html .= "<form action=\"index.php\" method=\"post\" name=\"FrontForm\">\n";
$html .= "<div class=\"sem_cat_title\">" . JTEXT::_('COM_MATUKIO_YOUR_RATING') . "</div><br />";
$html .= "<div class=\"sem_shortdesc\">" . $htxt . "</div>";
$html .= "<br /><center><table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">";

$tempa = "";
$tempb = "";
for ($i = 6; $i > 0; $i = $i - 1) {
    $tempa .= "<th><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . "240" . $i . ".png\"></th><td width=\"10px\">&nbsp;</td>";
    $tempb .= "<th><input type=\"radio\" name=\"grade\" value=\"" . $i . "\"";
    if ($i == $this->booking->grade) {
        $tempb .= " checked";
    }
    $tempb .= "></th><td width=\"10px\">&nbsp;</td>";
}
$html .= "<tr>" . $tempa . "</tr>";
$html .= "<tr>" . $tempb . "</tr>";
$html .= "</table></center>";
$html .= "<br /><div class=\"sem_shortdesc\">" . JTEXT::_('COM_MATUKIO_COMMENT') . ":</div>";
$html .= "<br /><center><input type=\"text\" name=\"text\" size=\"70\" maxlength=\"200\" value=\""
    . $this->booking->comment . "\"></center><br />";
$html .= "<input type=\"hidden\" name=\"option\" value=\"" . JRequest::getCmd('option') . "\">
        <input type=\"hidden\" name=\"view\" value=\"rateevent\" />
        <input type=\"hidden\" name=\"controller\" value=\"rateevent\" />
        <input type=\"hidden\" name=\"cid\" value=\"" . $this->event->id . "\">
        <input type=\"hidden\" name=\"task\" value=\"rate\">";
$html .= "<center><button class=\"button\" style=\"cursor:pointer;\" type=\"button\" onclick=\"this.disabled=true;document.FrontForm.submit();\">" . JTEXT::_('COM_MATUKIO_SEND') . "</button></center>";
$html .= "</form>";
$html .= "</body></html>";
echo $html;
