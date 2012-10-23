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

JHTML::_('behavior.tooltip');
jimport('joomla.filter.output');
?>
<script type="text/javascript">
    <?php
    /**
     * this is so lame... I hate myself for doing it. -> QUOTE Daniel :)
     */
    ?>
    Joomla.submitbutton = function (button) {
        if (button == 'edit' || button == 'add') {
            var view = new Element('input', {
                type:'hidden',
                name:'view',
                'value':'bookingfields'
            });

            view.inject(document.adminForm);
        }
        Joomla.submitform(button);
    }
</script>
<form action="index.php" method="post" name="adminForm">
    <table>
        <tr>
            <td align="left" width="100%"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:
                <input type="text" name="search" id="search" value="<?php echo $this->filter['search']; ?>"
                       class="text_area" onchange="document.adminForm.submit();"/>
                <button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button
                    onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR');
                    ?></button>
            </td>
            <td nowrap="nowrap">
                <?php
                echo $this->filter['state'];
                ?>
            </td>
        </tr>
    </table>

    <div id="editcell">
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5"><?php echo JText::_('JGRID_HEADING_ROW_NUMBER'); ?></th>
                <th width="5">
                    <input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->list); ?>);"/>
                </th>
                <th class="title"><?php echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'cc.$field_name', $this->filter['order_Dir'],
                                            $this->filter['order']); ?></th>
                <th width="7%"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
                <th width="30%"><?php echo JText::_('COM_MATUKIO_LABEL'); ?></th>
                <th width="10%"><?php echo JText::_('COM_MATUKIO_VALUES'); ?></th>
                <th width="7%"><?php echo JText::_('COM_MATUKIO_PAGE'); ?></th>
                <th width="10%"><?php echo JText::_('COM_MATUKIO_TYPE'); ?></th>
                <th width="7%"><?php echo JText::_('COM_MATUKIO_REQUIRED'); ?></th>
                <th width="7%"><?php echo JText::_('COM_MATUKIO_ORDERING'); ?></th>
                <th width="5%" nowrap="nowrap"><?php echo JText::_('JPUBLISHED'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
            </tfoot>
            <tbody>
            <?php
            $i = 0;
            foreach ($this->list as $l) {
                $checked = JHTML::_('grid.id', $i, $l->id);
                $published = JHTML::_('grid.published', $l, $i);

                $link = JRoute::_('index.php?option=com_matukio&controller=bookingfields&task=editBookingfield&id=' . $l->id);
                ?>
            <tr class="<?php echo "row" . $i % 2; ?>">
                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                <td>
                    <?php echo $checked; ?>
                </td>
                <td>
                    <a href="<?php echo $link; ?>"><?php echo $l->field_name; ?></a>
                </td>
                <td align="center">
                    <?php echo $l->id; ?>
                </td>
                <td>
                    <?php echo $l->label; ?>
                </td>
                <td>
                    <?php echo $l->values; ?>
                </td>
                <td align="center">
                    <?php echo $l->page; ?>
                </td>
                <td>
                    <?php echo $l->type; ?>
                </td>
                <td align="center">
                    <?php echo $l->required; ?>
                </td>
                <td align="center">
                    <?php echo $l->ordering; ?>
                </td>
                <td align="center">
                    <?php echo $published; ?>
                </td>
            </tr>
                <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="option" value="com_matukio"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="bookingfields"/>
    <input type="hidden" name="controller" value="bookingfields"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->filter['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter['order_Dir']; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>
