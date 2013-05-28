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

global $mainframe;
$document = JFactory::getDocument();
$database = JFactory::getDBO();
$my = JFactory::getuser();
JHTML::_('stylesheet', 'media/com_matukio/css/matukio.css');

JFilterOutput::objectHTMLSafe($this->event);
JHTML::_('behavior.modal');
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

// ---------------------------------
// Anzeige Kopfbereich mit Auswahl
// ---------------------------------

?>
<form action="index.php" method="post" name="FrontForm" enctype="multipart/form-data">
<?php
$knopfunten = MatukioHelperUtilsEvents::getEventlistHeader(3);

    // Zurück
$backlink = JRoute::_("index.php?option=com_matukio&view=eventlist&art=2");
$knopfoben = "<a title=\"" . JTEXT::_('COM_MATUKIO_BACK') . "\" href=\"" .$backlink . "\"><img src=\""
    . MatukioHelperUtilsBasic::getComponentImagePath() . "1032.png\" border=\"0\" align=\"absmiddle\"></a>";

$knopfunten .= "<a href=\"" .$backlink . "\"> <span class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\"><img src=\""
    . MatukioHelperUtilsBasic::getComponentImagePath() . "1016.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
    . JTEXT::_('COM_MATUKIO_BACK') . "</span></a>";

// Submit
$knopfunten .= " <input type=\"submit\" class=\"mat_button\" style=\"cursor:pointer;\" value=\"" . JText::_("COM_MATUKIO_SAVE") ."\">";

// TODO implement  icon
//    <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
//    . "1416.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_SAVE');

if ($this->event->id > 0) {

    // Event kopieren
    $duplicatelink = JRoute::_("index.php?option=com_matukio&view=createevent&task=duplicateEvent&cid=" . $this->event->id); // "duplicateEvent";

    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_DUPLICATE') . "\" href=\"" . $duplicatelink
        . "\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
        . "1232.png\" border=\"0\" align=\"absmiddle\"></a>";


    $knopfunten .= "<a title=\"" . JTEXT::_('COM_MATUKIO_DUPLICATE') . "\" href=\"" . $duplicatelink
                . "\"><button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\">
                <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                . "1216.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_DUPLICATE') . "</button></a>";


    // Delete (unpublish in reallity)
    $unpublishlink = JRoute::_("index.php?option=com_matukio&view=createevent&task=unpublishevent&cid=" . $this->event->id);


    $knopfoben .= "<a title=\"" . JTEXT::_('COM_MATUKIO_DELETE') . "\" href=\"" . $unpublishlink . "\">
        <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
        . "1532.png\" border=\"0\" align=\"absmiddle\"></a>";

    $knopfunten .= "<a href=\"" . $unpublishlink . "\"><button class=\"mat_button\" style=\"cursor:pointer;\" type=\"button\">
                     <img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
        . "1516.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_DELETE') . "</button></a>";
}
if (MatukioHelperSettings::getSettings('event_buttonposition', 2) == 0
    OR MatukioHelperSettings::getSettings('event_buttonposition', 2) == 2) {
    echo $knopfoben;
}
    MatukioHelperUtilsEvents::getEventlistHeaderEnd();

// ---------------------------------
// Anzeige Bereichsueberschrift
// ---------------------------------

if ($this->event->id == "") {
    $temp1 = JTEXT::_('COM_MATUKIO_NEW_EVENT');
    $temp2 = JTEXT::_('COM_MATUKIO_SUBMIT_NEW_EVENT');
} else {
    $temp1 = JTEXT::_('COM_MATUKIO_EDIT_EVENT');
    $temp2 = JTEXT::_('COM_MATUKIO_CHANGE_INFORMATION');
}
    MatukioHelperUtilsEvents::printHeading("$temp1", "$temp2");

// ---------------------------------
// Anzeige Eingabefelder
// ---------------------------------

$html = MatukioHelperUtilsEvents::getTableHeader(4) . MatukioHelperUtilsEvents::getEventEdit($this->event, 1)
    . MatukioHelperUtilsEvents::getTableHeader('e');

// ---------------------------------
// Anzeige Funktionsknoepfe unten
// ---------------------------------

if (MatukioHelperSettings::getSettings('event_buttonposition', 2) > 0) {
    $html .= MatukioHelperUtilsEvents::getTableHeader(4) . "<tr>"
        . MatukioHelperUtilsEvents::getTableCell($knopfunten, 'd', 'c', '100%', 'sem_nav_d')
        . "</tr>" . MatukioHelperUtilsEvents::getTableHeader('e');
}

// ---------------------------------------
// Ausgabe der unsichtbaren Formularfelder
// ---------------------------------------

if ($this->event->published == "") {
    $html .= "\n<input type=\"hidden\" name=\"published\" value=\"1\" />";
} else {
    $html .= "\n<input type=\"hidden\" name=\"published\" value=\"" . $this->event->published . "\" />";
}
if (MatukioHelperUtilsBasic::getUserLevel() < 6) {
    $html .= "<input type=\"hidden\" name=\"publisher\" value=\"" . $this->event->publisher . "\" />";
}
$html .= "<input type=\"hidden\" name=\"id\" value=\"" . $this->event->id . "\" />";
$html .= MatukioHelperUtilsEvents::getHiddenFormElements("", $this->catid, $this->search,
        $this->limit, $this->limitstart, 0, $this->dateid, -1);
echo $html;
?>

<input type="hidden" name="option" value="com_matukio" />
<input type="hidden" name="view" value="createevent" />
<input type="hidden" name="controller" value="createevent" />
<input type="hidden" name="task" value="saveevent" />

</table>
</form>
