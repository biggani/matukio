<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

if(CJOOMLA_VERSION == 3)
    JHtmlBehavior::framework();
else
    JHTML::_('behavior.mootools');

JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

JHTML::_('stylesheet', 'media/com_matukio/backend/css/matukio.css');

?>
<div id="matukio" class="matukio">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_EDIT_ORGANIZER'); ?></legend>
            <table>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_MATUKIO_ID'); ?>:
                    </td>
                    <td>
                        <?php echo $this->organizer->id; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100"  class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_ORGANIZER_USERID");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><?php echo JText::_('COM_MATUKIO_USER'); ?> * :
                    </td>
                    <td>
                        <?php
                            echo JHTML::_('list.users', "userId", $this->organizer->userId, true, null, "name", 0);   // users($name, $active, $nouser=0, $javascript=NULL, $order= 'name', $reg=1)
                        ?>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_ORGANIZER_NAME_OVERRIDE");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><?php echo JText::_('COM_MATUKIO_OVERRIDE_NAME'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->organizer->name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_ORGANIZER_EMAIL_OVERRIDE");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><?php echo JText::_('COM_MATUKIO_OVERRIDE_EMAIL'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="email" id="email" size="50" maxlength="250" value="<?php echo $this->organizer->email; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_MATUKIO_WEBSITE'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="website" id="website" size="50" maxlength="250" value="<?php echo $this->organizer->website; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_MATUKIO_PHONE'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="phone" id="phone" size="50" maxlength="250" value="<?php echo $this->organizer->phone; ?>" />
                    </td>
                </tr>

                <tr>
                    <td width="200" align="left" class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_ORGANIZER_IMAGE");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><?php echo JText::_('COM_MATUKIO_IMAGE'); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_( 'list.images', 'image', $this->organizer->image , null, 'images/' );?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <?php echo JText::_('COM_MATUKIO_DESCRIPTION'); ?>:<br />
                        <?php
                            $editor = JFactory::getEditor() ;
                            echo $editor->display("description", $this->organizer->description, 800, 400, 40, 20, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_ORGANIZER_COMMENTS");
                        ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important;"/></span><?php echo JText::_('COM_MATUKIO_COMMENTS'); ?>:
                    </td>
                    <td>
                        <textarea class="text_area" type="text" cols="20" rows="5" name="comments" id="comments" style="width:550px" /><?php echo $this->organizer->comments; ?></textarea>
                    </td>
                </tr>
            </table>

        </fieldset>
        <input type="hidden" name="id" value="<?php echo $this->organizer->id; ?>" />
        <input type="hidden" name="option" value="com_matukio" />
        <input type="hidden" name="controller" value="organizers" />
        <input type="hidden" name="view" value="editorganizer" />
        <input type="hidden" name="model" value="editorganizer" />
        <input type="hidden" name="task" value="editorganizer" />

        <?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>

<?php echo MatukioHelperUtilsBasic::getCopyright(); ?>
