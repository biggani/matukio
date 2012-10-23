<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.0.0 Stable $
 **/

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

?>
<div id="matukio" class="matukio">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_EDIT_BOOKING_FIELD'); ?></legend>
            <table>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_MATUKIO_FIELD_NAME'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="field_name" id="field_name" size="50" maxlength="250" value="<?php echo $this->bookingfield->field_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100"  class="key">
                        <?php echo JText::_('COM_MATUKIO_LABEL'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" size="50" maxlength="250" name="label" id="label" value="<?php echo $this->bookingfield->label; ?>" />
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_DEFAULT_VALUE'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="default" id="default" size="50" value="<?php echo $this->bookingfield->default; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_VALUES'); ?>:
                    </td>
                    <td>
                        <textarea class="text_area" type="text" cols="20" rows="4" name="values" id="values" style="width:500px" /><?php echo $this->bookingfield->values; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_PAGE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->select_page; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_TYPE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->select_type; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_REQUIRED'); ?>:
                    </td>
                    <td>
                        <?php echo $this->select_required; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_STYLE'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="style" id="style" size="50" value="<?php echo $this->bookingfield->style; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_ORDERING'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="ordering" id="ordering" size="20" value="<?php echo $this->bookingfield->ordering; ?>" />
                    </td>
                </tr>
            </table>

        </fieldset>
        <input type="hidden" name="id" value="<?php echo $this->bookingfield->id; ?>" />
        <input type="hidden" name="option" value="com_matukio" />
        <input type="hidden" name="controller" value="bookingfields" />
        <input type="hidden" name="view" value="editbookingfield" />
        <input type="hidden" name="model" value="editbookingfield" />
        <input type="hidden" name="task" value="editbookingfield" />
    </form>
</div>