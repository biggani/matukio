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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="col60">
        <div id="mat_email">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR'); ?></legend>
                <table class="admintable">
                    <tr>
                        <td class="key">
                        <span class="editlinktip hasTip matTip"
                              title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_IMPORT_SEMINAR_TABLE_NAME");
                              ?>"><img src="../media/com_matukio/images/info.png" align="right"
                                       style="float: right !important;"/></span>
                                <label for="seminar_table" width="100"  title="<?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_TABLE_NAME'); ?>">
                            <?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_TABLE_NAME'); ?>:
                        </label>
                        </td>
                        <td>
                            <input type="text" maxlength="255" id="seminar_table" name="seminar_table" size="60"
                                   value="jos_seminar">
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                        <span class="editlinktip hasTip matTip"
                              title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_IMPORT_SEMINAR_CATEGORIES_TABLE");
                              ?>"><img src="../media/com_matukio/images/info.png" align="right"
                                       style="float: right !important;"/></span>
                            <label for="seminar_booking_table" width="100"  title="<?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_CATEGORIES_TABLE'); ?>">
                                <?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_CATEGORIES_TABLE'); ?>:
                            </label>
                        </td>
                        <td>
                            <input type="text" maxlength="255" id="seminar_category_table" name="seminar_category_table" size="60"
                                   value="jos_categories">
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                        <span class="editlinktip hasTip matTip"
                              title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_IMPORT_SEMINAR_BOOKING_TABLE");
                              ?>"><img src="../media/com_matukio/images/info.png" align="right"
                                       style="float: right !important;"/></span>
                            <label for="seminar_booking_table" width="100"  title="<?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_BOOKING_TABLE'); ?>">
                                <?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_BOOKING_TABLE'); ?>:
                            </label>
                        </td>
                        <td>
                            <input type="text" maxlength="255" id="seminar_booking_table" name="seminar_booking_table" size="60"
                                   value="jos_sembookings">
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                        <span class="editlinktip hasTip matTip"
                              title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_IMPORT_SEMINAR_NUMBER_TABLE");
                              ?>"><img src="../media/com_matukio/images/info.png" align="right"
                                       style="float: right !important;"/></span>
                            <label for="seminar_table" width="100"  title="<?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_NUMBER_TABLE'); ?>">
                                <?php echo JText::_('COM_MATUKIO_IMPORT_SEMINAR_NUMBER_TABLE'); ?>:
                            </label>
                        </td>
                        <td>
                            <input type="text" maxlength="255" id="seminar_number_table" name="seminar_number_table" size="60"
                                   value="jos_semnumber">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div align="right">
                                <input type="submit" class="mat_button" value="<?php echo JText::_("COM_MATUKIO_IMPORT"); ?>" />
                            </div>
                        </td>
                    </tr>

                </table>
            </fieldset>
        </div>
    </div>
    <div class="clr"></div>


    <input type="hidden" name="option" value="com_matukio"/>
    <input type="hidden" name="task" value="importseminar"/>
    <input type="hidden" name="view" value="import"/>
    <input type="hidden" name="controller" value="import"/>
    <?php echo JHTML::_('form.token'); ?>
</form>