<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2 $
 **/

defined('_JEXEC') or die('Restricted access');

JImport( 'joomla.html.editor' );

?>
<div class="col60">
    <div id="mat_export">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_CSV'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[4]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_CSV_BOOKING_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_CSV");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php
                                echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_CSV_BOOKING'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea rows="10" cols="80" name="value[4]" id="value[4]" style="width: 800px;"><?php echo $this->templates[3]->value; ?></textarea>
                        <input type="hidden" name="subject[4]" value="ID" />
                        <input type="hidden" name="value_text[4]" value="" />
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_SIGNATURE_LIST'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="subject[5]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_LIST_TITLE'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_SIGNATURE_TITLE");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_LIST_TITLE'); ?>:
                        </label>
                    </td>
                    <td>
                        <input type="text" maxlength="255" id="subject[5]" name="subject[5]" size="60" value="<?php echo $this->templates[4]->subject; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value_text[5]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_HEADING'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_SIGNATURE_HEADING");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_HEADING'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value_text[5]", $this->templates[4]->value_text, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[5]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_LINE'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_SIGNATURE_LINE");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_SIGNATURE_LINE'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value[5]", $this->templates[4]->value, 800, 150, 40, 20, 1);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_PARTICIPANTS_LIST'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="subject[5]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_LIST_TITLE'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_PARTLIST_TITLE");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_LIST_TITLE'); ?>:
                        </label>
                    </td>
                    <td>
                        <input type="text" maxlength="255" id="subject[6]" name="subject[6]" size="60" value="<?php echo $this->templates[5]->subject; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value_text[6]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_HEADING'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_PARTLIST_HEADING");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_HEADING'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value_text[6]", $this->templates[5]->value_text, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[6]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_SINGLE'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_EXPORT_PARTLIST_SINGLE");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_EXPORT_PARTICIPANTS_SINGLE'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value[6]", $this->templates[5]->value, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>

    </div>
</div>
<div class="clr"></div>