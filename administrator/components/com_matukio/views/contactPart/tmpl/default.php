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
JHTML::_('stylesheet', 'media/com_matukio/backend/css/matukio.css');

var_dump($this->participants);
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div id="editcell">

    TODO

    </div>

    <input type="hidden" name="option" value="com_matukio" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="contactPart" />
    <input type="hidden" name="controller" value="contactPart" />
    <input type="hidden" name="event_id" value="<?php echo $this->event_id; ?>" />

    <?php echo JHTML::_('form.token'); ?>
</form>

<?php echo MatukioHelperUtilsBasic::getCopyright(); ?>
