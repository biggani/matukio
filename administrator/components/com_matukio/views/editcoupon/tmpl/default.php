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

JHTML::_('behavior.mootools');
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

?>
<div id="matukio" class="matukio">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_EDIT_COUPON'); ?></legend>
            <table>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_MATUKIO_COUPON_CODE'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="code" id="code" size="50" maxlength="250" value="<?php echo $this->coupon->code; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100"  class="key">
                        <?php echo JText::_('COM_MATUKIO_VALUE'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" size="10" maxlength="15" name="value" id="value" value="<?php echo $this->coupon->value; ?>" />
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_PERCENT'); ?>:
                    </td>
                    <td>
                        <?php echo $this->select_procent; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_PUBLISHED_UP'); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_('calendar', $this->coupon->published_up, 'published_up', 'published_up'); ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_MATUKIO_PUBLISHED_DOWN'); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_('calendar', $this->coupon->published_down, 'published_down', 'published_down'); ?>
                    </td>
                </tr>
            </table>

        </fieldset>
        <input type="hidden" name="id" value="<?php echo $this->coupon->id; ?>" />
        <input type="hidden" name="option" value="com_matukio" />
        <input type="hidden" name="controller" value="coupons" />
        <input type="hidden" name="view" value="editcoupon" />
        <input type="hidden" name="model" value="editcoupon" />
        <input type="hidden" name="task" value="editcoupon" />
    </form>
</div>