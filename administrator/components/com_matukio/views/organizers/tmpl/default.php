<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');

$listDir = $this->escape($this->state->get('list.direction'));
$listOrder = $this->escape($this->state->get('list.ordering'));

JHTML::_('stylesheet', 'media/com_matukio/backend/css/modern.css');
?>

<div class="compojoom-bootstrap">
<form name="adminForm" id="adminForm" method="post" action="<?php echo JRoute::_('index.php?option=com_matukio&view=organizers'); ?>">

    <div class="filter-search fltlft btn-group pull-left">
        <label class="filter-search-lbl element-invisible"
               for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
        <input type="text" name="filter_search" id="filter_search"
               value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
               title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"
               placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
    </div>
    <div class="btn-group pull-left hidden-phone">
        <?php if (JVERSION > 2.5) : ?>
        <button class="btn" type="submit"><i class="icon-search"></i></button>
        <button class="btn" type="button"
                onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i>
        </button>
        <?php else : ?>
        <button class="btn" type="submit"
                style="margin:0"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
        <button class="btn" type="button" style="margin:0"
                onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

        <?php endif; ?>
    </div>
    <div class="filter-select fltrt">
        <select name="filter_published" class="inputbox" onchange="this.form.submit()">
            <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
            <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
        </select>

    </div>
    <div class="clr"></div>
    <table class="adminlist">
        <thead>
        <tr>
            <th width="5"><?php echo JText::_('JGRID_HEADING_ROW_NUMBER'); ?></th>
            <th width="5">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
            </th>
            <th style="width: 90px;"><?php echo JHtml::_('grid.sort', 'COM_MATUKIO_ORGANIZER_USERID', 'n.userId', $listDir, $listOrder); ?></th>
            <th width="10"><?php echo JTEXT::_("COM_MATUKIO_ORGANIZER_ID"); ?></th>
            <th><?php echo JHtml::_('grid.sort', 'COM_MATUKIO_ORGANIZER_NAME', 'n.name', $listDir, $listOrder); ?></th>
            <th><?php echo JTEXT::_("COM_MATUKIO_ORGANIZER_EMAIL"); ?></th>
            <th><?php echo JTEXT::_("COM_MATUKIO_ORGANIZER_PHONE"); ?></th>
            <th><?php echo JTEXT::_("COM_MATUKIO_PUBLISHED"); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $item) :
                if(empty($item->name)) {
                    $item->name = JFactory::getUser($item->id)->name;
                }

                if(empty($item->email)) {
                    $item->email = JFactory::getUser($item->id)->email;
                }

                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
                    <td><div align="center"><?php echo '<a href="index.php?option=com_matukio&view=editorganizer&id=' .$item->id . '">'
                        . $item->userId . '</a>'; ?></div></td>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo '<a href="index.php?option=com_matukio&view=editorganizer&id=' .$item->id . '">'
                        . $item->name . '</a>'; ?></td>
                    <td><?php echo '<a href="mailto:' . $item->email . '">' .$item->email . '</a>'; ?></td>
                    <td><?php echo $item->phone ?></td>
                    <td><?php echo JHTML::_('grid.published', $item, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix = 'site.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>

        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>

        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDir; ?>"/>

        <?php echo JHTML::_('form.token'); ?>
    </table>
</form>

<?php echo MatukioHelperUtilsBasic::getCopyright(); ?>
</div>