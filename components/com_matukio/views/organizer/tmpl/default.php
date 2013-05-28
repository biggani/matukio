<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.1.0 $
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'media/com_matukio/css/modern.css');

if(empty($this->organizer)) {
    echo JText::_("COM_MATUKIO_NO_ORGANIZER_PROFILE");
    echo MatukioHelperUtilsBasic::getCopyright();
    return;
}

?>
<!-- Start Matukio by compojoom.com -->
<script type="text/javascript">
window.addEvent('domready', function () {

});
</script>
<div class="componentheading">
    <h2><?php echo JText::_($this->title); ?></h2>
</div>


<div id="mat_holder">
<div id="mat_infobox">
    <table class="mat_infotable" border="0" width="100%">
        <?php if (!empty($this->organizer->phone)) : ?>
        <tr>
            <td><?php echo JText::_("COM_MATUKIO_PHONE"); ?></td>
            <td><?php echo $this->organizer->phone; ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($this->organizer->website)) : ?>
        <tr>
            <td><?php echo JText::_("COM_MATUKIO_WEBSITE"); ?></td>
            <td><?php echo $this->organizer->website; ?></td>
        </tr>
        <?php endif; ?>
        <?php if(MatukioHelperSettings::getSettings("sendmail_contact", 1)) : ?>
        <tr>
            <td colspan="2">
                <?php
                    echo MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->organizer->id, "organizer", "modern");
                ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
</div>
<?php
    echo JHtml::_('content.prepare', $this->organizer->description);
?>

<?php
echo MatukioHelperUtilsBasic::getCopyright();
?>
</div>
<!-- End Matukio by compojoom.com -->