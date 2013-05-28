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
    <div id="mat_email">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING'); ?></legend>
            <table class="admintable">
                 <tr>
                    <td class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_BOOKING_SUBJECT");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><label for="subject[1]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_SUBJECT_DESC'); ?>">
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_SUBJECT'); ?>:
                        </label>
                    </td>
                    <td>
                        <input type="text" maxlength="255" id="subject[1]" name="subject[1]" size="60" value="<?php echo $this->templates[0]->subject; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                       <label for="value[1]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_HTMLTEXT_DESC'); ?>">
                             <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_BOOKING_HTMLTEXT");
                             ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_HTMLTEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                            $editor = JFactory::getEditor() ;
                            echo $editor->display("value[1]", $this->templates[0]->value, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value_text[1]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_TEXT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_BOOKING_TEXT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_TEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea rows="10" cols="80" name="value_text[1]" id="value_text[1]" style="width: 800px;"><?php echo $this->templates[0]->value_text; ?></textarea>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCELATION_ADMIN'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="subject[2]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_SUBJECT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_SUBJECT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_SUBJECT'); ?>:
                        </label>
                    </td>
                    <td>
                        <input type="text" maxlength="255" id="subject[2]" name="subject[2]" size="60" value="<?php echo $this->templates[1]->subject; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[2]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_HTMLTEXT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_HTMLTEXT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_HTMLTEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value[2]", $this->templates[1]->value, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value_text[3]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_TEXT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_TEXT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_TEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea rows="10" cols="80" name="value_text[2]" id="value_text[2]" style="width: 800px;"><?php echo $this->templates[1]->value_text; ?></textarea>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCELATION'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="subject[3]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_SUBJECT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_USER_SUBJECT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_SUBJECT'); ?>:
                        </label>
                    </td>
                    <td>
                        <input type="text" maxlength="255" id="subject[3]" name="subject[3]" size="60" value="<?php echo $this->templates[2]->subject; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[3]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_HTMLTEXT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_USER_HTMLTEXT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_HTMLTEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value[3]", $this->templates[2]->value, 800, 300, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" colspan="2">
                        <label for="value_text[3]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_TEXT_DESC'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_MAIL_CANCEL_USER_TEXT");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span><?php
                                echo JText::_('COM_MATUKIO_TEMPLATE_MAIL_BOOKING_CANCEL_TEXT'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea rows="10" cols="80" name="value_text[3]" id="value_text[3]" style="width: 800px;"><?php echo $this->templates[2]->value_text; ?></textarea>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
<div class="clr"></div>